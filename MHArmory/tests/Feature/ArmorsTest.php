<?php

namespace Tests\Feature;

use App\Models\Skills;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
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
            'name' => 'Iron Helmet',
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

        dd($response);
    }
}
