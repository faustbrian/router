<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ThirdParty\Http\Controllers;

use Cline\Router\Attributes\Get;

/**
 * @author Brian Faust <brian@cline.sh>
 */
final class ThirdPartyController
{
    #[Get('third-party')]
    public function thirdPartyGetMethod(): void {}
}
