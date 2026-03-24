<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Tests\TestClasses\Controllers\DomainOrderTestController;
use Tests\TestClasses\Controllers\DomainTestController;

it('can apply a domain on the url of every method', function (): void {
    $this->routeRegistrar->registerClass(DomainTestController::class);

    $this
        ->expectRegisteredRoutesCount(2)
        ->expectRouteRegistered(
            DomainTestController::class,
            controllerMethod: 'myGetMethod',
            uri: 'my-get-method',
            domain: 'my-subdomain.localhost',
        )
        ->expectRouteRegistered(
            DomainTestController::class,
            controllerMethod: 'myPostMethod',
            httpMethods: 'post',
            uri: 'my-post-method',
            domain: 'my-subdomain.localhost',
        );
});

it('registers domain files before non domain files', function (): void {
    // Use registerDirectory to test file-level domain ordering
    $this->routeRegistrar->registerDirectory($this->getTestPath('TestClasses/Controllers'));
    $routes = collect($this->getRouteCollection()->getRoutes());

    // Find all domain routes and non-domain routes
    $domainRoutes = $routes->filter(fn ($route): bool => $route->getDomain() !== null);
    $nonDomainRoutes = $routes->filter(fn ($route): bool => $route->getDomain() === null);

    // Get the last index of domain routes and first index of non-domain routes
    $allRoutes = $routes->values();

    // Find the last domain route index
    $lastDomainIndex = null;

    foreach ($domainRoutes as $domainRoute) {
        $index = $allRoutes->search($domainRoute);

        if ($lastDomainIndex !== null && $index <= $lastDomainIndex) {
            continue;
        }

        $lastDomainIndex = $index;
    }

    // Find the first non-domain route index
    $firstNonDomainIndex = null;

    foreach ($nonDomainRoutes as $nonDomainRoute) {
        $index = $allRoutes->search($nonDomainRoute);

        if ($firstNonDomainIndex !== null && $index >= $firstNonDomainIndex) {
            continue;
        }

        $firstNonDomainIndex = $index;
    }

    // All domain routes should come before all non-domain routes
    $this->assertLessThan(
        $firstNonDomainIndex,
        $lastDomainIndex,
        'All domain routes should be registered before all non-domain routes',
    );
});

it('registers domain routes before other routes in domain order test controller', function (): void {
    $this->routeRegistrar->registerClass(DomainOrderTestController::class);

    $routes = $this->expectRegisteredRoutesCount(4)->getRouteCollection()->getRoutes();

    $this->assertNotNull($routes[0]->domain());
    $this->assertNotNull($routes[1]->domain());
    $this->assertNull($routes[2]->domain());
    $this->assertNull($routes[3]->domain());
});
