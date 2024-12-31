<?php

namespace Tests\Feature;

use App\Models\Literature;
use App\Models\Variant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReaderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that reader controller returns the correct view.
     */
    public function test_reader_renders_correct_view(): void
    {
        $literature = Literature::factory()->createOne();
        $variants = Variant::factory()->set('literature_id', $literature->id)->count(2)->create();
        $firstVariant = $variants->firstOrFail();
        $langauges = $variants->pluck('language')->toArray();

        $this->get(route('read.index', ['variantId' => $firstVariant->id]))
            ->assertStatus(200)
            ->assertViewIs('read.index')
            ->assertSeeInOrder([
                $firstVariant->title,
                $firstVariant->description,
                implode(', ', $langauges),
                $literature->category,
            ]);
    }
}
