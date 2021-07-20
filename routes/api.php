<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;

Route::get('/test', function () {
    return response()->json('hello world');
});

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::delete('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::group(['middleware'=>'auth:sanctum'],function () {
    Route::prefix('users')->group(function () {
        Route::get('profile', [UserController::class,'index']);
    });
});