<?php

namespace Tests\Feature;

use App\Models\Armors;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ArmorsTest extends TestCase
{
    use DatabaseTransactions;


    public function setup(): void
    {
        parent::setup();
    }

    public function test_create_armor()
    {
        $this->actingAs($this->adminUser);
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
        $this->actingAs($this->adminUser);
        $response = $this->post('api/armors/create', [
            'name' => 'Iron Helmet',
            'type' => 'Pies',
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
        $this->actingAs($this->adminUser);
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
        $this->actingAs($this->adminUser);
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
        $this->actingAs($this->adminUser);
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

    public function test_armor_delete() 
    {
        $this->actingAs($this->adminUser);
        $armor = Armors::create([
            'name' => 'Old Iron Chest',
            'type' => 'Helmet'
        ]);
        $response = $this->delete("api/armors/delete/{$armor->id}");
        $response->assertStatus(200)
                 ->assertJson([
                    'message' => 'Armor Deleted Successfully'
                 ]);
        $this->assertDatabaseMissing('armors', ['id' => $armor->id]);
    }

    public function test_armor_not_found_to_delete() 
    {
        $this->actingAs($this->adminUser);
        $response = $this->delete("api/armors/delete/123412341");
        $response->assertStatus(404);
    }

    public function test_read_armor()
    {
        $this->actingAs($this->user);
        $response = $this->get('api/armors');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'type',
                    'skills'
                ]
            ]
        ]);
    }
}