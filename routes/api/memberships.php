<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MembershipController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/', [MembershipController::class, 'index']);       // View all memberships
    Route::post('/', [MembershipController::class, 'store']);      // Join a troupe
    Route::get('/{id}', [MembershipController::class, 'show']);    // View specific membership
    Route::put('/{id}', [MembershipController::class, 'update']);  // Update membership (e.g. switch troupe)
    Route::delete('/{id}', [MembershipController::class, 'destroy']); // Leave troupe
});
