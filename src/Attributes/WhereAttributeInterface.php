<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cline\Router\Attributes;

/**
 * Marker interface for attributes that contribute route parameter constraints.
 *
 * The registrar requests instances through this interface so general `Where`
 * constraints and convenience variants such as `WhereUuid` can participate in
 * the same resolution path.
 *
 * @author Brian Faust <brian@cline.sh>
 */
interface WhereAttributeInterface {}
