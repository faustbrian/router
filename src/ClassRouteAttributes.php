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
use Cline\Router\Attributes\Domain;
use Cline\Router\Attributes\DomainFromConfig;
use Cline\Router\Attributes\Group;
use Cline\Router\Attributes\Middleware;
use Cline\Router\Attributes\Prefix;
use Cline\Router\Attributes\Resource;
use Cline\Router\Attributes\RouteAttributeInterface;
use Cline\Router\Attributes\ScopeBindings;
use Cline\Router\Attributes\Where;
use Cline\Router\Attributes\WithTrashed;
use ReflectionClass;

use function array_filter;
use function config;
use function count;
use function is_bool;
use function is_string;

/**
 * Reads and normalizes class-level routing attributes for a single controller.
 *
 * This wrapper centralizes reflection and configuration fallback rules so the
 * registrar can consume a stable API instead of reasoning about individual
 * attribute classes. It also documents the precedence model used by the package:
 * explicit attributes on the class beat configuration defaults, while repeated
 * `#[Group]` attributes fan out a controller into multiple registrations.
 *
 * @author Brian Faust <brian@cline.sh>
 * @phpstan-type RouteGroup array{
 *     domain?: string,
 *     prefix?: string,
 *     where?: array<string, string>,
 *     as?: string,
 * }
 * @psalm-immutable
 */
