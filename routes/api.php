<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\DealController;
use App\Http\Controllers\Api\PipelineController;
use App\Http\Controllers\Api\PipelineStageController;
use App\Http\Controllers\Api\WebhookController;
use Illuminate\Support\Facades\Route;

Route::name('api.')->group(function () {
    include __DIR__ . '/webhook.php';

    Route::post('/auth/login', [AuthController::class, 'login']);

    Route::middleware(['cookie.auth'])->group(function () {
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/auth/me', [AuthController::class, 'me']);

        Route::resource('clients', ClientController::class);

        Route::resource('deals', DealController::class);
        Route::post('deals/{deal}/next-stage', [DealController::class, 'nextStage']);
        Route::post('deals/{deal}/move-to-stage/{stage}', [DealController::class, 'moveToStage']);

        Route::resource('pipelines', PipelineController::class);
        Route::resource('pipelines.stages', PipelineStageController::class);
    });

    Route::post('/webhook', [WebhookController::class, 'handle']);
});
