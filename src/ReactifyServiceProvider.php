<?php

namespace PHPDominicana\Reactify;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use PHPDominicana\Reactify\Commands\ReactifyCommand;

class ReactifyServiceProvider extends PackageServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('reactify')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_reactify_table')
            ->publishesServiceProvider(ReactifyServiceProvider::class)
            ->hasCommand(ReactifyCommand::class);
    }
}
