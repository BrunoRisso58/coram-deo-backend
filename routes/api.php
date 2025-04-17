<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('/auth')->middleware('check.jwt')->group(function () {
    Route::post('/sign-up', [UserController::class, 'signUp'])->withoutMiddleware('check.jwt');
    Route::get('/user/{id}', [UserController::class, 'getUser'])->middleware('check.user');
    Route::put('/user/{id}', [UserController::class, 'updateUser'])->middleware('check.user');
    Route::delete('/user/{id}', [UserController::class, 'deleteUser'])->middleware('check.user');
    Route::get('/users', [UserController::class, 'getUsers']);
    Route::post('/login', [UserController::class, 'login'])->withoutMiddleware('check.jwt');
    Route::post('/logout', [UserController::class, 'logout']);
    Route::get('/check-email', [UserController::class, 'checkEmail'])->withoutMiddleware('check.jwt');
});
