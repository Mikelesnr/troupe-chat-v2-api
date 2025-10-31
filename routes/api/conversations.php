<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConversationController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/', [ConversationController::class, 'index']);
    Route::post('/', [ConversationController::class, 'store']);
    Route::get('/{id}', [ConversationController::class, 'show']);
    Route::put('/{id}', [ConversationController::class, 'update']);
    Route::delete('/{id}', [ConversationController::class, 'destroy']);
});
