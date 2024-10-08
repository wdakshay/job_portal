<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Jab>
 */
class JabFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->jobTitle(),
            'user_id' => 1,
            'job_type_id' => rand(1, 3),
            'category_id' => rand(1, 5),
            'location' => fake()->city(),
            'vacancy' => rand(1, 10),
            'experience' => rand(1, 10),
            'description' => fake()->text(),
            'company_name' => fake()->company(),
        ];
    }
}
