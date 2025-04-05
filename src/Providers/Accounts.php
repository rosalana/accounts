<?php

namespace Rosalana\Accounts\Providers;

use Rosalana\Core\Contracts\Package;
use Rosalana\Core\Support\ConfigBuilder;

class Accounts implements Package
{
    public function resolvePublished(): bool
    {
        return false;
    }

    public function publish(): array
    {
        return [
            'config' => [
                'label' => 'Publish configuration settings to rosalana.php',
                'run' => function () {
                    ConfigBuilder::new('accounts')
                        ->add('test', 'Test value')
                        ->save();
                }
            ]
        ];
    }
}