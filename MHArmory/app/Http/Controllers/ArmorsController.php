<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArmorsRequest;
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

    public function updateArmor() 
    {}

    public function deleteArmor() 
    {}

    public function readArmor() 
    {}

}
