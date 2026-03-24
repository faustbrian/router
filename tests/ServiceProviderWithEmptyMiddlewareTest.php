<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests;

use Cline\Router\RouteAttributesServiceProvider;
use Cline\Router\RouteRegistrar;
use Illuminate\Contracts\Config\Repository;
use Tests\TestClasses\Controllers\Grouped\GroupTestController;

use function resolve;

/**
 * @author Brian Faust <brian@cline.sh>
 * @internal
 */
final class ServiceProviderWithEmptyMiddlewareTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->routeRegistrar = resolve(RouteRegistrar::class);
    }

    public function test_empty_middleware_string_does_not_cause_binding_resolution_exception(): void
    {
        $this->expectRegisteredRoutesCount(11);

        $this->expectRouteRegistered(
            GroupTestController::class,
            controllerMethod: 'myGetMethod',
            uri: 'grouped/my-get-method',
            middleware: ['test'],
        );

        $this->expectRouteRegistered(
            GroupTestController::class,
            controllerMethod: 'myPostMethod',
            httpMethods: 'post',
            uri: 'grouped/my-post-method',
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            RouteAttributesServiceProvider::class,
        ];
    }

    protected function resolveApplicationConfiguration($app): void
    {
        parent::resolveApplicationConfiguration($app);

        $app->make(Repository::class)->set('router.directories', [
            __DIR__.'/TestClasses/Controllers/Grouped' => [
                'prefix' => 'grouped',
                'middleware' => '',
                'namespace' => 'Tests\TestClasses\Controllers\Grouped',
            ],
        ]);
    }
}
