<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSetsRequest;
use App\Http\Requests\UpdateSetsRequest;
use App\Models\Sets;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SetsController extends Controller
{
    public function createSet(StoreSetsRequest $request)
    {
        $data = $request->validated();
        DB::transaction(function() use($data) {
            $set = Sets::create([
                'name' => $data['name'],
            ]);
            foreach($data['armors'] as $armorId){
                DB::table('sets_have_armors')->insert([
                    'id_sets' => $set->id,
                    'id_armors' => $armorId,
                ]);
            }
        });
        return response()->json([
            'message' => 'Set Created Successfully'
        ], 200);
    }

    public function updateSet(UpdateSetsRequest $request, $id)
    {
        $data = $request->validated();

        DB::transaction(function() use ($data, $id) {
            $set = Sets::findOrFail($id);
            if(isset($data['name'])) {
                $set->update(['name' => $data['name']]);
            }

            $currentArmors = DB::table('sets_have_armors')->where('id_sets', $set->id)->pluck('id_armors')->toArray();
            $newArmors = $data['armors'];

            $armorsToDelete = array_diff($currentArmors, $newArmors);
            $armorsToUpdate = array_diff($newArmors, $currentArmors);

            DB::table('sets_have_armors')->where('id_sets', $set->id)->whereIn('id_armors', $armorsToDelete)->delete();

            foreach ($armorsToUpdate as $armorId) {
                DB::table('sets_have_armors')->insert([
                    'id_sets' => $set->id,
                    'id_armors' => $armorId
                ]);
            }
        });
    }

    public function deleteSet(){}

    public function readSet(){}
}
