<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Tests\TestClasses\Controllers\RouteRegistrar\RegistrarTestFirstController;
use Tests\TestClasses\Controllers\RouteRegistrar\RegistrarTestSecondController;
use Tests\TestClasses\Controllers\RouteRegistrar\SubDirectory\RegistrarTestControllerInSubDirectory;
use Tests\TestClasses\Middleware\AnotherTestMiddleware;
use ThirdParty\Http\Controllers\ThirdPartyController;

it('can register a single file', function (): void {
    $this
        ->routeRegistrar
        ->registerFile($this->getTestPath('TestClasses/Controllers/RouteRegistrar/RegistrarTestFirstController.php'));

    $this->expectRegisteredRoutesCount(1);

    $this->expectRouteRegistered(
        RegistrarTestFirstController::class,
        uri: 'first-method',
    );
});

it('can apply config middlewares to all routes', function (): void {
    $this
        ->routeRegistrar
        ->registerFile($this->getTestPath('TestClasses/Controllers/RouteRegistrar/RegistrarTestFirstController.php'));

    $this->expectRegisteredRoutesCount(1);

    $this->expectRouteRegistered(
        RegistrarTestFirstController::class,
        uri: 'first-method',
        middleware: [AnotherTestMiddleware::class],
    );
});

it('can register a whole directory', function (): void {
    $this
        ->routeRegistrar
        ->registerDirectory($this->getTestPath('TestClasses/Controllers/RouteRegistrar'));

    $this->expectRegisteredRoutesCount(3);

    $this->expectRouteRegistered(
        RegistrarTestFirstController::class,
        uri: 'first-method',
    );

    $this->expectRouteRegistered(
        RegistrarTestSecondController::class,
        uri: 'second-method',
    );

    $this->expectRouteRegistered(
        RegistrarTestControllerInSubDirectory::class,
        uri: 'in-sub-directory',
    );
});

it('can register a directory with defined namespace', function (): void {
    require_once __DIR__.'/ThirdPartyTestClasses/Controllers/ThirdPartyController.php';
    $this->routeRegistrar
        ->useBasePath($this->getTestPath('ThirdPartyTestClasses'.\DIRECTORY_SEPARATOR.'Controllers'))
        ->useRootNamespace('ThirdParty\Http\Controllers\\')
        ->registerDirectory($this->getTestPath('ThirdPartyTestClasses'.\DIRECTORY_SEPARATOR.'Controllers'));

    $this->expectRegisteredRoutesCount(1);
    $this->expectRouteRegistered(
        ThirdPartyController::class,
        uri: 'third-party',
        controllerMethod: 'thirdPartyGetMethod',
    );
});

it('can register a directory with filename pattern', function (): void {
    $this
        ->routeRegistrar
        ->registerDirectory($this->getTestPath('TestClasses/Controllers/RouteRegistrar'), ['*FirstController.php']);

    $this->expectRegisteredRoutesCount(1);

    $this->expectRouteRegistered(
        RegistrarTestFirstController::class,
        uri: 'first-method',
    );
});

it('can register a directory with filename not pattern', function (): void {
    $this
        ->routeRegistrar
        ->registerDirectory($this->getTestPath('TestClasses/Controllers/RouteRegistrar'), [], ['*FirstController.php']);

    $this->expectRegisteredRoutesCount(2);
});
