<?php

use App\Http\Controllers\Custom\CredentialController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\DatabaseTransaction;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware(DatabaseTransaction::class)->group(function () {
    Route::prefix('account')->group(function () {
        Route::post('login', [CredentialController::class, 'login']);
        Route::post('refresh-token', [CredentialController::class, 'refreshToken']);
    });

    Route::middleware(AdminMiddleware::class)->group(function () {
        Route::post('logout', [CredentialController::class, 'logout']);

        require __DIR__.'/api_generated.php';
    });
});
