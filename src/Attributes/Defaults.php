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
 * Declares a Laravel route default value.
 *
 * Defaults may be attached to a controller class or an individual method.
 * Method-level defaults override class-level defaults when they use the same
 * key because the registrar merges class values first.
 *
 * @author Brian Faust <brian@cline.sh>
 * @psalm-immutable
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
final readonly class Defaults
{
    /**
     * Create a single default key/value pair.
     */
    public function __construct(
        public string $key,
        public string $value,
    ) {}
}
