<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use DatabaseTransactions;

    public function setup() : void 
    {
        parent::setup();
    }

    public function test_users_roles()
    {
        $this->assertTrue($this->adminUser->hasRole('admin'));
        $this->assertTrue($this->user->hasRole('hunter'));

    }


    public function test_user_register() 
    {
        $response = $this->post('api/register', [
            'name' => 'test',
            'email' => 'email@test.com',
            'password' => 'testing'
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('users' , [
            'email' => 'email@test.com'
        ]);

        $user = User::where('email', 'email@test.com')->first();
        $this->assertTrue($user->hasRole('hunter'));

    }

    public function test_user_invalid_register() 
    {
        $invalidData = $this->postJson('api/register',[
            'name' => 'test',
            'email' => '',
            'password' => '123'
        ]);
        $invalidData->assertStatus(422)
                    ->assertJsonValidationErrors([
            'email',
            'password',
        ]);
    }

    public function test_user_can_login() 
    {
        $user = $this->user;
        $response = $this->post('api/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);
        $response->assertStatus(200)
                 ->assertJsonStructure([
            'token',
            'message'
        ])
                 ->assertJson([
            'message' => 'Logged in Successfuly'
        ]);
    }

    public function  test_user_cant_login() 
    {
        $invalidData = $this->post('api/login', [
            'email' => 'invalidemail@notfound.com',
            'password' => '1234567'
        ]);
        $invalidData->assertStatus(401)
                    ->assertJson([
            'message' => 'Invalid credentials'
        ]);
    }

    public function test_user_can_logout() 
    {
        $user = $this->user;
        $response = $this->post('api/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $token = $response->json('token');

        $responseLogout = $this->post('api/logout',[], [
            'Authorization' => 'Bearer ' . $token
        ]);

        
        $response->assertStatus(200);
        $responseLogout->assertStatus(200)
                       ->assertJson([
                        'message' => 'Logged out Successfully'
                       ]);
    }

}
