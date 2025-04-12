<?php

namespace Rosalana\Accounts\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Rosalana\Accounts\Facades\Accounts;

class CheckRosalanaTokenExpiration
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\JsonResponse)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $expiresAt = Accounts::token()->expiresAt();

        if ($expiresAt && $expiresAt->isPast()) {
            Accounts::refresh();
        }

        return $next($request);
    }
}