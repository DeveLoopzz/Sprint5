<?php

use App\Http\Controllers\ArmorsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SetsController;
use App\Http\Controllers\SkillsController;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
//ROUTES AUTHENTICATED
Route::middleware('auth:api')->group(function() {

    Route::post('logout', [AuthController::class, 'logout']);
        //ADMIN ROUTES
        Route::group(['middleware' => ['role:admin']], function(){
            Route::post('skills', [SkillsController::class, 'createSkill']);
            Route::put('skills/{id}', [SkillsController::class, 'updateSkill']);
            Route::delete('skills/{id}', [SkillsController::class, 'deleteSkill']);
            Route::post('armors', [ArmorsController::class, 'createArmor']);
            Route::put('armors/{id}', [ArmorsController::class, 'updateArmor']);
            Route::delete('armors/{id}', [ArmorsController::class, 'deleteArmor']);
        });
        //ROUTES FOR ADMINS AND HUNTERS
        Route::group(['middleware' => ['role:admin|hunter']], function () {
            Route::get('sets', [SetsController::class, 'readSet']);
            Route::get('armors', [ArmorsController::class, 'readArmor']);
            Route::get('skills', [SkillsController::class, 'readSkills']);
            Route::post('sets', [SetsController::class, 'createSet']);
            Route::put('sets/{id}', [SetsController::class, 'updateSet']);
            Route::delete('sets/{id}', [SetsController::class, 'deleteSet']);
        });
    });





