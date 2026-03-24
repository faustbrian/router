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
 * Constrains a route parameter to a canonical ULID string.
 *
 * The regex mirrors Laravel's expected Crockford Base32 ULID format so route
 * matching rejects invalid values before model binding runs.
 *
 * @author Brian Faust <brian@cline.sh>
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
final class WhereUlid extends Where
{
    /**
     * Create a ULID parameter constraint.
     */
    public function __construct(
        string $param,
    ) {
        parent::__construct($param, '[0-7][0-9A-HJKMNP-TV-Z]{25}');
    }
}
