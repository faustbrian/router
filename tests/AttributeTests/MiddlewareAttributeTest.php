<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Tests\TestClasses\Controllers\MiddlewareTestController;
use Tests\TestClasses\Middleware\OtherTestMiddleware;
use Tests\TestClasses\Middleware\TestMiddleware;

it('can apply middleware on each method of a controller', function (): void {
    $this->routeRegistrar->registerClass(MiddlewareTestController::class);

    $this
        ->expectRegisteredRoutesCount(2)
        ->expectRouteRegistered(
            MiddlewareTestController::class,
            controllerMethod: 'singleMiddleware',
            uri: 'single-middleware',
            middleware: [TestMiddleware::class],
        )
        ->expectRouteRegistered(
            MiddlewareTestController::class,
            controllerMethod: 'multipleMiddleware',
            uri: 'multiple-middleware',
            middleware: [TestMiddleware::class, OtherTestMiddleware::class],
        );
});
