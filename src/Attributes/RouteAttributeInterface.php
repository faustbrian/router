<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cline\Router\Attributes;

/**
 * Marker interface for attributes the package recognizes during route
 * registration.
 *
 * Using a shared interface lets the reflection helpers validate that an
 * arbitrary attribute instance belongs to the router package before the
 * registrar applies package-specific behavior.
 *
 * @author Brian Faust <brian@cline.sh>
 */
interface RouteAttributeInterface {}
