<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\SkillController;
use App\Http\Controllers\Api\MatchController;
use App\Http\Controllers\Api\ActivityLogController;
use App\Http\Controllers\Api\SettingController;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::patch('/users/{id}/block', [UserController::class, 'block']);
    Route::patch('/users/{id}/unblock', [UserController::class, 'unblock']);

    Route::post('/user-skills', [UserController::class, 'addSkill']);

    Route::get('/reviews', [UserController::class, 'allReviews']);
    Route::get('/users/{id}/reviews', [UserController::class, 'userReviews']);

    Route::get('/skills', [SkillController::class, 'index']);
    Route::post('/skills', [SkillController::class, 'store']);
    Route::put('/skills/{id}', [SkillController::class, 'update']);
    Route::delete('/skills/{id}', [SkillController::class, 'destroy']);

    Route::get('/matches', [MatchController::class, 'index']);
    Route::post('/matches/generate', [MatchController::class, 'generate']);
    Route::get('/matches/suggested', [MatchController::class, 'suggested']);
    Route::get('/matches/{id}', [MatchController::class, 'show']);

    Route::get('/activity-logs', [ActivityLogController::class, 'index']);
    Route::get('/users/{id}/activity', [ActivityLogController::class, 'userActions']);

    Route::get('/settings/profile', [SettingController::class, 'profile']);
    Route::put('/settings/profile', [SettingController::class, 'updateProfile']);
    Route::post('/settings/profile', [SettingController::class, 'updateProfile']);
});