<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Rosalana\Accounts\Facades\RosalanaAuth;

class AuthenticatedSessionController extends Controller
{

    /**
     * Handle and incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        return response()->noContent();
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        RosalanaAuth::logout();

        return response()->noContent();
    }
}