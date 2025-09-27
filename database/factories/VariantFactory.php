<?php

namespace Database\Factories;

use App\Models\Literature;
use App\Models\Variant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

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
            'language' => fake()->randomElement(Variant::LANGUAGES),
            'url' => Storage::putFile('', UploadedFile::fake()->create('test.pdf', 100)),
            'literature_id' => Literature::factory(),
        ];
    }
}
