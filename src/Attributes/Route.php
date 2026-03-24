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
use Illuminate\Support\Arr;

use function array_map;
use function in_array;
use function mb_strtoupper;

/**
 * Base attribute describing an explicit HTTP route on a controller method.
 *
 * Concrete HTTP verb attributes extend this class to inherit URI, name, and
 * middleware handling while only changing the verb list. The constructor also
 * normalizes HTTP verbs against Laravel's canonical router verb list so mixed
 * casing in attribute declarations still produces predictable registrations.
 *
 * @author Brian Faust <brian@cline.sh>
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Route implements RouteAttributeInterface
{
    /**
     * Normalized HTTP methods passed to Laravel's router.
     *
     * @var list<string>
     */
    public array $methods;

    /**
     * Middleware added directly to the generated route.
     *
     * @var list<string>
     */
    public array $middleware;

    /**
     * Middleware exclusions applied after the route is created.
     *
     * @var list<string>
     */
    public array $withoutMiddleware;

    /**
     * Create a route definition attribute.
     *
     * Unknown verbs are left as-is so advanced router extensions can still opt
     * into custom methods while standard Laravel verbs are uppercased.
     *
     * @param array<int|string, string>|string $methods
     * @param array<int|string, string>|string $middleware
     * @param array<int|string, string>|string $withoutMiddleware
     */
    public function __construct(
        array|string $methods,
        public string $uri,
        public ?string $name = null,
        array|string $middleware = [],
        array|string $withoutMiddleware = [],
    ) {
        /** @var list<string> $wrappedMethods */
        $wrappedMethods = Arr::wrap($methods);

        $this->methods = array_map(
            static fn (string $verb): string => in_array(
                $upperVerb = mb_strtoupper($verb),
                Router::$verbs,
                true,
            )
            ? $upperVerb
            : $verb,
            $wrappedMethods,
        );

        /** @var list<string> $wrappedMiddleware */
        $wrappedMiddleware = Arr::wrap($middleware);

        /** @var list<string> $wrappedWithoutMiddleware */
        $wrappedWithoutMiddleware = Arr::wrap($withoutMiddleware);

        $this->middleware = $wrappedMiddleware;
        $this->withoutMiddleware = $wrappedWithoutMiddleware;
    }
}
