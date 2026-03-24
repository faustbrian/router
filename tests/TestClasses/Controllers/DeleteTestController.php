<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\TestClasses\Controllers;

use Cline\Router\Attributes\Delete;

/**
 * @author Brian Faust <brian@cline.sh>
 */
final class DeleteTestController
{
    #[Delete('my-delete-method')]
    public function myDeleteMethod(): void {}
}
