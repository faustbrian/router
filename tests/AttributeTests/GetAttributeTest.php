<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Tests\TestClasses\Controllers\GetMultipleTestController;
use Tests\TestClasses\Controllers\GetTestController;

it('can register a get route', function (): void {
    $this->routeRegistrar->registerClass(GetTestController::class);

    $this
        ->expectRegisteredRoutesCount(1)
        ->expectRouteRegistered(GetTestController::class, 'myGetMethod', 'get', 'my-get-method');
});

it('can register multiple get routes', function (): void {
    $this->routeRegistrar->registerClass(GetMultipleTestController::class);

    $this
        ->expectRegisteredRoutesCount(2)
        ->expectRouteRegistered(GetMultipleTestController::class, 'myGetMethod', 'get', 'my-get-method')
        ->expectRouteRegistered(GetMultipleTestController::class, 'myGetMethod', 'get', 'my-other-get-method');
});
