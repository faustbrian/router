<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\TestClasses\Controllers;

use Cline\Router\Attributes\Defaults;
use Cline\Router\Attributes\Get;
use Cline\Router\Attributes\Post;

/**
 * @author Brian Faust <brian@cline.sh>
 */
#[Defaults('param', 'controller-default')]
final class DefaultsTestController
{
    #[Get('my-get-method/{param?}')]
    public function myGetMethod(): void {}

    #[Post('my-post-method/{param?}/{param2?}')]
    #[Defaults('param2', 'method-default')]
    public function myPostMethod(): void {}

    #[Get('my-default-method/{param?}/{param2?}/{param3?}')]
    #[Defaults('param2', 'method-default-first')]
    #[Defaults('param3', 'method-default-second')]
    public function myDefaultMethod(): void {}

    #[Get('my-override-method/{param?}')]
    #[Defaults('param', 'method-default')]
    public function myOverrideMethod(): void {}
}
