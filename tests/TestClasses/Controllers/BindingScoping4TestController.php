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
use Cline\Router\Attributes\ScopeBindings;

/**
 * @author Brian Faust <brian@cline.sh>
 */
final class BindingScoping4TestController
{
    #[Get('default-scoping')]
    public function index(): void {}

    #[ScopeBindings(false)]
    #[Post('explicitly-disabled-scoping')]
    public function store(): void {}
}
