<?php

namespace Database\Factories;

use App\Enums\Breed;
use App\Enums\Sex;
use App\Enums\Type;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pet>
 */
class PetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'type' => fake()->randomElement(Type::cases()),
            'breed' => fake()->randomElement(Breed::cases()),
            'date_of_birth' => fake()->date(),
            'sex' => fake()->randomElement(Sex::cases()),
            'is_dangerous_animal' => fake()->boolean(),
        ];
    }
}
