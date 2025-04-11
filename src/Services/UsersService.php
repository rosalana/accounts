<?php

namespace Rosalana\Accounts\Services;

use Illuminate\Contracts\Auth\Authenticatable;

class UsersService
{
    public function sync(array $basecampUser): Authenticatable
    {
        $model = config('rosalana.accounts.model');
        $identifier = config('rosalana.accounts.identifier');

        if (!class_exists($model)) {
            throw new \RuntimeException("Rosalana Accounts: Model [$model] not found.");
        }

        if (!isset($basecampUser['id'])) {
            throw new \InvalidArgumentException("Rosalana Accounts: Basecamp user data missing 'id'.");
        }

        return $model::updateOrCreate(
            [$identifier => $basecampUser['id']],
            [
                'name' => $basecampUser['name'] ?? $basecampUser['email'],
                'email' => $basecampUser['email'],
            ]
        );
    }
}
