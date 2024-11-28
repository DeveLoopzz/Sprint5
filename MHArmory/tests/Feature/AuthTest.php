<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class AuthTest extends TestCase
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


    public function test_user_register() 
    {
        $response = $this->post('api/users/register', [
            'name' => 'test',
            'email' => 'email@test.com',
            'password' => 'testing'
        ]);

        $invalidData = $this->postJson('api/users/register',[
            'name' => 'test',
            'email' => '',
            'password' => '123'
        ]);

        $invalidData->assertStatus(422);

        $invalidData->assertJsonValidationErrors([
            'email',
            'password',
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('users' , [
            'email' => 'email@test.com'
        ]);
    }

    public function test_user_can_login() 
    {
        $user = $this->user;

        $response = $this->post('api/users/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $response->assertStatus(200);


    }
}
