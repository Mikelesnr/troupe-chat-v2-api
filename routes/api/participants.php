<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConversationParticipantController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/', [ConversationParticipantController::class, 'index']);
    Route::post('/', [ConversationParticipantController::class, 'store']);
    Route::get('/{id}', [ConversationParticipantController::class, 'show']);
    Route::put('/{id}', [ConversationParticipantController::class, 'update']);
    Route::delete('/{id}', [ConversationParticipantController::class, 'destroy']);
});
