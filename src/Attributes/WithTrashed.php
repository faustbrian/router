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
 * Controls whether implicit model binding should include soft-deleted models.
 *
 * Class-level usage establishes the default for every route on the controller,
 * while a method-level attribute can override that default for a specific route.
 *
 * @author Brian Faust <brian@cline.sh>
 * @psalm-immutable
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_CLASS)]
final readonly class WithTrashed implements RouteAttributeInterface
{
    /**
     * Create a soft-delete binding toggle.
     */
    public function __construct(
        public bool $withTrashed = true,
    ) {}
}
