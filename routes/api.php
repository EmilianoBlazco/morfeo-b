<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum'])->get('/role', function (Request $request) {
    return $request->user()->getRoleNames();
});



require __DIR__.'/auth.php';
