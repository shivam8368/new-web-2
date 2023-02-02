<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AddRtaResponseHeader
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        $response->header('Rating', 'RTA-5042-1996-1400-1577-RTA');

        return $response;
    }
}
