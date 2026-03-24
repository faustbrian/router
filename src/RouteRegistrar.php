<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cline\Router;

use Cline\AttributeReader\Attributes;
use Cline\Router\Attributes\Defaults;
use Cline\Router\Attributes\Fallback;
use Cline\Router\Attributes\Route;
use Cline\Router\Attributes\ScopeBindings;
use Cline\Router\Attributes\Where;
use Cline\Router\Attributes\WhereAttributeInterface;
use Cline\Router\Attributes\WithTrashed;
use Closure;
use Illuminate\Routing\Router;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionMethod;
use SplFileInfo;
use Symfony\Component\Finder\Finder;

use const DIRECTORY_SEPARATOR;

use function app;
use function array_filter;
use function class_exists;
use function count;
use function in_array;
use function is_string;
use function mb_rtrim;
use function mb_trim;
use function str_replace;
use function ucfirst;
use function usort;

/**
 * Registers Laravel routes from PHP attributes discovered on classes.
 *
 * The registrar is the package's orchestration layer. It discovers controller
 * classes, resolves class-level grouping metadata, expands method-level route
 * attributes into concrete Laravel routes, and applies cross-cutting concerns
 * such as middleware, scoped bindings, defaults, where constraints, fallback
 * markers, and `withTrashed` semantics in a predictable order.
 *
 * Directory registration intentionally sorts grouped routes so domain-specific
 * groups register before non-domain groups. That mirrors Laravel's matching
 * behavior and avoids broad host-agnostic routes shadowing tenant or subdomain
 * registrations generated from the same controller class.
 *
 * @author Brian Faust <brian@cline.sh>
 * @phpstan-type RouteGroup array{
 *     domain?: string,
 *     prefix?: string,
 *     where?: array<string, string>,
 *     as?: string,
 * }
 * @phpstan-type GroupedRoute array{
 *     class: ReflectionClass<object>,
 *     classRouteAttributes: ClassRouteAttributes,
 *     group: RouteGroup,
 * }
 * @phpstan-type MethodAttributes array{
 *     0: list<Route>,
 *     1: list<Where>,
 *     2: list<Defaults>,
 *     3: list<Fallback>,
 *     4: ?ScopeBindings,
 *     5: ?WithTrashed,
 * }
 */
final class RouteRegistrar
{
    /**
     * Base filesystem path used to convert discovered files into class names.
     */
    private string $basePath;

    /**
     * Namespace prefix applied when converting file paths into FQCNs.
     */
    private string $rootNamespace;

    /**
     * Global middleware prepended to every route and resource registered here.
     *
     * @var list<string>
     */
    private array $middleware = [];

    /**
     * Create a registrar with the current application path as the default base.
     */
    public function __construct(
        private readonly Router $router,
    ) {
        $this->useBasePath(app()->path());
    }

    /**
     * Create a temporary Laravel route group while retaining the fluent API.
     *
     * @param array<string, mixed>                $options
     * @param array<string, mixed>|Closure|string $routes
     */
    public function group(array $options, array|Closure|string $routes): self
    {
        $this->router->group($options, $routes);

        return $this;
    }

    /**
     * Set the filesystem root used when deriving a class name from a file path.
     *
     * Call this before `registerDirectory()` when the scanned files live outside
     * the default application path.
     */
    public function useBasePath(string $basePath): self
    {
        $this->basePath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $basePath);

