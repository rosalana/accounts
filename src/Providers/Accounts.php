<?php

namespace Rosalana\Accounts\Providers;

use Illuminate\Support\Facades\Artisan;
use Rosalana\Configure\Configure;
use Rosalana\Core\Console\InternalCommands;
use Rosalana\Core\Contracts\Package;

class Accounts implements Package
{
    use InternalCommands;

    public function resolvePublished(): bool
    {
        return Configure::fileExists('rosalana') && Configure::file('rosalana')->has('accounts');
    }

    public function publish(): array
    {
        return [
            'config' => [
                'label' => 'Publish configuration settings to rosalana.php',
                'run' => function () {

                    Configure::file('rosalana')
                        ->section('accounts')
                        ->withComment(
                            'Rosalana Basecamp Account Link',
                            "Describe how Basecamp account is linked to your local \nEloquent model. If you wish to change the identifier, \nmake sure to update the provided database migration as well.",
                        )
                        ->value('model', 'App\Models\User::class')
                        ->value('identifier', "rosalana_account_id")
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
