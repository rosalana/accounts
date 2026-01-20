<?php

namespace Rosalana\Accounts\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Rosalana\Accounts\Facades\Accounts;
use Rosalana\Core\Facades\App;

class UserController
{
    public function index(Request $request): JsonResponse
    {
        $model = App::config('accounts.model');

        $users = $model::paginate(15);

        return ok($users)();
    }

    public function logout(Request $request, int $id): JsonResponse
    {
        $user = Accounts::users()->toLocal((string) $id);

        if (! $user) {
            return error()->notFound("User with ID {$id} not found.")();
        }

        // Accounts::session()->terminateById($id);
    }
}
