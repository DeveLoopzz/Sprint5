<?php

namespace Tests\Feature;

use App\Models\Armors;
use App\Models\Sets;
use App\Models\Skills;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\Passport;
use Tests\TestCase;

class SetsTest extends TestCase
{
    use DatabaseTransactions;

    function setup():void 
    {
        parent::setup();
    }

    public function test_create_set()
    {
        Passport::actingAs($this->user);
        $response = $this->post('api/sets', [
            'name' => 'Rathalos Set',
            'armors' => $this->armors->pluck('id')->toArray()
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                    'message' => 'Set Created Successfully'
                 ]);

        $this->assertDatabaseHas('sets_have_armors', [
            'id_armors' => $this->armors[0]->id
        ]);
    }

    public function test_invalid_set()
    {
        Passport::actingAs($this->user);
        $response = $this->post('api/sets', [
            'name' => 'Rathalos Set',
            'armors' => ''
        ]);

        $response->assertStatus(422)
                 ->assertJson([
                    'message' => 'Validation Failed'
                 ]);
    }

    public function test_update_set() 
    {
        Passport::actingAs($this->user);
        $set = $this->set;
        $updatedData = [
            'name' => 'Fatalis Set',
            'armors' => [$this->armors[3]->id,
            $this->armors[4]->id,
            $this->armors[0]->id,
            $this->armors[1]->id,
            $this->armors[2]->id]
        ];
        $response = $this->put("api/sets/{$set->id}", $updatedData);

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Set Updated Successfully'
        ]);
        
        $this->assertDatabaseHas('sets_have_armors', [
            'id_sets'   => $set->id,
            'id_armors' => $this->armors[1]->id
        ]);
    
        $this->assertEquals(
            5,
            DB::table('sets_have_armors')->where('id_sets', $set->id)->count()
        );
    }

    public function test_update_few_armors()
    {
        Passport::actingAs($this->user);
        $set = $this->set;
        $updatedData = [
            'name' => 'Fatalis Set',
            'armors' => [$this->armors[3]->id,
            $this->armors[4]->id,
            $this->armors[0]->id,
            $this->armors[1]->id,
            ]
        ];

        $response = $this->put("api/sets/{$set->id}", $updatedData);

        $response->assertStatus(422);
    }


    public function test_delete_set() 
    {
        Passport::actingAs($this->user);
        $set = $this->set;
        $response = $this->delete("api/sets/{$set->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('sets', ['id' => $set->id]);
        $this->assertDatabaseMissing('sets_have_armors', ['id_sets' => $set->id]);
        $response->assertJson([
            'message' => 'Set Deleted Successfully'
        ]);
    }

    public function test_Delete_set_not_found()
    {
        Passport::actingAs($this->user);
        $set = 20000;
        $response = $this->delete("api/sets/{$set}");
        $response->assertStatus(404);
    }

    public function test_read_set() 
    {
        Passport::actingAs($this->user);
        $response = $this->get('api/sets');
        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Set List'
        ]);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'armors'
                ]
            ]
        ]);
    }

}
