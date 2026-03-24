<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Cline\Router\RouteRegistrar;
use Tests\TestCase;
use Tests\TestClasses\Middleware\AnotherTestMiddleware;

uses(TestCase::class)
    ->beforeEach(function (): void {
        $router = app()->router;

        $this->routeRegistrar = new RouteRegistrar($router)
            ->useBasePath($this->getTestPath())
            ->useMiddleware([AnotherTestMiddleware::class])
            ->useRootNamespace('Tests\\');
    })
    ->in(__DIR__);
