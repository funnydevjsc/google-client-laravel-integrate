<?php

namespace FunnyDev\GoogleClient;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\ServiceProvider;

class GoogleClientServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/google-service.php' => config_path('google-service.php'),
        ], 'funnydev-google-drive');

        try {
            if (!file_exists(config_path('google-service.php'))) {
                $this->commands([
                    \Illuminate\Foundation\Console\VendorPublishCommand::class,
                ]);

                Artisan::call('vendor:publish', ['--provider' => 'FunnyDev\\GoogleClient\\GoogleClientServiceProvider', '--tag' => ['funnydev-google-client']]);
            }
        } catch (\Exception $e) {}
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/google-service.php', 'google-service'
        );
        $this->app->singleton(\FunnyDev\GoogleClient\GoogleServiceClient::class, function () {
            return new \FunnyDev\GoogleClient\GoogleServiceClient;
        });
    }
}
