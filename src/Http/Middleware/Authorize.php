<?php

namespace Sideapps\NovaTranslation\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Sideapps\NovaTranslation\NovaTranslation;

class Authorize
{
    /**
     * Handle the incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return Response
     */
    public function handle($request, $next)
    {
        return resolve(NovaTranslation::class)->authorize($request) ? $next($request) : abort(403);
    }
}
