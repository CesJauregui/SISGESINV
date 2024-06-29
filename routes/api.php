<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\InscriptionController;
use App\Http\Controllers\Inscriptions\ObservationController;
use App\Http\Controllers\Inscriptions\TaskController;
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
    /*Routes Inscriptions*/
    Route::apiResource('inscription', InscriptionController::class);
    Route::get('/teachers', [InscriptionController::class, 'getTeachers']);
    Route::get('/graduates-students', [InscriptionController::class, 'getGraduatesStudents']);
    Route::put('/inscription/{inscription}/status', [InscriptionController::class, 'changeState']);

    Route::prefix('inscription/{inscription}')->group(function () {
        Route::apiResource('/tasks', TaskController::class);
        Route::apiResource('/observations', ObservationController::class);
    });

    Route::delete('logout', [AuthController::class, 'logout']);
});
