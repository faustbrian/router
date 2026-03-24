<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\TestClasses\Controllers;

use Cline\Router\Attributes\Route;
use Cline\Router\Attributes\ScopeBindings;

/**
 * @author Brian Faust <brian@cline.sh>
 */
final class BindingScoping2TestController
{
    #[Route('get', 'explicitly-enabled/{scoped}/{binding}')]
    #[ScopeBindings()]
    public function explicitlyEnabledScopedBinding(): void {}

    #[Route('get', 'explicitly-disabled/{scoped}/{binding}')]
    #[ScopeBindings(false)]
    public function explicitlyDisabledScopedBinding(): void {}

    #[Route('get', 'implicitly-disabled/{scoped}/{binding}')]
    public function implicitlyDisabledScopedBinding(): void {}
}
