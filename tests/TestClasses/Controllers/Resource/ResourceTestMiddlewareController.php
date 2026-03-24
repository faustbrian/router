<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\TestClasses\Controllers\Resource;

use Cline\Router\Attributes\Middleware;
use Cline\Router\Attributes\Resource;
use Tests\TestClasses\Middleware\OtherTestMiddleware;
use Tests\TestClasses\Middleware\TestMiddleware;

/**
 * @author Brian Faust <brian@cline.sh>
 */
#[Middleware([TestMiddleware::class, OtherTestMiddleware::class])]
#[Resource('posts', only: ['index', 'show'])]
final class ResourceTestMiddlewareController
{
    public function index(): void {}

    public function show(): void {}
}
