<?php

use App\Http\Controllers\ArmorsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SkillsController;
use Illuminate\Support\Facades\Route;

Route::post('users/register', [AuthController::class, 'register']);
Route::post('users/login', [AuthController::class, 'login']);
Route::middleware('auth:api')->group(function() {
    Route::post('users/logout', [AuthController::class, 'logout']);
});
//SKILLS ROUTES
Route::post('skills/create', [SkillsController::class, 'createSkill']);
Route::put('skills/update/{id}', [SkillsController::class, 'updateSkill']);
Route::delete('skills/delete/{id}', [SkillsController::class, 'deleteSkill']);
Route::get('skills', [SkillsController::class, 'readSkills']);

//ARMORS ROUTES
Route::post('armors/create', [ArmorsController::class, 'createArmor']);
Route::put('armors/update/{id}', [ArmorsController::class, 'updateArmor']);
Route::delete('armors/delete/{id}', [ArmorsController::class, 'deleteArmor']);
Route::get('armors', [ArmorsController::class, 'readArmor']);




