<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class PassportTestingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (app()->environment('testing')) {
            Artisan::call('passport:client', [
                '--personal' => true,
                '--no-interaction' => true,
            ]);
            
        }
    }
}
