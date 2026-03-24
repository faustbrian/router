<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\TestClasses\Controllers;

use Cline\Router\Attributes\Any;

/**
 * @author Brian Faust <brian@cline.sh>
 */
final class AnyTestController
{
    #[Any('my-any-method')]
    public function myAnyMethod(): void {}
}
