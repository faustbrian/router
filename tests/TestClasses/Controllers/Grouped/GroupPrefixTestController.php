<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\TestClasses\Controllers\Grouped;

use Cline\Router\Attributes\Get;
use Cline\Router\Attributes\Post;
use Cline\Router\Attributes\Prefix;

/**
 * @author Brian Faust <brian@cline.sh>
 */
#[Prefix('my-prefix')]
final class GroupPrefixTestController
{
    #[Get('my-prefix-get-method')]
    public function myGetMethod(): void {}

    #[Post('my-prefix-post-method')]
    public function myPostMethod(): void {}
}
