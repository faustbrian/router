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
 * Specialized resource attribute that opts into Laravel's `apiResource()`
 * registration semantics.
 *
 * This removes browser-oriented actions such as `create` and `edit` while still
 * exposing the same customization surface as the base `Resource` attribute.
 *
 * @author Brian Faust <brian@cline.sh>
 */
#[Attribute(Attribute::TARGET_CLASS)]
final class ApiResource extends Resource
{
    /**
     * Create an API resource declaration for a controller class.
     *
     * @param null|array<int|string, string>|string $except
     * @param null|array<int|string, string>|string $only
     * @param null|array<int|string, string>|string $names
     * @param null|array<int|string, string>|string $parameters
     */
    public function __construct(
        public string $resource,
        public array|string|null $except = null,
        public array|string|null $only = null,
        public array|string|null $names = null,
        public array|string|null $parameters = null,
        public ?bool $shallow = null,
    ) {
        parent::__construct(
            resource: $resource,
            apiResource: true,
            except: $except,
            only: $only,
            names: $names,
            parameters: $parameters,
            shallow: $shallow,
        );
    }
}
