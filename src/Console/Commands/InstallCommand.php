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
    protected $signature = 'accounts:install';

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

        // Controllers...
        $files->ensureDirectoryExists(app_path('Http/Controllers/Auth'));
        $files->copyDirectory(__DIR__ . '/../../../stubs/Http/Controllers/Auth', app_path('Http/Controllers/Auth'));

        // Middleware...
        $this->installMiddleware([
            '\Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class'
        ], 'api', 'prepend');

        // Requests...
        $files->ensureDirectoryExists(app_path('Http/Requests/Auth'));
        $files->copyDirectory(__DIR__ . '/../../../stubs/Http/Requests/Auth', app_path('Http/Requests/Auth'));

        // Routes...
        copy(__DIR__ . '/../../../stubs/routes/auth.php', base_path('routes/auth.php'));

        // Configuration...
        $files->copyDirectory(__DIR__ . '/../../../stubs/config', config_path());

        // Environment...
        if (! file_exists(base_path('.env'))) {
            copy(base_path('.env.example'), base_path('.env'));
        }

        file_put_contents(
            base_path('.env'),
            preg_replace('/APP_URL=(.*)/', 'APP_URL=http://localhost:8001' . PHP_EOL . 'FRONTEND_URL=http://localhost:3000' . PHP_EOL . 'SANCTUM_STATEFUL_DOMAINS=localhost:3000', file_get_contents(base_path('.env')))
        );

        $this->call('vandor:publish',[
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
