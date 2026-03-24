<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\TestClasses\Controllers;

use Cline\Router\Attributes\Patch;

/**
 * @author Brian Faust <brian@cline.sh>
 */
final class PatchMultipleTestController
{
    #[Patch('my-patch-method')]
    #[Patch('my-other-patch-method')]
    public function myPatchMethod(): void {}
}
