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
 * Constrains a route parameter to a canonical UUID string.
 *
 * The regex accepts mixed-case hexadecimal UUIDs with hyphen separators.
 *
 * @author Brian Faust <brian@cline.sh>
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
final class WhereUuid extends Where
{
    /**
     * Create a UUID parameter constraint.
     */
    public function __construct(
        string $param,
    ) {
        parent::__construct($param, '[\da-fA-F]{8}-[\da-fA-F]{4}-[\da-fA-F]{4}-[\da-fA-F]{4}-[\da-fA-F]{12}');
    }
}
