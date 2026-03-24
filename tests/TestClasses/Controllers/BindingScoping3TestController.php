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
#[ScopeBindings(false)]
final class BindingScoping3TestController
{
    #[Route('get', 'explicitly-disabled-by-class/{scoped}/{binding}')]
    public function explicitlyDisabledByClassScopedBinding(): void {}

    #[Route('get', 'explicitly-enabled-overriding-class/{scoped}/{binding}')]
    #[ScopeBindings(true)]
    public function explicitlyEnabledOverridingClassScopedBinding(): void {}
}
