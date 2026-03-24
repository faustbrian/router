<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Tests\TestClasses\Controllers\Domain1TestController;
use Tests\TestClasses\Controllers\Domain2TestController;

it('registers the same URL on different domains', function (): void {
    config()->set('domains.test', 'config.localhost');
    config()->set('domains.test2', 'config2.localhost');

    $this->routeRegistrar->registerClass(Domain1TestController::class);
    $this->routeRegistrar->registerClass(Domain2TestController::class);

    $this
        ->expectRegisteredRoutesCount(2)
        ->expectRouteRegistered(
            Domain1TestController::class,
            controllerMethod: 'myGetMethod',
            uri: 'my-get-method',
            domain: 'config.localhost',
        )
        ->expectRouteRegistered(
            Domain2TestController::class,
            controllerMethod: 'myGetMethod',
            uri: 'my-get-method',
            domain: 'config2.localhost',
        );
});
