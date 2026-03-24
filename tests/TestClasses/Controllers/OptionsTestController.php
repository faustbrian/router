<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\TestClasses\Controllers;

use Cline\Router\Attributes\Options;

/**
 * @author Brian Faust <brian@cline.sh>
 */
final class OptionsTestController
{
    #[Options('my-options-method')]
    public function myOptionsMethod(): void {}
}
