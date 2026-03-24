<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Tests\TestClasses\Controllers\PostMultipleTestController;
use Tests\TestClasses\Controllers\PostTestController;

it('can register a post route', function (): void {
    $this->routeRegistrar->registerClass(PostTestController::class);

    $this
        ->expectRegisteredRoutesCount(1)
        ->expectRouteRegistered(PostTestController::class, 'myPostMethod', 'post', 'my-post-method');
});

it('can register multiple post routes', function (): void {
    $this->routeRegistrar->registerClass(PostMultipleTestController::class);

    $this
        ->expectRegisteredRoutesCount(2)
        ->expectRouteRegistered(PostMultipleTestController::class, 'myPostMethod', 'post', 'my-post-method')
        ->expectRouteRegistered(PostMultipleTestController::class, 'myPostMethod', 'post', 'my-other-post-method');
});
