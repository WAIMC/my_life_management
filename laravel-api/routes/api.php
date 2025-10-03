<?php

use App\Http\Controllers\Master\AdminMstController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    Route::prefix('account')->group(function () {
        Route::post('login', [AdminMstController::class, 'login']);
        Route::post('refresh-token', [AdminMstController::class, 'refreshToken']);
    });

    Route::middleware(AdminMiddleware::class)->group(function () {

        // Master
        Route::prefix('master')->group(function () {
            // Admin
            Route::prefix('account')->group(function () {
                Route::post('logout', [AdminMstController::class, 'logout']);
            });
        });

        require __DIR__.'/api_generated.php';
    });
});
