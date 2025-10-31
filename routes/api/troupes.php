<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TroupeController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/', [TroupeController::class, 'index']);       // All troupes
    Route::get('/public', [TroupeController::class, 'public']); // Only public troupes

    Route::post('/', [TroupeController::class, 'store']);
    Route::get('/{id}', [TroupeController::class, 'show']);
    Route::put('/{id}', [TroupeController::class, 'update']);
    Route::delete('/{id}', [TroupeController::class, 'destroy']);
});
