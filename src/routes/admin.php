<?php

use Illuminate\Support\Facades\Route;

/**
 * routes for admin.
 * These route are applied "admin" middleware group by \App\Http\Kernel::$middlewareGroups.
 */
Route::prefix('admin')->group(function () {
    /**
     * If authenticated, redirect to RouteServiceProvider::ADMIN_HOME by guest middleware.
     * note: guest middleware = RedirectIfAuthenticated\App\Http\Middleware\RedirectIfAuthenticated (See: \App\Http\Kernel::$routeMiddleware)
     */
    Route::middleware('guest:admin')->group(function () {
        Route::get('/', \App\Http\Controllers\Admin\IndexController::class)
            ->name('admin.index');

        Route::get('login', \App\Http\Controllers\Admin\Auth\LoginController::class . '@showLoginForm')
            ->name('admin.login');
        Route::post('login', \App\Http\Controllers\Admin\Auth\LoginController::class . '@login');
    });

    /**
     * Require admin auth
     */
    Route::middleware('auth:admin')->group(function () {
        Route::post('logout', \App\Http\Controllers\Admin\Auth\LoginController::class . '@logout')
            ->name('admin.logout');

        Route::get('home', \App\Http\Controllers\Admin\Home\HomeIndexController::class)
            ->name('admin.home.index');
        Route::get('users', \App\Http\Controllers\Admin\User\UserIndexController::class)
            ->name('admin.user.index');
    });
});
