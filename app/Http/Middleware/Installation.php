<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Installation
{
    public function handle(Request $request, Closure $next)
    {
        // TEMPORARY: bypass purchase/installation check for testing
        return $next($request);
    }
}
