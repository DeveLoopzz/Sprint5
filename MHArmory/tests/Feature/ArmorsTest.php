<?php

namespace Tests\Feature;

use App\Models\Armors;
use App\Models\Skills;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ArmorsTest extends TestCase
{
    use DatabaseTransactions;

    protected $skills;

    public function setup(): void
    {
        parent::setup();
        $this->skills = Skills::factory()->count(5)->create();
    }

    public function test_create_armor()
    {
        Passport::actingAs($this->adminUser);
        $response = $this->post('api/armors', [
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
        Passport::actingAs($this->adminUser);
        $response = $this->post('api/armors', [
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
        Passport::actingAs($this->adminUser);
        $armor = Armors::create([
            'name' => 'Old Iron Chest',
            'type' => 'Chest'
        ]);

        $updatedSkills = [
            ['id' => $this->skills[0]->id, 'level' => 2],
            ['id' => $this->skills[1]->id, 'level' => 2],
            ['id' => $this->skills[2]->id, 'level' => 3]
        ];

        $response = $this->put('api/armors/' . $armor->id, [
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
        Passport::actingAs($this->adminUser);
        $response = $this->put('api/armors/19099', [
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
        Passport::actingAs($this->adminUser);
        $armor = Armors::create([
            'name' => 'Old Iron Chest',
            'type' => 'Helmet'
        ]);

        $updatedSkills = [
            ['id' => $this->skills[0]->id, 'level' => 2],
            ['id' => $this->skills[1]->id, 'level' => 2],
            ['id' => $this->skills[2]->id, 'level' => 3]
        ];

        $response = $this->put('api/armors/' . $armor->id, [
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
        Passport::actingAs($this->adminUser);
        $armor = Armors::create([
            'name' => 'Old Iron Chest',
            'type' => 'Helmet'
        ]);
        $response = $this->delete("api/armors/{$armor->id}");
        $response->assertStatus(200)
                 ->assertJson([
                    'message' => 'Armor Deleted Successfully'
                 ]);
        $this->assertDatabaseMissing('armors', ['id' => $armor->id]);
    }

    public function test_armor_not_found_to_delete() 
    {
        Passport::actingAs($this->adminUser);
        $response = $this->delete("api/armors/123412341");
        $response->assertStatus(404);
    }

    public function test_read_armor()
    {
        Passport::actingAs($this->adminUser);
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