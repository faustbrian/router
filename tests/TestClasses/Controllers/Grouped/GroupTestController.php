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

/**
 * @author Brian Faust <brian@cline.sh>
 */
final class GroupTestController
{
    #[Get('my-get-method', middleware: ['test'])]
    public function myGetMethod(): void {}

    #[Post('my-post-method')]
    public function myPostMethod(): void {}
}
