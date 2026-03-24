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
 * Shortcut attribute for registering a `GET` route.
 *
 * Use this for idempotent read endpoints while still benefiting from the
 * package's class-level grouping, defaults, and constraint merging behavior.
 *
 * @author Brian Faust <brian@cline.sh>
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
final class Get extends Route
{
    /**
     * Create a `GET` route declaration.
     *
     * @param array<int|string, string>|string $middleware
     * @param array<int|string, string>|string $withoutMiddleware
     */
    public function __construct(
        string $uri,
        ?string $name = null,
        array|string $middleware = [],
        array|string $withoutMiddleware = [],
    ) {
        parent::__construct(
            methods: ['get'],
            uri: $uri,
            name: $name,
            middleware: $middleware,
            withoutMiddleware: $withoutMiddleware,
        );
    }
}
