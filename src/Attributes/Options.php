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
 * Shortcut attribute for registering an `OPTIONS` route.
 *
 * This is typically used for CORS or capability-discovery endpoints that need
 * to live beside other attributed controller methods.
 *
 * @author Brian Faust <brian@cline.sh>
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
final class Options extends Route
{
    /**
     * Create an `OPTIONS` route declaration.
     *
     * @param array<int|string, string>|string $middleware
     */
    public function __construct(
        string $uri,
        ?string $name = null,
        array|string $middleware = [],
    ) {
        parent::__construct(
            methods: ['options'],
            uri: $uri,
            name: $name,
            middleware: $middleware,
        );
    }
}
