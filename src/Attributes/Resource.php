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
 * Declares that a controller should also register Laravel resource routes.
 *
 * The attribute mirrors the knobs exposed by Laravel's pending resource
 * registration API so a controller can describe resource generation alongside
 * its method-level route attributes.
 *
 * @author Brian Faust <brian@cline.sh>
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Resource implements RouteAttributeInterface
{
    /**
     * Create a resource route declaration for a controller class.
     *
     * @param null|array<int|string, string>|string $except
     * @param null|array<int|string, string>|string $only
     * @param null|array<int|string, string>|string $names
     * @param null|array<int|string, string>|string $parameters
     */
    public function __construct(
        public string $resource,
        public bool $apiResource = false,
        public array|string|null $except = null,
        public array|string|null $only = null,
        public array|string|null $names = null,
        public array|string|null $parameters = null,
        public ?bool $shallow = null,
    ) {}
}
