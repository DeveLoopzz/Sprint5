<?php

namespace Tests\Feature;

use App\Models\Armors;
use App\Models\Skills;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class ArmorsTest extends TestCase
{
    use DatabaseTransactions;
    protected $skills;
    protected $armor;
    public function setup(): void
    {
        parent::setup();
        Artisan::call('migrate');
        Artisan::call('db:seed');
        Artisan::call('passport:client', [
            '--name' => 'ClientTest',
            '--no-interaction' => true,
            '--personal' => true,
        ]);

        $this->skills = Skills::factory()->count(3)->create();
    }

    public function test_create_armor()
    {
        $response = $this->post('api/armors/create', [
            'name' => 'Ironic Helmet',
            'type' => 'Helmet',
            'skills' => [
                ['id' => $this->skills[0]->id, 'level' => 1 ],
                ['id' => $this->skills[1]->id, 'level' => 2 ],
                ['id' => $this->skills[2]->id, 'level' => 3 ],
            ],
        ]);
        $response->assertStatus(200)
                 ->assertJson([
                    'message' => 'Armor Created Successfully'
                 ]);

    }

    public function test_invalid_armor_create()
    {
        $response = $this->post('api/armors/create', [
            'name' => 'Iron Helmet',
            'type' => 'Helmet',
            'skills' => [
                ['id' => $this->skills[0]->id, 'level' => 1 ],
                ['id' => $this->skills[1]->id, 'level' => 2 ],
                ['id' => $this->skills[2]->id, 'level' => 3 ],
            ],
        ]);

        $response->assertStatus(422)
        ->assertJson([
           'message' => 'Validation Failed'
        ]);
    }

    public function test_update_armor() 
    {
        $armor = Armors::create([
            'name' => 'Old Iron Chest',
            'type' => 'Chest'
        ]);

        $updatedSkills = [
            ['id' => $this->skills[0]->id, 'level' => 2],
            ['id' => $this->skills[1]->id, 'level' => 2],
            ['id' => $this->skills[2]->id, 'level' => 3]
        ];

        $response = $this->put('api/armors/update/' . $armor->id, [
            'name' => 'New Iron Armor',
            'type' => 'Helmet',
            'skills' => $updatedSkills
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                    'message' => 'Armor Updated Successfully'
                 ]);
        $this->assertEquals('New Iron Armor', $armor->fresh()->name);
        $this->assertEquals('Helmet', $armor->fresh()->type);
    }

    public function test_update_not_found_armor() 
    {
        $response = $this->put('api/armors/update/19099', [
            'name' => 'New Iron Armor',
            'type' => 'Helmet',
            'skills' => [
                ['id' => $this->skills[0]->id, 'level' => 2],
                ['id' => $this->skills[1]->id, 'level' => 2],
                ['id' => $this->skills[2]->id, 'level' => 3]
            ]
        ]);
        $response->assertStatus(404);
    }

    public function test_update_invalid_data() 
    {
        $armor = Armors::create([
            'name' => 'Old Iron Chest',
            'type' => 'Helmet'
        ]);

        $updatedSkills = [
            ['id' => $this->skills[0]->id, 'level' => 2],
            ['id' => $this->skills[1]->id, 'level' => 2],
            ['id' => $this->skills[2]->id, 'level' => 3]
        ];

        $response = $this->put('api/armors/update/' . $armor->id, [
            'name' => 'New Iron Armor',
            'type' => 'Dedal',
            'skills' => $updatedSkills
        ]);

        $response->assertStatus(422)
                 ->assertJson([
                    'message' => 'Validation Failed'
                 ]);
    }



}

