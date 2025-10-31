<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MessageController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/', [MessageController::class, 'index']);
    Route::post('/', [MessageController::class, 'store']);
    Route::get('/{id}', [MessageController::class, 'show']);
    Route::put('/{id}', [MessageController::class, 'update']);
    Route::delete('/{id}', [MessageController::class, 'destroy']);
});
