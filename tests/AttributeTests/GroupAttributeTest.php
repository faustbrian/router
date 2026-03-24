<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Tests\TestClasses\Controllers\GroupTestController;

it('can apply a domain on the url of every method', function (): void {
    $this->routeRegistrar->registerClass(GroupTestController::class);

    $this
        ->expectRegisteredRoutesCount(4)
        ->expectRouteRegistered(
            GroupTestController::class,
            controllerMethod: 'myGetMethod',
            uri: 'my-prefix/my-get-method',
            domain: 'my-subdomain.localhost',
        )
        ->expectRouteRegistered(
            GroupTestController::class,
            controllerMethod: 'myPostMethod',
            httpMethods: 'post',
            uri: 'my-prefix/my-post-method',
            domain: 'my-subdomain.localhost',
        )
        ->expectRouteRegistered(
            GroupTestController::class,
            controllerMethod: 'myGetMethod',
            uri: 'my-second-prefix/my-get-method',
            domain: 'my-second-subdomain.localhost',
        )
        ->expectRouteRegistered(
            GroupTestController::class,
            controllerMethod: 'myPostMethod',
            httpMethods: 'post',
            uri: 'my-second-prefix/my-post-method',
            domain: 'my-second-subdomain.localhost',
        );
});
