<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\TestClasses\Controllers\RouteAttribute;

use Cline\Router\Attributes\Route;

/**
 * @author Brian Faust <brian@cline.sh>
 */
final class RouteNameTestController
{
    #[Route('get', 'my-method', name: 'test-name')]
    public function myMethod(): void {}
}
