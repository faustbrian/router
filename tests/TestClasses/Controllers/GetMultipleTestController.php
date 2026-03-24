<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\TestClasses\Controllers;

use Cline\Router\Attributes\Get;

/**
 * @author Brian Faust <brian@cline.sh>
 */
final class GetMultipleTestController
{
    #[Get('my-get-method')]
    #[Get('my-other-get-method')]
    public function myGetMethod(): void {}
}
