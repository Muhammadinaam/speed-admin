<?php

namespace MuhammadInaamMunir\SpeedAdmin;

use Illuminate\Routing\Router;
use MuhammadInaamMunir\SpeedAdmin\Http\Middleware\AdminAuth;
use MuhammadInaamMunir\SpeedAdmin\Http\Middleware\Language;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    const CONFIG_PATH = __DIR__ . '/../config/speed-admin.php';

    public function boot()
    {
        $this->setMenu();

        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('admin_auth', AdminAuth::class);
        $router->aliasMiddleware('language', Language::class);

        $this->publishes([
            self::CONFIG_PATH => config_path('speed-admin.php'),
        ], 'config');

        $this->publishes([
            __DIR__ . '/../public' => public_path('vendor/speed-admin'),
        ], 'public');

        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'speed-admin');

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'speed-admin');
        $this->loadJsonTranslationsFrom(__DIR__.'/../resources/lang');

        $this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/speed-admin'),
        ]);
    }

    public function register()
    {
        $this->mergeConfigFrom(
            self::CONFIG_PATH,
            'speed-admin'
        );

        $this->app->bind('speed-admin', function () {
            return new SpeedAdmin();
        });

        $this->app->singleton('speed-admin-menu', function() {
            return new SpeedAdminMenu();
        });
    }

    private function setMenu()
    {
        $menu = $this->app->make('speed-admin-menu');
        $menu->addMenu('side-bar', [
            'id' => 'administration',
            'parent_id' => null,
            'before_id' => null,
            'title' => __('Administration'),
            'icon' => 'cil-speedometer',
            'permission' => null 
        ]);

        $menu->addMenu('side-bar', [
            'id' => 'users-list',
            'parent_id' => 'administration',
            'before_id' => null,
            'title' => __('Users'),
            'icon' => 'cil-speedometer',
            'permission' => 'users-list',
            'href' => url(config('speed-admin.admin_url')) . '/users',
        ]);
    }
}
