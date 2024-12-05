<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSkillRequest;
use App\Http\Requests\UpdateSkillRequest;
use App\Models\Skills;
use Illuminate\Http\Request;

class SkillsController extends Controller
{
    public function createSkill(StoreSkillRequest $request)
    {
        $data = $request->validated();
        Skills::create($data);
        if(!$data){
            return response()->json([
                'message' => 'Incorrect value'
            ], 302);
        }
        return response()->json([
            'message' => 'Skill created successfully'
        ], 200);
    }

    public function updateSkill(UpdateSkillRequest $request, $id)
    {
        $skill = Skills::FindOrFail($id);
        $data = $request->validated();
        $skill->update([
            'name' => $data['name'] ?? $skill->name,
            'effect' => $data['effect'] ?? $skill->effect
        ]);

        return response()->json([
            'message' => 'Skill updated successfully'
        ],200);
    }

    public function deleteSkill()
    {

    }

    public function readSkills()
    {

    }
}