final readonly class ClassRouteAttributes
{
    /**
     * Reflected controller class name used for repeated attribute lookups.
     *
     * @var class-string
     */
    private string $className;

    /**
     * Capture the reflected class name once so later lookups avoid repeated
     * extraction from the `ReflectionClass`.
     *
     * @param ReflectionClass<object> $class
     */
    public function __construct(
        ReflectionClass $class,
    ) {
        /** @var class-string $className */
        $className = $class->getName();

        $this->className = $className;
    }

    /**
     * Return the class-level URI prefix, if any.
     *
     * @psalm-suppress NoInterfaceProperties
     */
    public function prefix(): ?string
    {
        $attribute = $this->getAttribute(Prefix::class);

        if (!$attribute instanceof Prefix) {
            return null;
        }

        return $attribute->prefix;
    }

    /**
     * Return the explicit class-level route domain.
     *
     * @psalm-suppress NoInterfaceProperties
     */
    public function domain(): ?string
    {
        $attribute = $this->getAttribute(Domain::class);

        if (!$attribute instanceof Domain) {
            return null;
        }

        return $attribute->domain;
    }

    /**
     * Resolve the route domain from configuration when `#[DomainFromConfig]` is
     * present and the configured value is a string.
     *
     * @psalm-suppress NoInterfaceProperties
     */
    public function domainFromConfig(): ?string
    {
        $attribute = $this->getAttribute(DomainFromConfig::class);

        if (!$attribute instanceof DomainFromConfig) {
            return null;
        }

        $domain = config($attribute->domain);

        return is_string($domain) ? $domain : null;
    }

    /**
     * Build the route group definitions that should wrap this controller.
     *
     * Repeated `#[Group]` attributes take precedence over the simpler
     * domain/prefix attributes and cause the controller to be registered once per
     * group. Without explicit groups, the registrar falls back to a single group
     * using the resolved domain and prefix.
     *
     * @return list<RouteGroup>
     * @psalm-suppress NoInterfaceProperties
     */
    public function groups(): array
    {
        $groups = [];

        /** @var list<Group> $groupAttributes */
        $groupAttributes = Attributes::getAll($this->className, Group::class);

        if (count($groupAttributes) > 0) {
            foreach ($groupAttributes as $attributeClass) {
                $groups[] = array_filter([
                    'domain' => $attributeClass->domain,
                    'prefix' => $attributeClass->prefix,
                    'where' => $attributeClass->where,
                    'as' => $attributeClass->as,
                ]);
            }
        } else {
            $groups[] = array_filter([
                'domain' => $this->domainFromConfig() ?? $this->domain(),
                'prefix' => $this->prefix(),
            ]);
        }

        return $groups;
    }

    /**
     * Return the resource name for resource or apiResource registration.
     *
     * @psalm-suppress NoInterfaceProperties
     */
    public function resource(): ?string
    {
        $attribute = $this->getAttribute(Resource::class);

        if (!$attribute instanceof Resource) {
            return null;
        }

        return $attribute->resource;
    }

    /**
     * Return custom parameter mappings for a resource declaration.
     *
     * @return null|array<int|string, string>|string
     * @psalm-suppress NoInterfaceProperties
     */
    public function parameters(): array|string|null
    {
        $attribute = $this->getAttribute(Resource::class);

        if (!$attribute instanceof Resource) {
            return null;
        }

        return $attribute->parameters;
    }

    /**
     * Return the shallow nesting flag for resource routes.
     *
     * @psalm-suppress NoInterfaceProperties
     */
    public function shallow(): ?bool
    {
        $attribute = $this->getAttribute(Resource::class);

        if (!$attribute instanceof Resource) {
            return null;
        }

        return $attribute->shallow;
    }

    /**
     * Determine whether the class should register `apiResource()` instead of the
     * full `resource()` route set.
     *
     * @psalm-suppress NoInterfaceProperties
     */
    public function apiResource(): ?bool
    {
        $attribute = $this->getAttribute(Resource::class);

        if (!$attribute instanceof Resource) {
            return null;
        }

        return $attribute->apiResource;
    }

    /**
     * Return resource actions that should be excluded from generation.
     *
     * @return null|array<int|string, string>|string
     * @psalm-suppress NoInterfaceProperties
     */
    public function except(): string|array|null
    {
        $attribute = $this->getAttribute(Resource::class);

        if (!$attribute instanceof Resource) {
            return null;
        }

        return $attribute->except;
    }

    /**
     * Return the whitelist of resource actions that should be generated.
     *
     * @return null|array<int|string, string>|string
     * @psalm-suppress NoInterfaceProperties
     */
    public function only(): string|array|null
    {
        $attribute = $this->getAttribute(Resource::class);

        if (!$attribute instanceof Resource) {
            return null;
        }

        return $attribute->only;
    }

    /**
     * Return custom route names for generated resource routes.
     *
     * @return null|array<int|string, string>|string
     * @psalm-suppress NoInterfaceProperties
     */
    public function names(): string|array|null
    {
        $attribute = $this->getAttribute(Resource::class);

        if (!$attribute instanceof Resource) {
            return null;
        }

        return $attribute->names;
    }

    /**
     * Return middleware that should apply to every route in the controller.
     *
     * @return list<string>
     * @psalm-suppress NoInterfaceProperties
     */
    public function middleware(): array
    {
        $attribute = $this->getAttribute(Middleware::class);

        if (!$attribute instanceof Middleware) {
            return [];
        }

        return $attribute->middleware;
    }

    /**
     * Resolve class-level scoped binding behavior.
     *
     * If the attribute is absent, the package falls back to the
     * `router.scope-bindings` configuration value when it is explicitly boolean.
     */
    public function scopeBindings(): ?bool
    {
        $attribute = $this->getAttribute(ScopeBindings::class);

        if (!$attribute instanceof ScopeBindings) {
            $scopeBindings = config('router.scope-bindings');

            return is_bool($scopeBindings) ? $scopeBindings : null;
        }

        return $attribute->scopeBindings;
    }

    /**
     * Collect class-level where constraints indexed by route parameter name.
     *
     * @return array<string, string>
     * @psalm-suppress NoInterfaceProperties
     */
    public function wheres(): array
    {
        $wheres = [];

        /** @var list<Where> $whereAttributes */
        $whereAttributes = Attributes::getAll($this->className, Where::class);

        foreach ($whereAttributes as $attributeClass) {
            $wheres[$attributeClass->param] = $attributeClass->constraint;
        }

        return $wheres;
    }

    /**
     * Collect class-level route defaults indexed by default key.
     *
     * @return array<string, string>
     * @psalm-suppress NoInterfaceProperties
     */
    public function defaults(): array
    {
        $defaults = [];

        /** @var list<Defaults> $defaultAttributes */
        $defaultAttributes = Attributes::getAll($this->className, Defaults::class);

        foreach ($defaultAttributes as $attributeClass) {
            $defaults[$attributeClass->key] = $attributeClass->value;
        }

        return $defaults;
    }

    /**
     * Determine whether implicit model binding should include soft-deleted rows.
     *
     * @psalm-suppress NoInterfaceProperties
     */
    public function withTrashed(): bool
    {
        $attribute = $this->getAttribute(WithTrashed::class);

        if (!$attribute instanceof WithTrashed) {
            return false;
        }

        return $attribute->withTrashed;
    }

    /**
     * Fetch a single class-level route attribute and normalize the return type to
     * the package marker interface.
     *
     * @param class-string<RouteAttributeInterface> $attributeClass
     */
    private function getAttribute(string $attributeClass): ?RouteAttributeInterface
    {
        $attribute = Attributes::get($this->className, $attributeClass);

        return $attribute instanceof RouteAttributeInterface ? $attribute : null;
    }
}
