<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Tests\TestClasses\Controllers\DeleteMultipleTestController;
use Tests\TestClasses\Controllers\DeleteTestController;

it('can register a delete route', function (): void {
    $this->routeRegistrar->registerClass(DeleteTestController::class);

    $this
        ->expectRegisteredRoutesCount(1)
        ->expectRouteRegistered(DeleteTestController::class, 'myDeleteMethod', 'delete', 'my-delete-method');
});

it('can register multiple delete routes', function (): void {
    $this->routeRegistrar->registerClass(DeleteMultipleTestController::class);

    $this
        ->expectRegisteredRoutesCount(2)
        ->expectRouteRegistered(DeleteMultipleTestController::class, 'myDeleteMethod', 'delete', 'my-delete-method')
        ->expectRouteRegistered(DeleteMultipleTestController::class, 'myDeleteMethod', 'delete', 'my-other-delete-method');
});
