<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Intervention\Image\Facades\Image;
use MuhammadInaamMunir\SpeedAdmin\Http\Controllers\AuthController;
use MuhammadInaamMunir\SpeedAdmin\Http\Controllers\LanguageController;

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

Route::group([
    'prefix' => config('speed-admin.admin_url'),
    'middleware' => ['web', 'admin_auth', 'language'],
], function () {

    Route::get('/', function () {
        return view('speed-admin::layouts.layout');
    });

    Route::view(
        '/profile',
        'speed-admin::auth.profile',
        ['breadcrumbs' => [__('Account'), __('Profile')]]
    )->name('admin.profile.form');

    Route::post('/profile', [AuthController::class, 'updateProfile'])->name('admin.update-profile');

    Route::view(
        '/change-password',
        'speed-admin::auth.change-password',
        ['breadcrumbs' => [__('Account'), __('Change password')]]
    )->name('admin.change-password-form');

    Route::post('/change-password', [AuthController::class, 'changePassword'])->name('admin.change-password');

    Route::get('uploaded-images', function() {
        return response()->file(Storage::path(request()->path));
    })->name('admin.get-uploaded-image');
});

Route::group(['middleware' => ['web', 'language'], 'prefix' => config('speed-admin.admin_url')], function () {

    Route::view('login', 'speed-admin::auth.login')->name('admin.login');
    Route::post('login', [AuthController::class, 'authenticate']);
    Route::get('logout', function () {
        Auth::logout();
        return redirect(route('admin.login'));
    })->name('admin.logout');

    Route::view('forgot-password', 'speed-admin::auth.forgot-password')->name('admin.forgot-password-form');
    Route::post('forgot-password', [AuthController::class, 'forgotPassword'])->name('admin.forgot-password');

    Route::get('/reset-password/{token}', function ($token) {
        return view('speed-admin::auth.reset-password', ['token' => $token]);
    })->name('password.reset');

    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('admin.password.update');

    Route::get('select-language', [LanguageController::class, 'selectLanguage'])->name('admin.select-language');
});
