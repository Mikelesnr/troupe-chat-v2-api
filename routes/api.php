<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/csrf-token', function () {
    $token = Str::random(40);

    Cookie::queue('XSRF-TOKEN', $token, 120); // 2 hours

    return response()->json([
        'xsrf_token' => $token,
    ]);
});
