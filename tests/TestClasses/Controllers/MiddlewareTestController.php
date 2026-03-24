<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\TestClasses\Controllers;

use Cline\Router\Attributes\Middleware;
use Cline\Router\Attributes\Route;
use Tests\TestClasses\Middleware\OtherTestMiddleware;
use Tests\TestClasses\Middleware\TestMiddleware;

/**
 * @author Brian Faust <brian@cline.sh>
 */
#[Middleware(TestMiddleware::class)]
final class MiddlewareTestController
{
    #[Route('get', 'single-middleware')]
    public function singleMiddleware(): void {}

    #[Route('get', 'multiple-middleware', middleware: OtherTestMiddleware::class)]
    public function multipleMiddleware(): void {}
}
