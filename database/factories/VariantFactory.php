<?php

namespace Database\Factories;

use App\Models\Literature;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Variant>
 */
class VariantFactory extends Factory
{

    protected $model = \App\Models\Variant::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(1),
            'description' => fake()->paragraph(2),
            'language' => fake()->randomElement(Literature::LANGUAGES),
            'url' => fake()->url(),
            'literature_id' => Literature::factory(),
        ];
    }
}
