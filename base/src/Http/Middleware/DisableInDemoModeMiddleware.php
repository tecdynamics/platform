<?php

namespace Tec\Base\Http\Middleware;

use Tec\Base\Exceptions\DisabledInDemoModeException;
use Tec\Base\Facades\BaseHelper;
use Closure;
use Illuminate\Http\Request;

class DisableInDemoModeMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (BaseHelper::hasDemoModeEnabled()) {
            throw new DisabledInDemoModeException();
        }

        return $next($request);
    }
}
