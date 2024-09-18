<?php

use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::put('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
    Route::put('/password', [PasswordController::class, 'update'])
        ->name('password.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::resource('/roles', RoleController::class);
    Route::resource('/permissions', PermissionController::class);
});
