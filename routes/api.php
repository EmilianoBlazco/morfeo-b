<?php

use App\Http\Controllers\Attendance\AttendanceController;
use App\Http\Middleware\EnsureHasEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum'])->get('/role', function (Request $request) {
    return $request->user()->getRoleNames();
});

Route::middleware([EnsureHasEntry::class])->post('/attendance', [AttendanceController::class, 'markAttendance']);


require __DIR__.'/auth.php';
