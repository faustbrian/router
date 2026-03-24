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
 * Resolves a controller domain from configuration instead of hard-coding it in
 * the attribute.
 *
 * This is useful when the package consumer needs environment-specific hostnames
 * but still wants route declarations to live with the controller.
 *
 * @author Brian Faust <brian@cline.sh>
 * @psalm-immutable
 */
#[Attribute(Attribute::TARGET_CLASS)]
final readonly class DomainFromConfig implements RouteAttributeInterface
{
    /**
     * Create a config-backed domain declaration.
     *
     * The provided string is a config key, not the literal host name.
     */
    public function __construct(
        public string $domain,
    ) {}
}
