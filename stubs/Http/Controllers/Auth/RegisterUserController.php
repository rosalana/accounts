<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Controllers\Controller;

class RegisterUserController extends Controller
{
    public function store(RegisterRequest $request)
    {
        $request->register();

        return response()->noContent();
    }
}