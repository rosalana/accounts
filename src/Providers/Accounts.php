<?php

namespace Rosalana\Accounts\Providers;

use Rosalana\Core\Contracts\Package;

class Accounts implements Package
{
    public function resolvePublished(): bool
    {
        return false;
    }

    public function publish(): array
    {
        return [
            //
        ];
    }
}