<?php

use App\Http\Controllers\Custom\CredentialController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\GenerateResponseMiddleware;
use App\Http\Middleware\TransactionMiddleware;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')
    ->middleware(GenerateResponseMiddleware::class)
    ->group(function () {
        Route::prefix('account')->group(function () {
            Route::post('login', [CredentialController::class, 'login']);
            Route::post('refresh-token', [CredentialController::class, 'refreshToken']);
        });

        Route::middleware(TransactionMiddleware::class)
            ->middleware(AdminMiddleware::class)
            ->group(function () {
                Route::post('logout', [CredentialController::class, 'logout']);

                require __DIR__ . '/api_generated.php';
            });
    });
