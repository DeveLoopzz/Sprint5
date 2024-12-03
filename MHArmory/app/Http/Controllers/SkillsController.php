<?php

namespace App\Http\Controllers;

use App\Models\Skills;
use Illuminate\Http\Request;

class SkillsController extends Controller
{
    public function createSkill(Request $request)
    {
        $request->validate([
            'name' => 'max:255|unique:skills|required',
            'effect' => 'unique:skills|required'
        ]);

        $skill = Skills::create([
            'name' => $request->name,
            'effect' =>$request->effect
        ]);

        return response()->json([
            'message' => 'Skill created successfully'
        ], 200);
    }

    public function updateSkill()
    {

    }

    public function deleteSkill()
    {

    }

    public function readSkills()
    {

    }
}
