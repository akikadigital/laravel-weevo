<?php

namespace Akika\LaravelWeevo\Commands;

use Illuminate\Console\Command;

class InstallLaravelWeevoPackage extends Command
{
    protected $signature = 'weevo:install';

    protected $description = 'Publish Akika/LaravelWeevo package migrations';

    public function handle()
    {
        // check if the config file exists
        if (!$this->checkIfConfigExists()) {
            $this->publishConfig();
        } else {
            // get confirmation from user to overwrite existing config file
            if ($this->getForceConcentForConfig()) {
                $this->publishConfig(true);
            } else {
                $this->info('Publishing Akika/LaravelWeevo package config file cancelled.');
            }
        }
    }

    /**
     * Check if the config file exists
     * @return bool
     */

    public function checkIfConfigExists()
    {
        $this->info('Checking if Akika/LaravelWeevo package config file exists...');

        // check if the config file exists
        if (file_exists(config_path('weevo.php'))) {
            $this->info('Akika/LaravelWeevo package config file already exists.');
            return true;
        } else {
            $this->info('Akika/LaravelWeevo package config file does not exist.');
            return false;
        }
    }

    /**
     * Publish the config file
     * @param bool $forcePublish
     * @return void
     */

    public function publishConfig($forcePublish = false)
    {
        $this->info('Publishing Akika/LaravelWeevo package config file...');

        $params = [
            '--provider' => "Akika\LaravelWeevo\WeevoServiceProvider",
            '--tag' => "config"
        ];

        if ($forcePublish) {
            $params['--force'] = true;
        }

        $this->call('vendor:publish', $params);

        $this->info('Akika/LaravelWeevo package config file published successfully.');
    }

    /**
     * Get confirmation from user to overwrite existing config file
     * @return bool
     */

    public function getForceConcentForConfig()
    {
        return $this->confirm('Do you want to overwrite existing config file?');
    }
}
