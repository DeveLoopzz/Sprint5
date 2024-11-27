<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class AuthTester extends TestCase
{
    use RefreshDatabase;
    protected $user;
    protected $adminUser;

    public function setup() : void 
    {
        parent::setUp();

        Artisan::call('migrate');

        Artisan::call('db:seed');

        Artisan::call('passport:client', [
            '--name' => 'ClientTest',
            '--no-interaction' => true,
            '--personal' => true,
        ]);

        $this->user = User::create([
            'name' => 'test',
            'email' => 'test@test.com',
            'password' => bcrypt('password')
        ]);
    }


    public function userRegister() 
    {
        $response = $this->post('/users/register', [
            'name' => 'test',
            'email' => 'email@test.com',
            'password' => 'testing'
        ]);

        $response->assertStatus(200);

        $response->assertDatabaseHas('users' , [
            'email' => 'email@test.com'
        ]);
    }

    public function user_can_login() 
    {
        $user = $this->user;

        $response = $this->post('/users/login', [
            'email' => $user->email,
            'password' => $user->password
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure(['token']);

        

    }
}
