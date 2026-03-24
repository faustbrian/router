<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\TestClasses\Controllers\Resource;

use Cline\Router\Attributes\Resource;

/**
 * @author Brian Faust <brian@cline.sh>
 */
#[Resource('posts', only: ['index', 'show'], names: 'api.v1.posts')]
final class ResourceTestNamesStringController
{
    public function index(): void {}

    public function show(): void {}
}
