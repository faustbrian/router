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
 * Controls Laravel's scoped implicit binding behavior for attributed routes.
 *
 * Apply this at the class or method level to opt into or out of scoped child
 * bindings without leaving the attribute-driven route definition model.
 *
 * @author Brian Faust <brian@cline.sh>
 * @psalm-immutable
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_CLASS)]
final readonly class ScopeBindings implements RouteAttributeInterface
{
    /**
     * Create a scoped-binding toggle.
     */
    public function __construct(
        public bool $scopeBindings = true,
    ) {}
}
