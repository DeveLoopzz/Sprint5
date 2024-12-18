<?php

namespace Database\Factories;

use App\Models\Armors;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ArmorsFactory extends Factory
{
    protected $model = Armors::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'type' => $this->faker->randomElement(['Helmet', 'Chest', 'Gloves', 'Faulds', 'Boots']),
        ];
    }
}
