<?php

namespace Tests\Feature;

use App\Models\Literature;
use App\Models\Variant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ReaderControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that reader controller returns the correct view.
     */
    public function testControllerRendersCorrectView(): void
    {
        $literature = Literature::factory()->createOne();
        $variants = Variant::factory()->set('literature_id', $literature->id)->count(2)->create();
        $firstVariant = $variants->firstOrFail();
        $languages = $variants
            ->whereNotNull('url')
            ->pluck('language')->filter(fn ($language) => is_string($language))->map(fn ($language) => ucfirst($language))
            ->toArray();

        $this->get(route('read.index', ['variantId' => $firstVariant->id]))
            ->assertStatus(200)
            ->assertViewIs('read.index')
            ->assertSeeInOrder([
                ucfirst($literature->category),
                $firstVariant->title,
                $firstVariant->description,
                ...$languages,
            ]);
    }

    /**
     * Test that a 404 is returned if the literature variant is not found
     */
    public function testGetLiteratureBinary404IfNotFound(): void
    {
        $this->get(route('read.getLiteratureBinary', ['id' => -1]))
            ->assertStatus(404);
    }

    /**
     * Get literature binary
     */
    public function testGetLiteratureBinary(): void
    {
        $variant = Variant::factory()->createOne();

        $this->get(route('read.getLiteratureBinary', ['id' => $variant->id]))
            ->assertStatus(200)
            ->assertHeader('Content-Type', 'application/pdf')
            ->assertContent(Storage::get($variant->url ?? '') ?? '');
    }
}
