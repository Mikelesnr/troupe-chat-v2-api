<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TroupeTagController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/{troupeId}/sync', [TroupeTagController::class, 'syncTags']);
    Route::delete('/{troupeId}/detach/{tagId}', [TroupeTagController::class, 'detachTag']);
});
