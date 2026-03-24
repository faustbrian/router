<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Tests\TestClasses\Controllers\WithoutMiddlewareTestController;
use Tests\TestClasses\Middleware\SkippedMiddleware;

it('can skip middleware added to class', function (): void {
    $this->routeRegistrar->registerClass(WithoutMiddlewareTestController::class);

    $this
        ->expectRegisteredRoutesCount(1)
        ->expectRouteRegistered(
            WithoutMiddlewareTestController::class,
            controllerMethod: 'withoutMiddleware',
            uri: 'without-middleware',
            withoutMiddleware: [SkippedMiddleware::class],
        );
});
