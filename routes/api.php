<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\InscripcionController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::apiResource('users', UserController::class);

Route::controller(AuthController::class)->group(function () {
    Route::post('/auth/login', 'login');
    Route::post('/auth/reset-password', 'sendResetPassword');
    Route::post('/auth/reset-password/{token}', 'resetPassword')->name('reset-password');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::delete('logout', [AuthController::class, 'logout']);
});
