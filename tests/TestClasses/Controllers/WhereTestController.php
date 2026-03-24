<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\TestClasses\Controllers;

use Cline\Router\Attributes\Get;
use Cline\Router\Attributes\Post;
use Cline\Router\Attributes\Where;
use Cline\Router\Attributes\WhereAlpha;
use Cline\Router\Attributes\WhereAlphaNumeric;
use Cline\Router\Attributes\WhereIn;
use Cline\Router\Attributes\WhereNumber;
use Cline\Router\Attributes\WhereUlid;
use Cline\Router\Attributes\WhereUuid;

/**
 * @author Brian Faust <brian@cline.sh>
 */
#[Where('param', '[0-9]+')]
final class WhereTestController
{
    #[Get('my-get-method/{param}')]
    public function myGetMethod(): void {}

    #[Post('my-post-method/{param}/{param2}')]
    #[Where('param2', '[a-zA-Z]+')]
    public function myPostMethod(): void {}

    #[Get('my-where-method/{param}/{param2}/{param3}')]
    #[Where('param2', '[a-zA-Z]+')]
    #[Where('param3', 'test')]
    public function myWhereMethod(): void {}

    #[Get('my-shorthands')]
    #[WhereAlpha('alpha')]
    #[WhereAlphaNumeric('alpha-numeric')]
    #[WhereIn('in', ['value1', 'value2'])]
    #[WhereNumber('number')]
    #[WhereUlid('ulid')]
    #[WhereUuid('uuid')]
    public function myShorthands(): void {}
}
