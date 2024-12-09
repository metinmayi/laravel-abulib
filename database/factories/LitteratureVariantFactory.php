<?php

namespace Database\Factories;

use App\Models\Litterature;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LitteratureVariant>
 */
class LitteratureVariantFactory extends Factory
{

    protected $model = \App\Models\LitteratureVariant::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->title(),
            'description' => fake()->paragraph(1),
            'language' => fake()->languageCode(),
            'url' => fake()->url(),
            'litterature_id' => Litterature::factory(),
        ];
    }
}
