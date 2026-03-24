<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Tests\TestClasses\Controllers\PutMultipleTestController;
use Tests\TestClasses\Controllers\PutTestController;

it('can register a put route', function (): void {
    $this->routeRegistrar->registerClass(PutTestController::class);

    $this
        ->expectRegisteredRoutesCount(1)
        ->expectRouteRegistered(PutTestController::class, 'myPutMethod', 'put', 'my-put-method');
});

it('can register multiple put routes', function (): void {
    $this->routeRegistrar->registerClass(PutMultipleTestController::class);

    $this
        ->expectRegisteredRoutesCount(2)
        ->expectRouteRegistered(PutMultipleTestController::class, 'myPutMethod', 'put', 'my-put-method')
        ->expectRouteRegistered(PutMultipleTestController::class, 'myPutMethod', 'put', 'my-other-put-method');
});
