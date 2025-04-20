<?php

namespace Rosalana\Accounts\Services\Basecamp;

use Rosalana\Accounts\Facades\Accounts;
use Rosalana\Core\Services\Basecamp\Service;

class UsersService extends Service
{
    public function find(string $id)
    {
        return $this->manager
            ->withAuth()
            ->get("users/{$id}");
    }
}
