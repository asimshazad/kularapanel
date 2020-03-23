<?php

namespace Khludev\KuLaraPanel;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Filesystem\Filesystem;

class SimpleControlPanelServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // alias middleware
        $this->app['router']->aliasMiddleware('auth_admin', 'Khludev\KuLaraPanel\Middleware\AuthAdmin');
        $this->app['router']->aliasMiddleware('guest_admin', 'Khludev\KuLaraPanel\Middleware\GuestAdmin');
        $this->app['router']->aliasMiddleware('intend_url', 'Khludev\KuLaraPanel\Middleware\IntendUrl');
        $this->app['router']->aliasMiddleware('not_admin_role', 'Khludev\KuLaraPanel\Middleware\NotAdminRole');
        $this->app['router']->aliasMiddleware('not_system_doc', 'Khludev\KuLaraPanel\Middleware\NotSystemDoc');
        $this->app['router']->aliasMiddleware('api_logger', 'Khludev\KuLaraPanel\Middleware\ApiLogger');
        $this->app['router']->aliasMiddleware('https_protocol', 'Khludev\KuLaraPanel\Middleware\HttpsProtocol');

        $this->mergeConfigFrom(__DIR__.'/../config/simplecontrolpanel.php', 'lap');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'lap');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/routes.php');
        $this->gatePermissions();
        $this->validatorExtensions();
        $this->configSettings();

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
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
        $this->app->singleton('simplecontrolpanel', function ($app) {
            return new SimpleControlPanel;
        });
        $this->app->register(\Khludev\KuLaraPanel\WidgetServiceProvider::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['simplecontrolpanel'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        // install general
        $this->publishes([
            __DIR__ . '/../public' => public_path('lap'),
            __DIR__ . '/../resources/lang' => resource_path('lang'),
            __DIR__ . '/../resources/views/layouts' => resource_path('views/vendor/lap/layouts'),
            __DIR__ . '/../resources/views/auth' => resource_path('views/vendor/lap/auth'),
            __DIR__ . '/../resources/views/backend' => resource_path('views/vendor/lap/backend'),
            __DIR__ . '/../resources/views/users' => resource_path('views/vendor/lap/users'),
        ], 'lap.general');

        // install all views
        $this->publishes([__DIR__ . '/../resources/views' => resource_path('views/vendor/lap')], 'lap.all.view');

        // in case want to customized the routes
        $this->publishes([__DIR__ . '/routes.php' => resource_path('../'.config('lap.crud_paths.route').'/routes.php')], 'lap.admin.route');

        // advanced. if u know what to do, install 1 by 1
        $this->publishes([__DIR__.'/../config/simplecontrolpanel.php' => config_path('lap.php')], 'lap.config');
        $this->publishes([__DIR__.'/../config/seotools.php' => config_path('seotools.php')], 'lap.seo.config');
        $this->publishes([__DIR__ . '/../public' => public_path('lap')], 'lap.public');
        $this->publishes([__DIR__ . '/../resources/lang' => resource_path('lang')], 'lap.lang');
        $this->publishes([__DIR__ . '/../resources/views/layouts' => resource_path('views/vendor/lap/layouts')], 'lap.layouts');
        $this->publishes([__DIR__ . '/../resources/views/auth' => resource_path('views/vendor/lap/auth')], 'lap.auth.view');
        $this->publishes([__DIR__ . '/../resources/views/backend' => resource_path('views/vendor/lap/backend')], 'lap.backend.view');
        $this->publishes([__DIR__ . '/../resources/views/users' => resource_path('views/vendor/lap/users')], 'lap.users.view');

        $files = new Filesystem;
        if (!$files->exists(config('lap.crud_paths.route'))) {
            $files->makeDirectory(config('lap.crud_paths.route'), 0755, true);
        }
        if (file_exists(resource_path('../'.config('lap.crud_paths.route').'/routes.php'))) {
            $routes = $files->get(config('lap.crud_paths.routes'));
            $route_content = PHP_EOL . "include_once(resource_path('../".config('lap.crud_paths.route')."/routes.php'));";
            if (strpos($routes, $route_content) === false) {
                $files->append(config('lap.crud_paths.routes'), $route_content);
            }
        }

        $this->publishes([__DIR__ . '/../database/migrations' => database_path('migrations')], 'lap.migrations');
        $this->publishes([__DIR__ . '/../resources/stubs/crud/default' => resource_path('stubs/crud/default')], 'lap.stubs');

        // Registering package commands.
        $this->commands([
                Commands\CrudConfig::class,
                Commands\CrudGenerate::class,
        ]);

    }

    public function gatePermissions()
    {
        Gate::before(function ($user, $permission) {
            if ($user->hasPermission($permission)) {
                return true;
            }
        });
    }

    public function validatorExtensions()
    {
        Validator::extend('current_password', function ($attribute, $value, $parameters, $validator) {
            return Hash::check($value, auth()->user()->password);
        }, 'The current password is invalid.');
    }

    public function configSettings()
    {
        if (Schema::hasTable('settings')) {
            foreach (app(config('lap.models.setting'))->all() as $setting) {
                Config::set('settings.' . $setting->key, $setting->value);
            }
        }
    }
}
