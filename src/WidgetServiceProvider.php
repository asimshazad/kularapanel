<?php

namespace Khludev\KuLaraPanel;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Foundation\AliasLoader;

class WidgetServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('widget', function ($expression) {
            return "{!! \Widget::{$expression}()->render() !!}";
        });

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->commands([
                Commands\CrudWidget::class,
            ]);
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        // Register the service the package provides.
        $this->app->singleton('widget', function ($app) {
            return new Widget;
        });
        AliasLoader::getInstance([
            'Widget' => '\Khludev\KuLaraPanel\Widget'
        ]);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['widget'];
    }
}
