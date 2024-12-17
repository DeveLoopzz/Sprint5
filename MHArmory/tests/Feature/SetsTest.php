<?php

namespace Tests\Feature;

use App\Models\Armors;
use App\Models\Sets;
use App\Models\Skills;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SetsTest extends TestCase
{
    use DatabaseTransactions;
    protected $armors;
    protected $skills;
    protected $set;

    function setup():void 
    {
        parent::setup();
        Artisan::call('migrate');
        Artisan::call('db:seed');
        Artisan::call('passport:client', [
            '--name' => 'ClientTest',
            '--no-interaction' => true,
            '--personal' => true,
        ]);

        $this->armors = Armors::factory()->count(5)->create();
        $this->skills = Skills::factory()->count(5)->create();

        foreach($this->armors as $armor) {
            $armor->skills()->attach($this->skills[0]->id, ['level' => 1]);
        }
        $armorsWithSkills = $this->armors->pluck('id')->toArray();

        DB::transaction(function() use($armorsWithSkills){
            $this->set = Sets::create([
                'name' => 'Alatreon Set'
            ]);
            foreach($armorsWithSkills as $armors){
                DB::table('sets_have_armors')->insert([
                    'id_sets' => $this->set->id,
                    'id_armors' => $armors,
                ]);
            }
        });

    }

    public function test_create_set()
    {
        $response = $this->post('api/sets/create', [
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
        $response = $this->post('api/sets/create', [
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
        $set = $this->set;
        $updatedData = [
            'name' => 'Fatalis Set',
            'armors' => [$this->armors[3]->id,
            $this->armors[4]->id,
            $this->armors[0]->id,
            $this->armors[1]->id,
            $this->armors[2]->id]
        ];
        $response = $this->put("api/sets/update/{$set->id}", $updatedData);

        $response->assertStatus(200);
        
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
        $set = $this->set;
        $updatedData = [
            'name' => 'Fatalis Set',
            'armors' => [$this->armors[3]->id,
            $this->armors[4]->id,
            $this->armors[0]->id,
            $this->armors[1]->id,
            ]
        ];

        $response = $this->put("api/sets/update/{$set->id}", $updatedData);

        $response->assertStatus(422);
    }
}
