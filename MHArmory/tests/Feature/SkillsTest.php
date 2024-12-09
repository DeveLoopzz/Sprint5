<?php

namespace Tests\Feature;

use App\Models\Skills;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class SkillsTest extends TestCase
{
    use DatabaseTransactions;
    protected $skill;

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
        $this->skill = Skills::create([
            'name' => 'defense bonus',
            'effect' => json_encode(["1" => "defense + 5",
             "2" =>"defense +10"])
        ]);
    }

    public function test_skills_create()
    {
        $response = $this->post('api/skills/create', [
            'name' => "attack bonuses",
            'effect' => json_encode(["1" => "attack +2",
             "2" =>"attack +10"])
        ]);

        $response->assertStatus(200)
                 ->assertjson([
                    'message' => 'Skill created successfully'
                 ]);

        $this->assertDatabaseHas('skills', [
            'name' => 'attack bonuses'
        ]);
    }

    public function test_skills_create_invalid_json() 
    {
        $response = $this->post('api/skills/create', [
            'name' => 'attack bonus',
            'effect' => 'attack +5'
        ]);

        $response->assertStatus(422)
                 ->assertJson([
                    'message' => 'Validation Failed'
                 ]);
    }

    public function test_skills_create_repeated_value()
    {
        $response = $this->post('api/skills/create', [
            'name' => 'defense bonus',
            'effect' => '{"1" => "defense + 5",
             "2" =>"defense +10"}'
        ]);

        $response->assertStatus(422)
                 ->assertJson([
                    'message' => 'Validation Failed'
                 ]);
    }

    public function test_skills_update() 
    {
        $skill = $this->skill;
        $response = $this->putJson("api/skills/update/{$skill->id}", [
            'name' => 'new defense bonus'
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                    'message' => 'Skill updated successfully'
                 ]);
        $this->assertDatabaseHas('skills', [
            'name' => 'new defense bonus'
        ]);
    }

    public function test_skills_update_invalid_data() 
    {
        $skill = $this->skill;
        $response = $this->putJson("api/skills/update/{$skill->id}", [
            'name' => 1,
            'effect' => ''
        ]);

        $response->assertStatus(422)
                 ->assertJson([
                    'message' => 'Validation Failed'
                 ]);
    }

    public function test_skill_can_be_deleted()
    {
        $skill = $this->skill;
        $response = $this->delete("api/skills/delete/{$skill->id}");
        $response->assertStatus(200);
        $this->assertDatabaseMissing('skills', [
                    'id' => $skill->id
                 ]);
    }

    public function test_skill_cant_be_deleted()
    {
        $response = $this->delete("api/skills/delete/12324569");
        $response->assertStatus(404)
                 ->assertJson([
                    'message' => 'Not Found'
                 ]);
    }

    public function test_read_skills() 
    {   
        $response = $this->get("api/skills");
        $response->assertStatus(200)
                 ->assertJsonStructure([
                    'data' => [
                        '*' => [
                            'id',
                            'name',
                            'effect'
                        ]
                    ]
                ]);
    }

}
