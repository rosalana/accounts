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
        try {
            $response = Basecamp::auth()->current();
        } catch (BasecampUnauthorizedException $e) {
            Accounts::session()->terminate();
            throw $e;
        }

        if (empty($response->json('data.id'))) {
            throw new \InvalidArgumentException("Rosalana Accounts: Basecamp user data missing 'id'.");
        }

        return $this->toLocal($response->json('data.id'));
    }

    public function find(string $remote_id): ?User
    {
        try {
            $response = Basecamp::users()->find($remote_id);
        } catch (BasecampUnauthorizedException $e) {
            Accounts::session()->terminate();
            throw $e;
        }

        if (empty($response->json('data.id'))) {
            throw new \InvalidArgumentException("Rosalana Accounts: User ID is required.");
        }

        return $this->toLocal($response->json('data.id'));
    }

    public function toLocal(?string $remote_id): ?User
    {
        if (is_null($remote_id)) return null;

        [$model, $identifier] = $this->resolveModel();

        return $model::where($identifier, $remote_id)->first() ?? null;
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
