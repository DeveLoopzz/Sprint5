<?php

namespace Tests\Feature;

use App\Models\Armors;
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
    }

    public function test_create_set()
    {
        $response = $this->post('api/sets/create', [
            'name' => 'Rathalos Set',
            'armors' => $this->armors
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                    'message' => 'Set Created Successfully'
                 ]);

        $this->assertDatabaseHas('sets_have_armors', [
            'id_armors' => $this->armors[0]->id
        ]);
    }
}
