<?php

namespace Rosalana\Accounts\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Rosalana\Accounts\Contracts\AuthContract;
use Rosalana\Accounts\Services\AuthService;
use Rosalana\Core\Services\Basecamp\Manager;

class RosalanaAccountsServiceProvider extends ServiceProvider
{
    /**
     * Register everything in the container.
     */
    public function register()
    {
        $this->app->singleton(AuthContract::class, function() {
            return new AuthService();
        });

        $this->app->resolving('rosalana.basecamp', function (Manager $manager) {
            $manager->registerService('users', new \Rosalana\Accounts\Services\Basecamp\UsersService());
        });
    }

    /**
     * Boot services.
     */
    public function boot(Router $router)
    {
        $router->aliasMiddleware('auth.rosalana', \Rosalana\Accounts\Http\Middleware\CheckRosalanaTokenValidation::class);

        $this->publishes([
            __DIR__ . '/../../database/migrations/' => database_path('migrations'),
        ], 'rosalana-accounts-migrations');

        $this->commands([
            \Rosalana\Accounts\Console\Commands\InstallCommand::class,
        ]);
    }
}
