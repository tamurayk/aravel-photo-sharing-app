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
    });

    /**
     * Require admin auth
     */
    Route::middleware('auth:admin')->group(function () {
    });
});
