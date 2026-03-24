<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Tests\TestClasses\Controllers\DefaultsTestController;

it('can apply defaults on each method of a controller', function (): void {
    $this->routeRegistrar->registerClass(DefaultsTestController::class);

    $this
        ->expectRegisteredRoutesCount(4)
        ->expectRouteRegistered(
            DefaultsTestController::class,
            controllerMethod: 'myGetMethod',
            uri: 'my-get-method/{param?}',
            defaults: ['param' => 'controller-default'],
        )->expectRouteRegistered(
            DefaultsTestController::class,
            controllerMethod: 'myPostMethod',
            httpMethods: 'post',
            uri: 'my-post-method/{param?}/{param2?}',
            defaults: ['param' => 'controller-default', 'param2' => 'method-default'],
        );
});

it('can apply more than one default on a method', function (): void {
    $this->routeRegistrar->registerClass(DefaultsTestController::class);

    $this
        ->expectRegisteredRoutesCount(4)
        ->expectRouteRegistered(
            DefaultsTestController::class,
            controllerMethod: 'myDefaultMethod',
            uri: 'my-default-method/{param?}/{param2?}/{param3?}',
            defaults: [
                'param' => 'controller-default',
                'param2' => 'method-default-first',
                'param3' => 'method-default-second',
            ],
        );
});

it('can override controller defaults', function (): void {
    $this->routeRegistrar->registerClass(DefaultsTestController::class);

    $this
        ->expectRegisteredRoutesCount(4)
        ->expectRouteRegistered(
            DefaultsTestController::class,
            controllerMethod: 'myOverrideMethod',
            uri: 'my-override-method/{param?}',
            defaults: ['param' => 'method-default'],
        );
});
