<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cline\Router\Attributes;

use Attribute;

use function implode;

/**
 * Constrains a route parameter to one of a fixed set of literal values.
 *
 * The provided values are joined into a pipe-delimited regex fragment suitable
 * for Laravel's underlying `where` constraint handling.
 *
 * @author Brian Faust <brian@cline.sh>
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
final class WhereIn extends Where
{
    /**
     * Create an enum-like parameter constraint from a list of allowed values.
     *
     * @param list<string> $constraint
     */
    public function __construct(
        string $param,
        array $constraint,
    ) {
        parent::__construct($param, implode('|', $constraint));
    }
}
