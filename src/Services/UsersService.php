<?php

namespace Rosalana\Accounts\Services;

use Illuminate\Foundation\Auth\User;
use Rosalana\Accounts\Facades\Accounts;
use Rosalana\Core\Exceptions\Http\BasecampUnauthorizedException;
use Rosalana\Core\Facades\Basecamp;

class UsersService
{
    public function sync(array $basecampUser): User
    {
        [$model, $identifier] = $this->resolveModel();

        if (!isset($basecampUser['id'])) {
            throw new \InvalidArgumentException("Rosalana Accounts: Basecamp user data missing 'id'.");
        }

        return $model::updateOrCreate(
            [$identifier => $basecampUser['id']],
            [
                $identifier => $basecampUser['id'],
                'name' => $basecampUser['name'] ?? $basecampUser['email'],
                'email' => $basecampUser['email'],
            ]
        );
    }

    public function current(): User
    {
        [$model, $identifier] = $this->resolveModel();

        try {
            $response = Basecamp::auth()->current();
        } catch (BasecampUnauthorizedException $e) {
            Accounts::session()->terminate();
            throw $e;
        }

        if (empty($response->json('data.id'))) {
            throw new \InvalidArgumentException("Rosalana Accounts: Basecamp user data missing 'id'.");
        }

        return $model::where($identifier, $response->json('data.id'))->first() ?? $this->sync($response->json('data'));
    }

    public function find(string $id): ?User
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

        return $model::where($identifier, $response->json('data.id'))->first() ?? null;
    }

    public function toLocal(array|null $basecampUser): ?User
    {
        if (is_null($basecampUser)) return null;
        
        [$model, $identifier] = $this->resolveModel();

        if (empty($basecampUser['id'])) {
            throw new \InvalidArgumentException("Rosalana Accounts: Basecamp user data missing 'id'.");
        }

        return $model::where($identifier, $basecampUser['id'])->first() ?? null;
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
