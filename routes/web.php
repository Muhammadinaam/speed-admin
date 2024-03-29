<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Intervention\Image\Facades\Image;
use MuhammadInaamMunir\SpeedAdmin\Http\Controllers\AuthController;
use MuhammadInaamMunir\SpeedAdmin\Http\Controllers\LanguageController;
use MuhammadInaamMunir\SpeedAdmin\Http\Controllers\UserController;
use MuhammadInaamMunir\SpeedAdmin\Http\Controllers\RoleController;
use MuhammadInaamMunir\SpeedAdmin\Http\Controllers\BelongsToController;
use MuhammadInaamMunir\SpeedAdmin\Http\Controllers\GridController;
use MuhammadInaamMunir\SpeedAdmin\Http\Controllers\TenantOrganizationController;
use MuhammadInaamMunir\SpeedAdmin\Http\Controllers\SettingController;
use MuhammadInaamMunir\SpeedAdmin\Http\Controllers\ApplicationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::middleware(['web', 'admin_auth', 'language'])
->prefix(config('speed-admin.admin_url'))
->name('admin.')
->group(function () {

    Route::get('/', function () {
        return view('speed-admin::layouts.layout');
    })->name('base');

    Route::view(
        '/profile',
        'speed-admin::auth.profile',
        ['breadcrumbs' => [__('Account'), __('Profile')]]
    )->name('profile.form');

    Route::post('/profile', [AuthController::class, 'updateProfile'])->name('update-profile');

    Route::view(
        '/change-password',
        'speed-admin::auth.change-password',
        ['breadcrumbs' => [__('Account'), __('Change password')]]
    )->name('change-password-form');

    Route::post('/change-password', [AuthController::class, 'changePassword'])->name('change-password');

    Route::get('uploaded-images', function() {
        return response()->file(Storage::path(request()->path));
    })->name('get-uploaded-image');

    Route::resource('users', UserController::class);
    Route::get('users-data', [UserController::class, 'getData'])->name('users.get-data');

    if(config('speed-admin.enable_tenant_organization_feature'))
    {
        Route::resource('tenant-organizations', TenantOrganizationController::class);
        Route::get('tenant-organizations-data', [TenantOrganizationController::class, 'getData'])->name('tenant-organizations.get-data');
    }

    Route::resource('roles', RoleController::class);
    Route::get('roles-data', [RoleController::class, 'getData'])->name('roles.get-data');

    Route::get('select-model', [BelongsToController::class, 'selectModel'])->name('select.model');

    Route::post('perform-grid-action', [GridController::class, 'performGridAction']);

    Route::get('show-add-new-form', [BelongsToController::class, 'showAddNewForm'])->name('show-add-new-form');
    Route::post('save-data-of-add-new-form', [BelongsToController::class, 'saveDataOfAddNewForm'])->name('save-data-of-add-new-form');

    if(config('speed-admin.settings_enabled'))
    {
        Route::get('settings', [SettingController::class, 'editSettings']);
        Route::put('settings/{id}', [SettingController::class, 'updateSettings']);
    }

    if(config('speed-admin.applications_enabled'))
    {
        Route::get('system-applications', [ApplicationController::class, 'index'])->name('system-applications');
        Route::post('system-applications/{application_name}/change-status', [ApplicationController::class, 'changeStatus'])->name('system-applications.change-status');
        Route::post('system-applications/{application_name}/update-database', [ApplicationController::class, 'updateDatabase'])->name('system-applications.update-database');
    }
});

Route::middleware(['web', 'language'])
->prefix(config('speed-admin.admin_url'))
->name('admin.')
->group(function () {

    Route::view('login', 'speed-admin::auth.login')->name('login');
    Route::post('login', [AuthController::class, 'authenticate']);
    Route::get('logout', function () {
        Auth::logout();
        return redirect(route('admin.login'));
    })->name('logout');

    Route::view('forgot-password', 'speed-admin::auth.forgot-password')->name('forgot-password-form');
    Route::post('forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot-password');

    Route::get('/reset-password/{token}', function ($token) {
        return view('speed-admin::auth.reset-password', ['token' => $token]);
    })->name('password.reset');

    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

    Route::get('select-language', [LanguageController::class, 'selectLanguage'])->name('select-language');
});
