<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArmorsRequest;
use App\Http\Requests\UpdateArmorsRequest;
use App\Models\Armors;
use App\Models\ArmorsHaveSkills;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArmorsController extends Controller
{
    public function createArmor(StoreArmorsRequest $request) 
    {   
        $data = $request->validated();
        DB::transaction(function () use ($data){
            $armor = Armors::create([
                'name' => $data['name'],
                'type' => $data['type']
            ]);
            foreach($data['skills'] as $skillData) {
                DB::table('armors_have_skills')->insert([
                    'id_armors' => $armor->id,
                    'id_skills' => $skillData['id'],
                    'level' => $skillData['level'] ?? 1
                ]);
            }
        }); 
        return response()->json([
            'message' => 'Armor Created Successfully',
        ], 200);
    }

    public function updateArmor(UpdateArmorsRequest $request, $id) 
    {
        $data = $request->validated();
        $armor = Armors::FindOrFail($id);

        DB::transaction(function() use ($data, $armor) 
        {
            $updateData = [];
            if (isset($data['name'])) {
                $updateData['name'] = $data['name'];
            }
            if (isset($data['type'])) {
                $updateData['type'] = $data['type'];
            }
            $armor->update($updateData);
    
            if(isset($data['skills'])) {
                DB::table('armors_have_skills')->where('id_armors', $armor->id)->delete();
                foreach($data['skills'] as $skillData) {
                    DB::table('armors_have_skills')->insert([
                        'id_armors' => $armor->id,
                        'id_skills' => $skillData['id'],
                        'level' => $skillData['level'] ?? 1
                    ]);
                }
            }
        });
        return response()->json([
            'message' => 'Armor Updated Successfully',
            'armor' => $armor
        ], 200);
    }

    public function deleteArmor() 
    {}

    public function readArmor() 
    {}

}
