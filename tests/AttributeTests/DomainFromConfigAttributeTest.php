<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Tests\TestClasses\Controllers\DomainFromConfigTestController;

it('can apply a domain on the url of every method', function (): void {
    config()->set('domains.test', 'config.localhost');
    $this->routeRegistrar->registerClass(DomainFromConfigTestController::class);

    $this
        ->expectRegisteredRoutesCount(2)
        ->expectRouteRegistered(
            DomainFromConfigTestController::class,
            controllerMethod: 'myGetMethod',
            uri: 'my-get-method',
            domain: 'config.localhost',
        )
        ->expectRouteRegistered(
            DomainFromConfigTestController::class,
            controllerMethod: 'myPostMethod',
            httpMethods: 'post',
            uri: 'my-post-method',
            domain: 'config.localhost',
        );
});
