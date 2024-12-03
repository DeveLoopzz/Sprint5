<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SkillsController;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Route;

Route::post('users/register', [AuthController::class, 'register']);
Route::post('users/login', [AuthController::class, 'login']);
Route::middleware('auth:api')->group(function() {
    Route::post('users/logout', [AuthController::class, 'logout']);
});
//SKILLS ROUTES
Route::post('skills/create', [SkillsController::class, 'createSkill']);
Route::post('skills/update/{id}', [SkillsController::class, 'updateSkill']);
Route::post('skills/delete/{id}', [SkillsController::class, 'deleteSkill']);
Route::post('skills', [SkillsController::class], 'readSkills');


