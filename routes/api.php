<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('troupes')->group(base_path('routes/api/troupes.php'));
Route::prefix('memberships')->group(base_path('routes/api/memberships.php'));
Route::prefix('messages')->group(base_path('routes/api/messages.php'));
Route::prefix('conversations')->group(base_path('routes/api/conversations.php'));
Route::prefix('participants')->group(base_path('routes/api/participants.php'));
Route::prefix('troupe-tags')->group(base_path('routes/api/troupe_tags.php'));
Route::prefix('users')->group(base_path('routes/api/users.php'));
