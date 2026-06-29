<?php

namespace Akika\LaravelWeevo\Commands;

use Illuminate\Console\Command;

class InstallLaravelWeevoPackage extends Command
{
    protected $signature = 'weevo:install {--force : Overwrite existing config file}';

    protected $description = 'Install the Laravel Weevo package';

    public function handle(): int
    {
        $configExists = file_exists(config_path('weevo.php'));

        if ($configExists && ! $this->option('force')) {
            if (! $this->confirm('The Weevo config file already exists. Do you want to overwrite it?')) {
                $this->info('Publishing Weevo config cancelled.');

                return self::SUCCESS;
            }
        }

        $this->call('vendor:publish', [
            '--provider' => 'Akika\LaravelWeevo\WeevoServiceProvider',
            '--tag' => 'weevo-config',
            '--force' => $configExists || $this->option('force'),
        ]);

        $this->info('Laravel Weevo package installed successfully.');

        return self::SUCCESS;
    }
}
