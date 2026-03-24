<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Tests\TestClasses\Controllers\PrefixTestController;

it('can apply a prefix on the url of every method', function (): void {
    $this->routeRegistrar->registerClass(PrefixTestController::class);

    $this
        ->expectRegisteredRoutesCount(3)
        ->expectRouteRegistered(
            PrefixTestController::class,
            controllerMethod: 'myRootGetMethod',
            uri: 'my-prefix',
        )
        ->expectRouteRegistered(
            PrefixTestController::class,
            controllerMethod: 'myGetMethod',
            uri: 'my-prefix/my-get-method',
        )
        ->expectRouteRegistered(
            PrefixTestController::class,
            controllerMethod: 'myPostMethod',
            httpMethods: 'post',
            uri: 'my-prefix/my-post-method',
        );
});
