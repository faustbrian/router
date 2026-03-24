<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Tests\TestClasses\Controllers\PatchMultipleTestController;
use Tests\TestClasses\Controllers\PatchTestController;

it('can register a patch route', function (): void {
    $this->routeRegistrar->registerClass(PatchTestController::class);

    $this
        ->expectRegisteredRoutesCount(1)
        ->expectRouteRegistered(PatchTestController::class, 'myPatchMethod', 'patch', 'my-patch-method');
});

it('can register multiple patch routes', function (): void {
    $this->routeRegistrar->registerClass(PatchMultipleTestController::class);

    $this
        ->expectRegisteredRoutesCount(2)
        ->expectRouteRegistered(PatchMultipleTestController::class, 'myPatchMethod', 'patch', 'my-patch-method')
        ->expectRouteRegistered(PatchMultipleTestController::class, 'myPatchMethod', 'patch', 'my-other-patch-method');
});
