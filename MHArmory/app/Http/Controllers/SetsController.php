<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSetsRequest;
use App\Http\Requests\UpdateSetsRequest;
use App\Models\Sets;
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
            $newArmors = $data['armors'];
            DB::table('sets_have_armors')->where('id_sets', $set->id)->delete();

            foreach ($newArmors as $armorId) {
                DB::table('sets_have_armors')->insert([
                    'id_sets' => $set->id,
                    'id_armors' => $armorId
                ]);
            }
        });
        return response()->json([
            'message' => 'Set Updated Successfully',
        ],200);
    }
    public function deleteSet($id)
    {
        $set = Sets::FindOrFail($id);

        DB::transaction(function() use($set) {
            DB::table('sets_have_armors')->where('id_sets', $set->id)->delete();
            $set->delete();
        });

        return response()->json([
            'message' => 'Set Deleted Successfully',
        ],200);
    }
    public function readSet()
    {
        $sets = Sets::with('armors')->get();
        return response()->json([
            'data' => $sets,
            'message' => 'Set List'
        ], 200); 
    }
}
