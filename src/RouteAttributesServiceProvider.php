<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cline\Router;

use Illuminate\Foundation\Application;
use Illuminate\Routing\Router;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Override;

use function app;
use function array_filter;
use function collect;
use function config;
use function config_path;
use function is_array;
use function is_string;

/**
 * Boots the package and triggers attribute-driven route registration.
 *
 * The service provider owns package configuration, publishes the config file
 * for consumers, and decides whether automatic route discovery should run for
 * the current application lifecycle.
 *
 * @author Brian Faust <brian@cline.sh>
 */
final class RouteAttributesServiceProvider extends ServiceProvider
{
    /**
     * Publish configuration when appropriate and register routes eagerly.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/router.php' => config_path('router.php'),
            ], 'config');
        }

        $this->registerRoutes();
    }

    #[Override()]
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/router.php', 'router');
    }

    /**
     * Resolve the registrar and replay the configured directory definitions.
     *
     * Each entry may be a plain directory path or an option array that supplies
     * namespace, base path, group options, and Finder pattern filters.
     */
    private function registerRoutes(): void
    {
        if (!$this->shouldRegisterRoutes()) {
            return;
        }

        /** @var Router $router */
        $router = $this->app->make(Router::class);
        $middleware = config('router.middleware');

        /** @var array<int|string, string>|string $normalizedMiddleware */
        $normalizedMiddleware = is_string($middleware) || is_array($middleware) ? $middleware : [];

        $routeRegistrar = $this->app->make(RouteRegistrar::class, [$router])
            ->useMiddleware($normalizedMiddleware);

        collect($this->getRouteDirectories())->each(function (string|array $directory, string|int $namespace) use ($routeRegistrar): void {
            if (is_array($directory)) {
                /** @var array{namespace?: string, base_path?: string, patterns?: list<string>, not_patterns?: list<string>} $directory */
                $options = array_filter(
                    Arr::except($directory, ['namespace', 'base_path', 'patterns', 'not_patterns']),
                );

                /** @var array<string, mixed> $options */
                $routeDirectory = is_string($namespace) ? $namespace : app()->path();
                $basePath = $directory['base_path'] ?? $routeDirectory;

                $routeRegistrar
                    ->useRootNamespace($directory['namespace'] ?? app()->getNamespace())
                    ->useBasePath($basePath)
                    ->group($options, fn () => $routeRegistrar->registerDirectory($routeDirectory, $directory['patterns'] ?? [], $directory['not_patterns'] ?? []));
            } else {
                is_string($namespace)
                    ? $routeRegistrar
                        ->useRootNamespace($namespace)
                        ->useBasePath($directory)
                        ->registerDirectory($directory)
                    : $routeRegistrar
                        ->useRootNamespace(app()->getNamespace())
                        ->useBasePath(app()->path())
                        ->registerDirectory($directory);
            }
        });
    }

    /**
     * Decide whether package route discovery should run for this request cycle.
     *
     * Registration is skipped when the package is disabled or the application is
     * serving cached routes because Laravel expects the route table to be static.
     */
    private function shouldRegisterRoutes(): bool
    {
        if (config('router.enabled') !== true) {
            return false;
        }

        /** @var Application $app */
        $app = $this->app;

        return !$app->routesAreCached();
    }

    /**
     * Return the configured route directory definitions in normalized array form.
     *
     * @return array<int|string, array<string, mixed>|string>
     */
    private function getRouteDirectories(): array
    {
        $directories = config('router.directories');

        if (!is_array($directories)) {
            return [];
        }

        /** @var array<int|string, array<string, mixed>|string> $directories */
        return $directories;
    }
}
