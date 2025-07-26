<?php

namespace Rosalana\Accounts\Providers;

use Illuminate\Support\Facades\Artisan;
use Rosalana\Core\Console\InternalCommands;
use Rosalana\Core\Contracts\Package;
use Rosalana\Core\Support\Config;

class Accounts implements Package
{
    use InternalCommands;

    public function resolvePublished(): bool
    {
        return Config::exists('accounts');
    }

    public function publish(): array
    {
        return [
            'config' => [
                'label' => 'Publish configuration settings to rosalana.php',
                'run' => function () {

                    Config::new('accounts')
                        ->add('model', 'App\\Models\\User::class')
                        ->add('identifier', "'rosalana_account_id'")
                        ->comment(
                            'Describe how Basecamp account is linked to your local Eloquent model. If you wish to change the identifier, make sure to update the provided database migration as well.',
                            'Rosalana Basecamp Account Link'
                        )
                        ->save();
                }
            ],
            'migrations' => [
                'label' => 'Publish database migrations',
                'run' => function () {
                    Artisan::call('vendor:publish', [
                        '--tag' => 'rosalana-accounts-migrations',
                        '--force' => true
                    ]);
                }
            ],
            'env' => [
                'label' => 'Publish .env variables',
                'run' => function () {
                    $this->setEnvValue('SESSION_LIFETIME', '525600');
                }
            ],
            'stubs' => [
                'label' => 'Publish auth controllers and routes',
                'run' => function () {
                    Artisan::call('vendor:publish', [
                        '--tag' => 'rosalana-accounts-stubs',
                        '--force' => true
                    ]);
                }
            ]
        ];
    }
}
