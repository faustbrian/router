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
 * Shortcut attribute for registering a `PATCH` route.
 *
 * This is intended for partial-update endpoints where the semantics differ from
 * a full resource replacement handled by `PUT`.
 *
 * @author Brian Faust <brian@cline.sh>
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
final class Patch extends Route
{
    /**
     * Create a `PATCH` route declaration.
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
            methods: ['patch'],
            uri: $uri,
            name: $name,
            middleware: $middleware,
            withoutMiddleware: $withoutMiddleware,
        );
    }
}
