<?php

namespace Rosalana\Accounts\Services;

use Illuminate\Contracts\Auth\Authenticatable;
use Rosalana\Accounts\Facades\Accounts;
use Rosalana\Core\Exceptions\BasecampUnauthorizedException;
use Rosalana\Core\Facades\Basecamp;

class UsersService
{
    public function sync(array $basecampUser): Authenticatable
    {
        [$model, $identifier] = $this->resolveModel();

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

    public function current(): Authenticatable
    {
        [$model, $identifier] = $this->resolveModel();

        try {
            $response = Basecamp::users()->current();
        } catch (BasecampUnauthorizedException $e) {
            Accounts::session()->terminate();
            throw $e;
        }

        if (empty($response->json('data.id'))) {
            throw new \InvalidArgumentException("Rosalana Accounts: Basecamp user data missing 'id'.");
        }

        return $model::where($identifier, $response->json('data.id'))->first() ?? $this->sync($response->json('data'));
    }

    public function find(string $id): Authenticatable
    {
        [$model, $identifier] = $this->resolveModel();

        try {
            $response = Basecamp::users()->find($id);
        } catch (BasecampUnauthorizedException $e) {
            Accounts::session()->terminate();
            throw $e;
        }

        if (empty($response->json('data.id'))) {
            throw new \InvalidArgumentException("Rosalana Accounts: User ID is required.");
        }

        return $model::where($identifier, $response->json('data.id'))->first() ?? $this->sync($response->json('data'));
    }

    protected function resolveModel(): array
    {
        $model = config('rosalana.accounts.model');
        $identifier = config('rosalana.accounts.identifier');

        if (!class_exists($model)) {
            throw new \RuntimeException("Rosalana Accounts: Model [$model] not found.");
        }

        return [$model, $identifier];
    }
}
