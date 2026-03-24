<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\TestClasses\Controllers;

use Cline\Router\Attributes\Domain;
use Cline\Router\Attributes\Get;
use Cline\Router\Attributes\Post;

/**
 * @author Brian Faust <brian@cline.sh>
 */
#[Domain('my-subdomain.localhost')]
final class DomainTestController
{
    #[Get('my-get-method')]
    public function myGetMethod(): void {}

    #[Post('my-post-method')]
    public function myPostMethod(): void {}
}
