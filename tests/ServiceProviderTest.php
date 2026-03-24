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
use Tests\TestClasses\Controllers\Grouped\GroupPrefixTestController;
use Tests\TestClasses\Controllers\Grouped\GroupResourceTestController;
use Tests\TestClasses\Controllers\Grouped\GroupResourceTestPrefixController;
use Tests\TestClasses\Controllers\Grouped\GroupTestController;

use function resolve;

/**
 * @author Brian Faust <brian@cline.sh>
 * @internal
 */
final class ServiceProviderTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->routeRegistrar = resolve(RouteRegistrar::class);
    }

    public function test_the_provider_can_register_group_of_directories(): void
    {
        $this->expectRegisteredRoutesCount(11);

        $this->expectRouteRegistered(
            GroupTestController::class,
            controllerMethod: 'myGetMethod',
            uri: 'grouped/my-get-method',
            middleware: ['SomeMiddleware', 'api', 'test'],
        );

        $this->expectRouteRegistered(
            GroupTestController::class,
            controllerMethod: 'myPostMethod',
            httpMethods: 'post',
            uri: 'grouped/my-post-method',
            middleware: ['SomeMiddleware', 'api'],
        );

        $this->expectRouteRegistered(
            GroupResourceTestController::class,
            controllerMethod: 'index',
            httpMethods: 'get',
            uri: 'grouped/posts',
            middleware: ['SomeMiddleware', 'api'],
            name: 'posts.index',
        );

        $this->expectRouteRegistered(
            GroupResourceTestController::class,
            controllerMethod: 'store',
            httpMethods: 'post',
            uri: 'grouped/posts',
            middleware: ['SomeMiddleware', 'api'],
            name: 'posts.store',
        );

        $this->expectRouteRegistered(
            GroupResourceTestController::class,
            controllerMethod: 'show',
            httpMethods: 'get',
            uri: 'grouped/posts/{post}',
            middleware: ['SomeMiddleware', 'api'],
            name: 'posts.show',
        );

        $this->expectRouteRegistered(
            GroupResourceTestController::class,
            controllerMethod: 'update',
            httpMethods: 'put',
            uri: 'grouped/posts/{post}',
            middleware: ['SomeMiddleware', 'api'],
            name: 'posts.update',
        );

        $this->expectRouteRegistered(
            GroupResourceTestController::class,
            controllerMethod: 'destroy',
            httpMethods: 'delete',
            uri: 'grouped/posts/{post}',
            middleware: ['SomeMiddleware', 'api'],
            name: 'posts.destroy',
        );

        $this->expectRouteRegistered(
            GroupResourceTestPrefixController::class,
            controllerMethod: 'index',
            httpMethods: 'get',
            uri: 'grouped/api/v1/my-prefix/etc/posts',
            middleware: ['SomeMiddleware', 'api'],
            name: 'prefixed_posts.index',
        );

        $this->expectRouteRegistered(
            GroupResourceTestPrefixController::class,
            controllerMethod: 'show',
            httpMethods: 'get',
            uri: 'grouped/api/v1/my-prefix/etc/posts/{post}',
            middleware: ['SomeMiddleware', 'api'],
            name: 'prefixed_posts.show',
        );

        $this->expectRouteRegistered(
            GroupPrefixTestController::class,
            controllerMethod: 'myGetMethod',
            httpMethods: 'get',
            uri: 'grouped/my-prefix/my-prefix-get-method',
            middleware: ['SomeMiddleware', 'api'],
        );

        $this->expectRouteRegistered(
            GroupPrefixTestController::class,
            controllerMethod: 'myPostMethod',
            httpMethods: 'post',
            uri: 'grouped/my-prefix/my-prefix-post-method',
            middleware: ['SomeMiddleware', 'api'],
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

        $app->make(Repository::class)->set('router.middleware', ['SomeMiddleware']);
        $app->make(Repository::class)->set('router.directories', [
            __DIR__.'/TestClasses/Controllers/Grouped' => [
                'prefix' => 'grouped',
                'middleware' => 'api',
                'namespace' => 'Tests\TestClasses\Controllers\Grouped',
            ],
        ]);
    }
}
