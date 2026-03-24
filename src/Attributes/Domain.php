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
 * Assigns a fixed route domain to every route generated for a controller.
 *
 * This is primarily used for subdomain or tenant routing where the controller
 * should only be registered under a specific host.
 *
 * @author Brian Faust <brian@cline.sh>
 * @psalm-immutable
 */
#[Attribute(Attribute::TARGET_CLASS)]
final readonly class Domain implements RouteAttributeInterface
{
    /**
     * Create a fixed-domain route declaration.
     */
    public function __construct(
        public string $domain,
    ) {}
}
