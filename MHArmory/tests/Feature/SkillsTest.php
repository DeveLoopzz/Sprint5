<?php

namespace Tests\Feature;

use App\Models\Skills;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class SkillsTest extends TestCase
{
    use RefreshDatabase;
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
            'name' => 'attack bonus',
            'effect' => json_encode(['1' => 'attack + 5',
             '2' =>'attack +10'])
        ]);
    }

    public function test_skills_create()
    {
        $response = $this->post('api/skills/create', [
            'name' => 'attack bonus',
            'effect' => json_encode(['1' => 'attack +5',
             '2' =>'attack +10'])
        ]);

        $response->assertStatus(200)
                 ->assertjson([
                    'message' => 'Skill created successfully'
                 ]);

        $this->assertDatabaseHas('skills', [
            'name' => 'attack bonus'
        ]);
    }

}
