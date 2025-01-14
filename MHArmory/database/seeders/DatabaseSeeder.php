<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $userAdmin = User::factory()->create([
            'name' => 'TestUser',
            'email' => 'test@example.com',
            'password' => '12345678'
        ]);

        $userAdmin->assignRole('admin');

        $user = User::factory()->create([
            'name' => 'User',
            'email' => 'test@hunter.com',
            'password' => '12345678'
        ]);
        
        $user->assignRole('hunter');
    }
}
