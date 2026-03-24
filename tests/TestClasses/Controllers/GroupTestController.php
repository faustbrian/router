<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\TestClasses\Controllers;

use Cline\Router\Attributes\Get;
use Cline\Router\Attributes\Group;
use Cline\Router\Attributes\Post;

/**
 * @author Brian Faust <brian@cline.sh>
 */
#[Group(prefix: 'my-prefix', domain: 'my-subdomain.localhost')]
#[Group(prefix: 'my-second-prefix', domain: 'my-second-subdomain.localhost')]
final class GroupTestController
{
    #[Get('my-get-method')]
    public function myGetMethod(): void {}

    #[Post('my-post-method')]
    public function myPostMethod(): void {}
}
