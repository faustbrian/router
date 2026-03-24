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
 * Declares a raw route parameter constraint.
 *
 * This is the most flexible constraint attribute in the package and acts as the
 * base class for the convenience variants such as `WhereUuid` and `WhereUlid`.
 *
 * @author Brian Faust <brian@cline.sh>
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class Where implements WhereAttributeInterface
{
    /**
     * Create a parameter constraint declaration.
     */
    public function __construct(
        public readonly string $param,
        public readonly string $constraint,
    ) {}
}
