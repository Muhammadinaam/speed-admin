<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Intervention\Image\Facades\Image;
use MuhammadInaamMunir\SpeedAdmin\Http\Controllers\AuthController;
use MuhammadInaamMunir\SpeedAdmin\Http\Controllers\LanguageController;
use MuhammadInaamMunir\SpeedAdmin\Http\Controllers\UserController;
use MuhammadInaamMunir\SpeedAdmin\Http\Controllers\SelectController;
use MuhammadInaamMunir\SpeedAdmin\Http\Controllers\GridController;

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

    Route::get('select-model', [SelectController::class, 'selectModel'])->name('select.model');

    Route::post('perform-grid-action', [GridController::class, 'performGridAction']);
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
