<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class TokenTest extends TestCase
{

    use DatabaseTransactions;

    protected $token;
    protected $adminToken;

    public function setUp(): void
    {
        parent::setup();
        Artisan::call('migrate:fresh');
        Artisan::call('db:seed');
        Artisan::call('passport:keys');
        Artisan::call('passport:client', [
            '--name' => 'ClientTest',
            '--no-interaction' => true,
            '--personal' => true,
        ]);
        $this->user = User::factory()->asHunter()->create();
        $this->adminUser = User::factory()->asAdmin()->create();

    }

    // public function test_token() 
    // {
    //     $registerUser = $this->post('api/users/login', [
    //         'email' => 'test@example.com',
    //         'password' => '12345678'
    //     ]);

    //     $loginUser = $this->post('api/users/login', [
    //         'email' => $this->user->email,
    //         'password' => $this->user->password
    //     ]);

    //     dd($registerUser['token']);
    // }
}
