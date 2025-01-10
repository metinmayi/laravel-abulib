<?php

namespace Database\Factories;

use App\Models\Variant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Literature>
 */
class LiteratureFactory extends Factory
{
    protected $model = \App\Models\Literature::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category' => fake()->randomElement($this->model::CATEGORIES),
        ];
    }

    /**
     * Create a Literature instance with variants.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withVariants(): self
    {
        return $this->afterCreating(function (\App\Models\Literature $literature) {
            foreach (\App\Models\Literature::LANGUAGES as $language) {
                Variant::factory()->create([
                    'language' => $language,
                    'literature_id' => $literature->id,
                ]);
            }
        });
    }
}
