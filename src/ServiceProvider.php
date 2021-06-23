<?php

namespace MuhammadInaamMunir\SpeedAdmin;

use Illuminate\Routing\Router;
use MuhammadInaamMunir\SpeedAdmin\Http\Middleware\AdminAuth;
use MuhammadInaamMunir\SpeedAdmin\Http\Middleware\Language;
use MuhammadInaamMunir\SpeedAdmin\Facades\SpeedAdminHelpers;
use MuhammadInaamMunir\SpeedAdmin\Console\CreateAdminUser;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    const CONFIG_PATH = __DIR__ . '/../config/speed-admin.php';

    public function boot()
    {
        $this->setMenu();
        $this->addPermissions();

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

        // Register the command if we are using the application via the CLI
        if ($this->app->runningInConsole()) {
            $this->commands([
                CreateAdminUser::class,
            ]);
        }
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

        $this->app->bind('SpeedAdminHelpers', function () {
            return new SpeedAdminHelpers();
        });

        $this->app->singleton('speed-admin-menu', function() {
            return new SpeedAdminMenu();
        });

        $this->app->singleton('speed-admin-models-registry', function() {
            return new SpeedAdminModelsRegistery();
        });

        $this->app->singleton('speed-admin-permissions', function() {
            return new SpeedAdminPermissions();
        });

        $this->app->singleton('speed-admin-settings', function() {
            return new SpeedAdminSettings();
        });

        \SpeedAdminHelpers::setModelRegistry(
            \App\Models\User::class, 
            \MuhammadInaamMunir\SpeedAdmin\Models\User::class
        );
    }

    private function addPermissions()
    {
        $permissions = $this->app->make('speed-admin-permissions');

        $permissions->addPermission('Administration', 'General', 'can-access-admin-panel', 'Can access admin panel');

        $permissions->addModelPermissions(
            'Administration',
            \MuhammadInaamMunir\SpeedAdmin\Models\User::class,
            true,
            true,
            true,
            true
        );

        $permissions->addModelPermissions(
            'Administration',
            \MuhammadInaamMunir\SpeedAdmin\Models\Role::class,
            true,
            true,
            true,
            true
        );
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

        if(config('speed-admin.enable_tenant_organization_feature'))
        {
            $menu->addMenu('side-bar', [
                'id' => 'tenant-organizations-list',
                'parent_id' => 'administration',
                'before_id' => null,
                'title' => __('Tenant Organizations'),
                'icon' => 'cil-speedometer',
                'permission' => 'tenant-organization-list',
                'href' => url(config('speed-admin.admin_url')) . '/tenant-organizations',
            ]);
        }

        $menu->addMenu('side-bar', [
            'id' => 'users-list',
            'parent_id' => 'administration',
            'before_id' => null,
            'title' => __('Users'),
            'icon' => 'cil-speedometer',
            'permission' => 'user-list',
            'href' => url(config('speed-admin.admin_url')) . '/users',
        ]);

        $menu->addMenu('side-bar', [
            'id' => 'roles-list',
            'parent_id' => 'administration',
            'before_id' => null,
            'title' => __('Roles'),
            'icon' => 'cil-speedometer',
            'permission' => 'role-list',
            'href' => url(config('speed-admin.admin_url')) . '/roles',
        ]);

        // TODO
        /*
        $menu->addMenu('side-bar', [
            'id' => 'settings',
            'parent_id' => 'administration',
            'before_id' => null,
            'title' => __('Settings'),
            'icon' => 'cil-speedometer',
            'permission' => 'edit-settings',
            'href' => url(config('speed-admin.admin_url')) . '/settings',
        ]);

        $menu->addMenu('side-bar', [
            'id' => 'applications',
            'parent_id' => 'administration',
            'before_id' => null,
            'title' => __('Applications'),
            'icon' => 'cil-speedometer',
            'permission' => 'manage-applications',
            'href' => url(config('speed-admin.admin_url')) . '/applications',
        ]);
        */
    }
}
