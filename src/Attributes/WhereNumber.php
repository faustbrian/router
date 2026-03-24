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
 * Constrains a route parameter to digits only.
 *
 * Internally this is equivalent to `where($param, '[0-9]+')`.
 *
 * @author Brian Faust <brian@cline.sh>
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
final class WhereNumber extends Where
{
    /**
     * Create a numeric parameter constraint.
     */
    public function __construct(
        string $param,
    ) {
        parent::__construct($param, '[0-9]+');
    }
}
