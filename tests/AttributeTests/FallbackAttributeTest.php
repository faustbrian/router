<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Tests\TestClasses\Controllers\FallbackTestController;

it('can register a route as fallback', function (): void {
    $this->routeRegistrar->registerClass(FallbackTestController::class);

    $this
        ->expectRegisteredRoutesCount(1)
        ->expectRouteRegistered(
            controller: FallbackTestController::class,
            controllerMethod: 'myFallbackMethod',
            httpMethods: 'get',
            uri: 'my-fallback-method',
            isFallback: true,
        );
});
