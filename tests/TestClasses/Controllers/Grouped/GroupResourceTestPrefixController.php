<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\TestClasses\Controllers\Grouped;

use Cline\Router\Attributes\Prefix;
use Cline\Router\Attributes\Resource;

/**
 * @author Brian Faust <brian@cline.sh>
 */
#[Prefix('/api/v1/my-prefix/etc')]
#[Resource('posts', only: ['index', 'show'], names: 'prefixed_posts')]
final class GroupResourceTestPrefixController
{
    public function index(): void {}

    public function show(): void {}
}
