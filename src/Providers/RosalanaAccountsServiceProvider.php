<?php

namespace Rosalana\Accounts\Providers;

use Illuminate\Support\ServiceProvider;
use Rosalana\Accounts\Services\AccountsManager;
use Rosalana\Core\Services\Basecamp\Manager;

class RosalanaAccountsServiceProvider extends ServiceProvider
{
    /**
     * Register everything in the container.
     */
    public function register()
    {
        $this->app->singleton('rosalana.accounts', function () {
            return new AccountsManager(
                new \Rosalana\Accounts\Services\AuthService(),
                new \Rosalana\Core\Session\TokenSession(),
                new \Rosalana\Accounts\Session\AuthSession(),
                new \Rosalana\Accounts\Services\UsersService()
            );
        });

        $this->app->resolving('rosalana.basecamp', function (Manager $manager) {
            $manager->registerService('auth', new \Rosalana\Accounts\Services\Basecamp\AuthService());
            $manager->registerService('users', new \Rosalana\Accounts\Services\Basecamp\UsersService());
        });
    }

    /**
     * Boot services.
     */
    public function boot()
    {
        if (!$this->app->runningInConsole()) {
            $this->app['router']->pushMiddlewareToGroup('web',\Rosalana\Accounts\Http\Middleware\CheckRosalanaTokenExpiration::class);
        }

        $this->publishes([
            __DIR__ . '/../../database/migrations/' => database_path('migrations'),
        ], 'rosalana-accounts-migrations');

        $this->publishes([
            __DIR__ . '/../../stubs/Http/Controllers/Auth' => app_path('Http/Controllers/Auth'),
            __DIR__ . '/../../stubs/Http/Requests/Auth' => app_path('Http/Requests/Auth'),
            __DIR__ . '/../../stubs/routes/auth.php' => base_path('routes/auth.php'),
        ], 'rosalana-accounts-stubs');
    }
}
