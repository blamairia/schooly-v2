<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * No-op middleware used by installer packages.
 * Kept as a simple pass-through to avoid BindingResolutionException when
 * middleware is referenced in the HTTP kernel but not provided by an
 * external package.
 */
class PrepareForInstaller
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }
}
