<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\TestClasses\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * @author Brian Faust <brian@cline.sh>
 */
final class TestMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }
}
