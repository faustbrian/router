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
 * Declares an explicit Laravel route group for an attributed controller.
 *
 * Repeat this attribute to register the same controller multiple times with
 * different prefixes, domains, names, or where constraints.
 *
 * @author Brian Faust <brian@cline.sh>
 * @psalm-immutable
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
final readonly class Group implements RouteAttributeInterface
{
    /**
     * Create a route group definition attached to a controller class.
     *
     * @param null|array<string, string> $where
     */
    public function __construct(
        public ?string $prefix = null,
        public ?string $domain = null,
        public ?string $as = null,
        public ?array $where = [],
    ) {}
}