        return $this;
    }

    /**
     * Set the namespace prefix used for discovered route classes.
     *
     * The registrar normalizes slashes and guarantees a trailing namespace
     * separator so later file-to-class conversion can concatenate safely.
     */
    public function useRootNamespace(string $rootNamespace): self
    {
        $this->rootNamespace = mb_rtrim(str_replace('/', '\\', $rootNamespace), '\\').'\\';

        return $this;
    }

    /**
     * Define middleware that should be attached to every generated route.
     *
     * These middleware entries are prepended ahead of class-level and
     * method-level attribute middleware so application-wide guards can wrap all
     * attribute-driven registrations consistently.
     *
     * @param array<int|string, string>|string $middleware
     */
    public function useMiddleware(string|array $middleware): self
    {
        /** @var list<string> $wrappedMiddleware */
        $wrappedMiddleware = Arr::wrap($middleware);

        $this->middleware = $wrappedMiddleware;

        return $this;
    }

    /**
     * Return the currently configured global middleware stack.
     *
     * @return list<string>
     */
    public function middleware(): array
    {
        return $this->middleware ?? [];
    }

    /**
     * Discover PHP files in one or more directories and register all routes they
     * describe.
     *
     * File discovery defaults to `*.php` and respects optional inclusion and
     * exclusion patterns. The resulting class groups are sorted so routes with an
     * explicit domain register before general groups.
     *
     * @param array<int|string, string>|string $directories
     * @param list<string>                     $patterns
     * @param list<string>                     $notPatterns
     */
    public function registerDirectory(string|array $directories, array $patterns = [], array $notPatterns = []): void
    {
        /** @var list<string> $directories */
        $directories = Arr::wrap($directories);
        $patterns = $patterns ?: ['*.php'];

        $files = new Finder()->files()->in($directories)->name($patterns)->notName($notPatterns)->sortByName();

        $this
            ->collectGroupsFromFiles($files)
            ->sortByDesc(fn ($item): bool => !in_array($item['group']['domain'] ?? null, [null, '', '0'], true))
            ->each(fn (array $item) => $this->registerGroupedRoutes($item));
    }

    /**
     * Register routes described by a single PHP file.
     *
     * String paths are normalized to `SplFileInfo` so both direct calls and
     * Finder-driven discovery follow the same file-to-class resolution path.
     */
    public function registerFile(string|SplFileInfo $path): void
    {
        if (is_string($path)) {
            $path = new SplFileInfo($path);
        }

        $fullyQualifiedClassName = $this->fullQualifiedClassNameFromFile($path);

        $this->processAttributes($fullyQualifiedClassName);
    }

    /**
     * Register routes for an already-known fully qualified class name.
     */
    public function registerClass(string $class): void
    {
        $this->processAttributes($class);
    }

    /**
     * Apply scoped binding behavior with method-level configuration overriding
     * class-level defaults.
     */
    public function setScopeBindingsIfAvailable(?ScopeBindings $scopeBindingsAttribute, \Illuminate\Routing\Route $route, ClassRouteAttributes $classRouteAttributes): void
    {
        $scopeBindings = $scopeBindingsAttribute instanceof ScopeBindings
            ? $scopeBindingsAttribute->scopeBindings
            : $classRouteAttributes->scopeBindings();

        match ($scopeBindings) {
            true => $route->scopeBindings(),
            false => $route->withoutScopedBindings(),
            null => null,
        };
    }

    /**
     * Resolve every route-related attribute attached to a controller method.
     *
     * The tuple shape is fixed so the registration pipeline can destructure the
     * result cheaply without repeated reflection work.
     *
     * @param  class-string     $className
     * @return MethodAttributes
     */
    public function getAttributesForTheMethod(string $className, string $methodName): array
    {
        /** @var list<Route> $routeAttributes */
        $routeAttributes = Attributes::getAllOnMethod($className, $methodName, Route::class);

        /** @var list<Where> $wheresAttributes */
        $wheresAttributes = Attributes::getAllOnMethod($className, $methodName, WhereAttributeInterface::class);

        /** @var list<Defaults> $defaultAttributes */
        $defaultAttributes = Attributes::getAllOnMethod($className, $methodName, Defaults::class);

        /** @var list<Fallback> $fallbackAttributes */
        $fallbackAttributes = Attributes::getAllOnMethod($className, $methodName, Fallback::class);
        $scopeBindingsAttribute = Attributes::onMethod($className, $methodName, ScopeBindings::class);
        $withTrashedAttribute = Attributes::onMethod($className, $methodName, WithTrashed::class);

        return [
            $routeAttributes,
            $wheresAttributes,
            $defaultAttributes,
            $fallbackAttributes,
            $scopeBindingsAttribute instanceof ScopeBindings ? $scopeBindingsAttribute : null,
            $withTrashedAttribute instanceof WithTrashed ? $withTrashedAttribute : null,
        ];
    }

    /**
     * Merge class-level and method-level where constraints onto the route.
     *
     * Method attributes win on duplicate parameter names because they are applied
     * after the class defaults are loaded.
     *
     * @param list<Where> $wheresAttributes
     */
    public function setWheresIfAvailable(ClassRouteAttributes $classRouteAttributes, array $wheresAttributes, \Illuminate\Routing\Route $route): void
    {
        $wheres = $classRouteAttributes->wheres();

        foreach ($wheresAttributes as $wheresAttribute) {
            $wheres[$wheresAttribute->param] = $wheresAttribute->constraint;
        }

        if ($wheres === []) {
            return;
        }

        $route->setWheres($wheres);
    }

    /**
     * Build the HTTP verb list and controller action tuple for a route attribute.
     *
     * Invokable controllers are registered using the class name directly so they
     * match Laravel's expected action signature.
     *
     * @param  ReflectionClass<object>                                                   $class
     * @return array{0: list<string>, 1: array{0: class-string, 1: string}|class-string}
     */
    public function getHTTPMethodsAndAction(Route $attributeClass, ReflectionMethod $method, ReflectionClass $class): array
    {
        $httpMethods = $attributeClass->methods;
        $action = $method->getName() === '__invoke' ? $class->getName() : [$class->getName(), $method->getName()];

        return [$httpMethods, $action];
    }

    /**
     * Apply middleware in the package's precedence order.
     *
     * Global registrar middleware wraps class middleware, which in turn wraps the
     * route-specific middleware declared on the concrete HTTP attribute.
     */
    public function addMiddlewareToRoute(ClassRouteAttributes $classRouteAttributes, Route $attributeClass, \Illuminate\Routing\Route $route): void
    {
        $classMiddleware = $classRouteAttributes->middleware();
        $methodMiddleware = $attributeClass->middleware;
        $route->middleware([...$this->middleware, ...$classMiddleware, ...$methodMiddleware]);
    }

    /**
     * Merge class defaults with method defaults and attach them to the route.
     *
     * Method-level defaults override class-level keys because they are written
     * into the merged array last.
     *
     * @param list<Defaults> $defaultAttributes
     */
    public function setDefaultsIfAvailable(ClassRouteAttributes $classRouteAttributes, array $defaultAttributes, \Illuminate\Routing\Route $route): void
    {
        $defaults = $classRouteAttributes->defaults();

        foreach ($defaultAttributes as $defaultAttribute) {
            $defaults[$defaultAttribute->key] = $defaultAttribute->value;
        }

        if ($defaults === []) {
            return;
        }

        $route->setDefaults($defaults);
    }

    /**
     * Apply `withTrashed` behavior with method-level overrides when present.
     */
    public function setWithTrashedIfAvailable(ClassRouteAttributes $classRouteAttributes, ?WithTrashed $withTrashedAttribute, \Illuminate\Routing\Route $route): void
    {
        $withTrashed = $classRouteAttributes->withTrashed();

        if ($withTrashedAttribute instanceof WithTrashed) {
            $route->withTrashed($withTrashedAttribute->withTrashed);
        } else {
            $route->withTrashed($withTrashed);
        }
    }

    /**
     * Create the closure used when registering resource or apiResource routes.
     *
     * Resource options are resolved once from the class attribute wrapper and
     * then replayed against Laravel's pending resource registration object.
     *
     * @param ReflectionClass<object> $class
     */
    public function getRoutes(ReflectionClass $class, ClassRouteAttributes $classRouteAttributes): Closure
    {
        return function () use ($class, $classRouteAttributes): void {
            $resource = $classRouteAttributes->resource();

            if ($resource === null) {
                return;
            }

            $route = $classRouteAttributes->apiResource()
                ? $this->router->apiResource($resource, $class->getName())
                : $this->router->resource($resource, $class->getName());

            if (($only = $classRouteAttributes->only()) !== null) {
                $route->only($only);
            }

            if (($except = $classRouteAttributes->except()) !== null) {
                $route->except($except);
            }

            if (($names = $classRouteAttributes->names()) !== null) {
                $route->names($names);
            }

            if (($parameters = $classRouteAttributes->parameters()) !== null) {
                $route->parameters($parameters);
            }

            if (($shallow = $classRouteAttributes->shallow()) !== null) {
                $route->shallow($shallow);
            }

            $route->middleware([...$this->middleware, ...$classRouteAttributes->middleware()]);
        };
    }

    /**
     * Build grouped route descriptors from discovered files.
     *
     * Files that do not resolve to an autoloadable class are ignored so partial
     * scans or stray helper files do not break registration.
     *
     * @return Collection<int, GroupedRoute>
     */
    private function collectGroupsFromFiles(Finder $files): Collection
    {
        $groups = [];

        foreach ($files as $file) {
            $className = $this->fullQualifiedClassNameFromFile($file);

            if (!class_exists($className)) {
                continue;
            }

            $class = new ReflectionClass($className);

            $groups = [...$groups, ...$this->expandClassIntoGroups([
                'class' => $class,
                'classRouteAttributes' => new ClassRouteAttributes($class),
            ])];
        }

        return new Collection($groups);
    }

    /**
     * Expand a single class into one or more route groups.
     *
     * A controller may declare multiple `#[Group]` attributes, each of which
     * produces an independent registration pass over the same class methods.
     *
     * @param  array{class: ReflectionClass<object>, classRouteAttributes: ClassRouteAttributes} $classData
     * @return list<GroupedRoute>
     */
    private function expandClassIntoGroups(array $classData): array
    {
        $groups = [];

        foreach ($classData['classRouteAttributes']->groups() as $group) {
            $groups[] = [
                'class' => $classData['class'],
                'classRouteAttributes' => $classData['classRouteAttributes'],
                'group' => $group,
            ];
        }

        return $groups;
    }

    /**
     * Register a previously expanded group and its optional resource routes.
     *
     * Resource routes are registered after method routes for the same group so
     * class-level resource metadata is handled in one place.
     *
     * @param GroupedRoute $item
     */
    private function registerGroupedRoutes(array $item): void
    {
        $this->router->group(
            $item['group'],
            fn () => $this->registerRoutes($item['class'], $item['classRouteAttributes']),
        );

        if (!$item['classRouteAttributes']->resource()) {
            return;
        }

        $this->registerResource($item['class'], $item['classRouteAttributes']);
    }

    /**
     * Convert a discovered file path into the class name that should own it.
     */
    private function fullQualifiedClassNameFromFile(SplFileInfo $file): string
    {
        $class = mb_trim(Str::replaceFirst($this->basePath, '', $file->getRealPath()), DIRECTORY_SEPARATOR);

        $class = str_replace(
            [DIRECTORY_SEPARATOR, 'App\\'],
            ['\\', app()->getNamespace()],
            ucfirst(Str::replaceLast('.php', '', $class)),
        );

        return $this->rootNamespace.$class;
    }

    /**
     * Reflect a class and register every grouped and resource route it exposes.
     *
     * Group ordering mirrors `registerDirectory()` by prioritizing domain-aware
     * groups first for individual class registration calls as well.
     */
    private function processAttributes(string $className): void
    {
        if (!class_exists($className)) {
            return;
        }

        $class = new ReflectionClass($className);

        $classRouteAttributes = new ClassRouteAttributes($class);

        $groups = $classRouteAttributes->groups();

        // Note: When called from registerDirectory, groups are already globally sorted
        // This sorting is only for individual registerClass calls
        usort($groups, function (mixed $group1, mixed $group2): int {
            /** @var RouteGroup $group1 */
            /** @var RouteGroup $group2 */
            $domain1 = !in_array($group1['domain'] ?? null, [null, '', '0'], true);
            $domain2 = !in_array($group2['domain'] ?? null, [null, '', '0'], true);

            return $domain2 <=> $domain1; // Domain routes come first
        });

        foreach ($groups as $group) {
            $router = $this->router;
            $router->group($group, fn () => $this->registerRoutes($class, $classRouteAttributes));
        }

        if (!$classRouteAttributes->resource()) {
            return;
        }

        $this->registerResource($class, $classRouteAttributes);
    }

    /**
     * Register resource routes inside the class-level domain and prefix context.
     *
     * Group-specific `#[Group]` metadata is intentionally not replayed here;
     * resources use the canonical domain and prefix attributes of the class.
     *
     * @param ReflectionClass<object> $class
     */
    private function registerResource(ReflectionClass $class, ClassRouteAttributes $classRouteAttributes): void
    {
        $this->router->group(array_filter([
            'domain' => $classRouteAttributes->domain(),
            'prefix' => $classRouteAttributes->prefix(),
        ]), $this->getRoutes($class, $classRouteAttributes));
    }

    /**
     * Register all method-level HTTP routes declared on a controller class.
     *
     * Each route attribute produces an independent Laravel route. Method-level
     * options override or extend class-level defaults before the route is marked
     * as a fallback when requested.
     *
     * @param ReflectionClass<object> $class
     */
    private function registerRoutes(ReflectionClass $class, ClassRouteAttributes $classRouteAttributes): void
    {
        $className = $class->getName();

        foreach ($class->getMethods() as $method) {
            $methodName = $method->getName();
            [$routeAttributes, $wheresAttributes, $defaultAttributes, $fallbackAttributes, $scopeBindingsAttribute, $withTrashedAttribute] = $this->getAttributesForTheMethod($className, $methodName);

            foreach ($routeAttributes as $attributeClass) {
                [$httpMethods, $action] = $this->getHTTPMethodsAndAction($attributeClass, $method, $class);

                $route = $this->router->addRoute($httpMethods, $attributeClass->uri, $action);

                if ($attributeClass->name !== null) {
                    $route->name($attributeClass->name);
                }

                $this->setScopeBindingsIfAvailable($scopeBindingsAttribute, $route, $classRouteAttributes);

                $this->setWheresIfAvailable($classRouteAttributes, $wheresAttributes, $route);

                $this->setDefaultsIfAvailable($classRouteAttributes, $defaultAttributes, $route);

                $this->addMiddlewareToRoute($classRouteAttributes, $attributeClass, $route);

                $this->addWithoutMiddlewareToRoute($attributeClass, $route);

                $this->setWithTrashedIfAvailable($classRouteAttributes, $withTrashedAttribute, $route);

                if (count($fallbackAttributes) <= 0) {
                    continue;
                }

                $route->fallback();
            }
        }
    }

    /**
     * Remove middleware declared via the route attribute after the route exists.
     */
    private function addWithoutMiddlewareToRoute(Route $attributeClass, \Illuminate\Routing\Route $route): void
    {
        $methodWithoutMiddleware = $attributeClass->withoutMiddleware;
        $route->withoutMiddleware($methodWithoutMiddleware);
    }
}
