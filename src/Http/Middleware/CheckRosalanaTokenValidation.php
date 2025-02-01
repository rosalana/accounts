<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Services\Auth as RosalanaAuth;
use Closure;
use Firebase\JWT\ExpiredException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Auth;

class CheckRosalanaTokenValidation
{

    /**
     * Handle an incoming request.
     * 
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = RosalanaAuth::CookieGet();

        if (!$token) {
            throw new \Illuminate\Validation\UnauthorizedException('Unauthorized');
        }

        try {
            $decode = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
            $this->logginUser($decode);
        } catch (ExpiredException $e) {
            try {
                $response = RosalanaAuth::refresh($token);
                $token = $response['data']['token'];
                RosalanaAuth::CookieCreate($token);
                $decode = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
                $this->logginUser($decode);
            } catch (\App\Exceptions\RosalanaAuthException $e) {
                $this->logoutUser();
                throw new \Illuminate\Validation\UnauthorizedException('Unauthorized');
            }
        }

        return $next($request);
    }

    private function logoutUser()
    {
        Auth::logout();
        RosalanaAuth::CookieForget();

        session()->invalidate();
        session()->regenerateToken();
    }

    private function logginUser($decode)
    {
        // $user = User::where('rosalana_account_id', $decode->sub)->first();
        // Auth::login($user);

        session()->regenerate();
    }
}
