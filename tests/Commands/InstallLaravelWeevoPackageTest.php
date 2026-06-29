<?php

use Akika\LaravelWeevo\Commands\InstallLaravelWeevoPackage;
use Illuminate\Support\Facades\Artisan;

beforeEach(function () {
    @unlink(config_path('weevo.php'));
});

afterEach(function () {
    @unlink(config_path('weevo.php'));
    \Mockery::close();
});

it('runs the install command successfully', function () {
    Artisan::call('weevo:install', [
        '--force' => true,
    ]);

    expect(Artisan::output())
        ->toContain('Laravel Weevo package installed successfully.');
});

it('publishes the weevo config file', function () {
    expect(file_exists(config_path('weevo.php')))->toBeFalse();

    Artisan::call('weevo:install', [
        '--force' => true,
    ]);

    expect(file_exists(config_path('weevo.php')))->toBeTrue();
});

it('cancels installation when config exists and force is not supplied', function () {
    file_put_contents(config_path('weevo.php'), '<?php return [];');

    $command = \Mockery::mock(InstallLaravelWeevoPackage::class)
        ->makePartial();

    $command->shouldReceive('option')
        ->with('force')
        ->andReturn(false);

    $command->shouldReceive('confirm')
        ->once()
        ->with('The Weevo config file already exists. Do you want to overwrite it?')
        ->andReturn(false);

    $command->shouldReceive('info')
        ->once()
        ->with('Publishing Weevo config cancelled.');

    expect($command->handle())->toBe(0);
});
