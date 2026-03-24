<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Tests\TestClasses\Controllers\OptionsMultipleTestController;
use Tests\TestClasses\Controllers\OptionsTestController;

it('can register an options route', function (): void {
    $this->routeRegistrar->registerClass(OptionsTestController::class);

    $this
        ->expectRegisteredRoutesCount(1)
        ->expectRouteRegistered(OptionsTestController::class, 'myOptionsMethod', 'options', 'my-options-method');
});

it('can register multiple options routes', function (): void {
    $this->routeRegistrar->registerClass(OptionsMultipleTestController::class);

    $this
        ->expectRegisteredRoutesCount(2)
        ->expectRouteRegistered(OptionsMultipleTestController::class, 'myOptionsMethod', 'options', 'my-options-method')
        ->expectRouteRegistered(OptionsMultipleTestController::class, 'myOptionsMethod', 'options', 'my-other-options-method');
});
