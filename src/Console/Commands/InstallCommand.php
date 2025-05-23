<?php

namespace Rosalana\Accounts\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rosalana:accounts:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the Rosalana Accounts controllers and resources';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->call('install:api');
        
        $files = new Filesystem;
        
        $this->info('Installing Rosalana Accounts...');
        $this->info('Publishing Rosalana Accounts assets...');

        // Controllers... copy only if they don't exist
        $files->ensureDirectoryExists(app_path('Http/Controllers/Auth'));
        if (! $files->exists(app_path('Http/Controllers/Auth/AuthenticatedSessionController.php'))) {
            $files->copy(__DIR__ . '/../../../stubs/Http/Controllers/Auth/AuthenticatedSessionController.php', app_path('Http/Controllers/Auth/AuthenticatedSessionController.php'));
        }
        if (! $files->exists(app_path('Http/Controllers/Auth/ConfirmablePasswordController.php'))) {
            $files->copy(__DIR__ . '/../../../stubs/Http/Controllers/Auth/RegisteredUserController.php', app_path('Http/Controllers/Auth/RegisteredUserController.php'));
        }

        // Middleware...
        $this->installMiddleware([
            '\Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class'
        ], 'api', 'prepend');

        // Requests...
        $files->ensureDirectoryExists(app_path('Http/Requests/Auth'));
        $files->copyDirectory(__DIR__ . '/../../../stubs/Http/Requests/Auth', app_path('Http/Requests/Auth'));

        // Routes...
        copy(__DIR__ . '/../../../stubs/routes/auth.php', base_path('routes/auth.php'));
        copy(__DIR__ . '/../../../stubs/routes/web.php', base_path('routes/web.php'));
        copy(__DIR__ . '/../../../stubs/routes/api.php', base_path('routes/api.php'));

        // Configuration...
        $files->copyDirectory(__DIR__ . '/../../../stubs/config', config_path());

        $this->info('Rosalana Accounts scaffolding installed successfully.');

        $this->info('Publishing Rosalana Accounts configuration...');

        // Environment...
        if (! file_exists(base_path('.env'))) {
            copy(base_path('.env.example'), base_path('.env'));
        }

        file_put_contents(
            base_path('.env'),
            preg_replace('/APP_URL=(.*)/', 'APP_URL=http://localhost:8001' . PHP_EOL . 'FRONTEND_URL=http://localhost:3000' . PHP_EOL . 'SANCTUM_STATEFUL_DOMAINS=localhost:3000', file_get_contents(base_path('.env')))
        );

        $this->call('vendor:publish',[
            '--tag' => 'rosalana-accounts-migrations',
            '--force' => true,
        ]);

        $this->info('Rosalana Accounts scaffolding installed successfully.');
    }

    /**
     * Install the given middleware names into the application.
     *
     * @param  array|string  $name
     * @param  string  $group
     * @param  string  $modifier
     * @return void
     */
    protected function installMiddleware($names, $group = 'web', $modifier = 'append')
    {
        $bootstrapApp = file_get_contents(base_path('bootstrap/app.php'));

        $names = collect(Arr::wrap($names))
            ->filter(fn ($name) => ! Str::contains($bootstrapApp, $name))
            ->whenNotEmpty(function ($names) use ($bootstrapApp, $group, $modifier) {
                $names = $names->map(fn ($name) => "$name")->implode(','.PHP_EOL.'            ');

                $bootstrapApp = str_replace(
                    '->withMiddleware(function (Middleware $middleware) {',
                    '->withMiddleware(function (Middleware $middleware) {'
                        .PHP_EOL."        \$middleware->$group($modifier: ["
                        .PHP_EOL."            $names,"
                        .PHP_EOL.'        ]);'
                        .PHP_EOL,
                    $bootstrapApp,
                );

                file_put_contents(base_path('bootstrap/app.php'), $bootstrapApp);
            });
    }
}
