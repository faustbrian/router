<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Tests\TestClasses\Controllers\BindingScoping1TestController;
use Tests\TestClasses\Controllers\BindingScoping2TestController;
use Tests\TestClasses\Controllers\BindingScoping3TestController;
use Tests\TestClasses\Controllers\BindingScoping4TestController;

it('can enable binding scoping on each method of a controller', function (): void {
    $this->routeRegistrar->registerClass(BindingScoping2TestController::class);

    $this
        ->expectRegisteredRoutesCount(3)
        ->expectRouteRegistered(
            BindingScoping2TestController::class,
            controllerMethod: 'explicitlyEnabledScopedBinding',
            uri: 'explicitly-enabled/{scoped}/{binding}',
            enforcesScopedBindings: true,
            preventsScopedBindings: false,
        )
        ->expectRouteRegistered(
            BindingScoping2TestController::class,
            controllerMethod: 'explicitlyDisabledScopedBinding',
            uri: 'explicitly-disabled/{scoped}/{binding}',
            enforcesScopedBindings: false,
            preventsScopedBindings: true,
        )
        ->expectRouteRegistered(
            BindingScoping2TestController::class,
            controllerMethod: 'implicitlyDisabledScopedBinding',
            uri: 'implicitly-disabled/{scoped}/{binding}',
            enforcesScopedBindings: false,
            preventsScopedBindings: false,
        );
});

it('can disable binding scoping on individual methods of a controller', function (): void {
    $this->routeRegistrar->registerClass(BindingScoping1TestController::class);

    $this
        ->expectRegisteredRoutesCount(2)
        ->expectRouteRegistered(
            BindingScoping1TestController::class,
            controllerMethod: 'implicitlyEnabledScopedBinding',
            uri: 'implicit/{scoped}/{binding}',
            enforcesScopedBindings: true,
            preventsScopedBindings: false,
        )
        ->expectRouteRegistered(
            BindingScoping1TestController::class,
            controllerMethod: 'explicitlyDisabledScopedBinding',
            uri: 'explicitly-disabled/{scoped}/{binding}',
            enforcesScopedBindings: false,
            preventsScopedBindings: true,
        );
});

it('can enable binding scoping on individual methods of a controller', function (): void {
    $this->routeRegistrar->registerClass(BindingScoping3TestController::class);

    $this
        ->expectRegisteredRoutesCount(2)
        ->expectRouteRegistered(
            BindingScoping3TestController::class,
            controllerMethod: 'explicitlyDisabledByClassScopedBinding',
            uri: 'explicitly-disabled-by-class/{scoped}/{binding}',
            enforcesScopedBindings: false,
            preventsScopedBindings: true,
        )
        ->expectRouteRegistered(
            BindingScoping3TestController::class,
            controllerMethod: 'explicitlyEnabledOverridingClassScopedBinding',
            uri: 'explicitly-enabled-overriding-class/{scoped}/{binding}',
            enforcesScopedBindings: true,
            preventsScopedBindings: false,
        );
});

it('respects default scope bindings setting from config', function (): void {
    config()->set('router.scope-bindings', true);

    $this->routeRegistrar->registerClass(BindingScoping4TestController::class);

    $this
        ->expectRegisteredRoutesCount(2)
        ->expectRouteRegistered(
            BindingScoping4TestController::class,
            controllerMethod: 'index',
            uri: 'default-scoping',
            enforcesScopedBindings: true,
            preventsScopedBindings: false,
        )
        ->expectRouteRegistered(
            BindingScoping4TestController::class,
            controllerMethod: 'store',
            httpMethods: 'post',
            uri: 'explicitly-disabled-scoping',
            enforcesScopedBindings: false,
            preventsScopedBindings: true,
        );
});
