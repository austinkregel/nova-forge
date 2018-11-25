<?php

namespace Kregel\NovaForge\Http\Middleware;

use Kregel\NovaForge\NovaForge;

class Authorize
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response
     */
    public function handle($request, $next)
    {
        return resolve(NovaForge::class)->authorize($request) ? $next($request) : abort(403);
    }
}
