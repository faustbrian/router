<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Tests\TestClasses\Controllers\AnyTestController;

it('can register an any route', function (): void {
    $this->routeRegistrar->registerClass(AnyTestController::class);

    $this
        ->expectRegisteredRoutesCount(1)
        ->expectRouteRegistered(AnyTestController::class, 'myAnyMethod', 'head', 'my-any-method')
        ->expectRouteRegistered(AnyTestController::class, 'myAnyMethod', 'get', 'my-any-method')
        ->expectRouteRegistered(AnyTestController::class, 'myAnyMethod', 'post', 'my-any-method')
        ->expectRouteRegistered(AnyTestController::class, 'myAnyMethod', 'put', 'my-any-method')
        ->expectRouteRegistered(AnyTestController::class, 'myAnyMethod', 'patch', 'my-any-method')
        ->expectRouteRegistered(AnyTestController::class, 'myAnyMethod', 'delete', 'my-any-method')
        ->expectRouteRegistered(AnyTestController::class, 'myAnyMethod', 'options', 'my-any-method');
});
