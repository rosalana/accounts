<?php

namespace Rosalana\Accounts\Http\Middleware;

use Illuminate\Http\Request;
use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Rosalana\Accounts\Facades\RosalanaAuth;
use Rosalana\Accounts\Services\AuthService;
use Rosalana\Accounts\Services\RosalanaSession;
use Symfony\Component\HttpFoundation\Response;

class CheckRosalanaTokenValidation
{
    /**
     * Handle an incoming request.
     * 
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = RosalanaSession::get();

        if (!$token) {
            // return unouthorized();
        }

        try {
            $decode = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
            $user = \App\Models\User::where('rosalana_account_id', $decode->sub)->first();

            $authService = app(AuthService::class);
            $authService->localLogin($user, $token);
        } catch (\Firebase\JWT\ExpiredException $e) {

            try {
                RosalanaAuth::refresh();
            } catch (\Rosalana\Accounts\Exceptions\RosalanaTokenRefreshException $e) {
                // return unouthorized();
            }
        }

        return $next($request);
    }
}
