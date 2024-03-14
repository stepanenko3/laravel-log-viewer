<?php

namespace Stepanenko3\LaravelLogViewer;

use Illuminate\Support\Facades\Event;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Stepanenko3\LaravelLogViewer\Events\LogFileDeleted;
use Stepanenko3\LaravelLogViewer\Facades\LogViewer;

class LogViewerServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('log-viewer')
            ->hasConfigFile();
    }

    public function boot(): void
    {
        parent::boot();

        Event::listen(LogFileDeleted::class, function (): void {
            LogViewer::clearFileCache();
        });
    }
}
