<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\GoalInstanceController;

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

Route::middleware('check.jwt')->group(function () {
    Route::prefix('/auth')->group(function () {
        Route::post('/sign-up', [UserController::class, 'signUp'])->withoutMiddleware('check.jwt');
        Route::get('/user/{id}', [UserController::class, 'getUser'])->middleware('check.user');
        Route::put('/user/{id}', [UserController::class, 'updateUser'])->middleware('check.user');
        Route::delete('/user/{id}', [UserController::class, 'deleteUser'])->middleware('check.user');
        Route::get('/users', [UserController::class, 'getUsers']);
        Route::post('/login', [UserController::class, 'login'])->withoutMiddleware('check.jwt');
        Route::post('/logout', [UserController::class, 'logout']);
        Route::get('/check-email', [UserController::class, 'checkEmail'])->withoutMiddleware('check.jwt');
    });

    Route::prefix('/goal')->group(function () {
        Route::post('/create', [GoalController::class, 'createGoal']);
        Route::get('/{id}', [GoalController::class, 'getGoal']);
        Route::put('/{id}', [GoalController::class, 'updateGoal']);
        Route::delete('/{id}', [GoalController::class, 'deleteGoal']);
        Route::get('/', [GoalController::class, 'getGoals']);
    });

    Route::prefix('/goal-instance')->group(function () {
        Route::post('/create', [GoalInstanceController::class, 'createGoalInstances']);
        Route::get('/', [GoalInstanceController::class, 'getGoalInstances']);
        Route::get('/{id}/get', [GoalInstanceController::class, 'getGoalInstance']);
        Route::delete('/{id}', [GoalInstanceController::class, 'deleteGoalInstance']);
        Route::put('/{id}/mark-as-completed', [GoalInstanceController::class, 'markGoalInstanceAsComplete']);

        Route::get('/completion-dashboard', [GoalInstanceController::class, 'get7DaysCompletionDashboard']);
    });
});
