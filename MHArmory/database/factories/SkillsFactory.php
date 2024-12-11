<?php

namespace Database\Factories;

use App\Models\Skills;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Skills>
 */
class SkillsFactory extends Factory
{
    protected $model = Skills::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'effect' => json_encode([
                $this->faker->randomNumber(5) => $this->faker->word,
                $this->faker->randomNumber(5) => $this->faker->word,
            ])
        ];
    }
}
