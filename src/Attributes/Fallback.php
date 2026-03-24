<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cline\Router\Attributes;

use Attribute;

/**
 * Marks a route attribute as Laravel's fallback route for its group.
 *
 * The registrar checks for this attribute after the concrete route has been
 * created and then calls `$route->fallback()` on the generated instance.
 *
 * @author Brian Faust <brian@cline.sh>
 */
#[Attribute(Attribute::TARGET_METHOD)]
final class Fallback {}
