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
 * Adds a URI prefix to every route generated for a controller.
 *
 * This offers the simple class-level equivalent of placing routes inside a
 * Laravel `Route::prefix()` group.
 *
 * @author Brian Faust <brian@cline.sh>
 * @psalm-immutable
 */
#[Attribute(Attribute::TARGET_CLASS)]
final readonly class Prefix implements RouteAttributeInterface
{
    /**
     * Create a class-level URI prefix declaration.
     */
    public function __construct(
        public string $prefix,
    ) {}
}
