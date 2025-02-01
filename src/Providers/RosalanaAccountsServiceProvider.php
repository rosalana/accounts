<?php

namespace Rosalana\Core\Providers;

use Illuminate\Support\ServiceProvider;

class RosalanaAccountsServiceProvider extends ServiceProvider
{
    /**
     * Register everything in the container.
     */
    public function register()
    {
        // Co když chci dopsat do rosalana.php něco navíc? a nevytvářet nový?
        $this->mergeConfigFrom(__DIR__ . '/../../config/rosalana-accounts.php', 'rosalana');
    }

    /**
     * Boot services.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../database/migrations/' => database_path('migrations'),
        ], 'rosalana-accounts-migrations');
    }
}
