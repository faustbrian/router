<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cline\Router\Attributes;

use Attribute;
use Illuminate\Routing\Router;

/**
 * Registers a controller method for every verb Laravel exposes.
 *
 * This is the attribute equivalent of `Route::any()` and is useful when a
 * single action intentionally handles multiple HTTP methods without duplicating
 * multiple verb-specific attributes.
 *
 * @author Brian Faust <brian@cline.sh>
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
final class Any extends Route
{
    /**
     * Create an all-verbs route declaration.
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
            methods: Router::$verbs,
            uri: $uri,
            name: $name,
            middleware: $middleware,
            withoutMiddleware: $withoutMiddleware,
        );
    }
}
