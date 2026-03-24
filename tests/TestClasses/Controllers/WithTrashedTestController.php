<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\TestClasses\Controllers;

use Cline\Router\Attributes\Get;
use Cline\Router\Attributes\WithTrashed;

/**
 * @author Brian Faust <brian@cline.sh>
 */
#[WithTrashed()]
final class WithTrashedTestController
{
    #[Get('with-trashed-test-method/{param}')]
    #[WithTrashed()]
    public function withTrashedRoute(): void {}

    #[Get('with-trashed-test-method-2/{param}')]
    #[WithTrashed(false)]
    public function withoutTrashedRoute(): void {}

    #[Get('with-trashed-test-method-3/{param}')]
    public function noWithTrashedAttributeRoute(): void {}
}
