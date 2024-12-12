<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSetsRequest;
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

    public function updateSet(){}

    public function deleteSet(){}

    public function readSet(){}
}
