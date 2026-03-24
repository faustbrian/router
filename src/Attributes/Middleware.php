<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cline\Router\Attributes;

use Attribute;
use Illuminate\Support\Arr;

/**
 * Declares middleware that should wrap every route generated for a controller.
 *
 * Values are normalized to a list during construction so the registrar can
 * merge them with global and method-level middleware without further checks.
 *
 * @author Brian Faust <brian@cline.sh>
 */
#[Attribute(Attribute::TARGET_CLASS)]
final class Middleware implements RouteAttributeInterface
{
    /**
     * Normalized middleware stack for the attributed controller.
     *
     * @var list<string>
     */
    public array $middleware = [];

    /**
     * Create a class-level middleware declaration.
     *
     * @param array<int|string, string>|string $middleware
     */
    public function __construct(string|array $middleware = [])
    {
        /** @var list<string> $wrappedMiddleware */
        $wrappedMiddleware = Arr::wrap($middleware);

        $this->middleware = $wrappedMiddleware;
    }
}
