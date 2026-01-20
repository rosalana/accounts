<?php

namespace Rosalana\Accounts\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SessionController
{
    public function index(Request $request): JsonResponse
    {
        return error('Not implemented', 501)();
    }

    public function terminate(Request $request, string $id): JsonResponse
    {
        return error('Not implemented', 501)();
    }
}
