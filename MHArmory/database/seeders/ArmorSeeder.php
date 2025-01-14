<?php

namespace Database\Seeders;

use App\Models\Armors;
use App\Models\Skills;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ArmorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $skills = Skills::all();


        Armors::factory()->count(5)->create()->each(function ($armor) use ($skills) {
            $armor->skills()->attach($skills->random(3), [
                'level' => rand(1, 3),
            ]);
        });
    }
}
