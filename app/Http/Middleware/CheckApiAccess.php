<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckApiAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $uri = $request->route()->uri();
        $method = $request->method();

        $hasAccess = $user->apiEndpoints()
            ->where('uri', $uri)
            ->where('method', strtoupper($method))
            ->exists();

        if (!$hasAccess) {
            return response()->json(['message' =>
            'Anda tidak memiliki akses ke endpoint ini!'], 403);
        }

        return $next($request);
    }
}
