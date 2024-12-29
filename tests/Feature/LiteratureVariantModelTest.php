<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LiteratureVariantModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test literature model to array
     */
    public function test_model_to_array(): void
    {
        $literature = \App\Models\LiteratureVariant::factory()->make();

        $this->assertEquals(['title', 'description', 'language', 'url', 'literature_id'], array_keys($literature->toArray()));
    }

    /**
     * Test retrieving literature
     */
    public function test_retrieving_literature(): void
    {
        $variant = \App\Models\LiteratureVariant::factory()->createOne();

        $this->assertNotNull($variant->literature);
        $this->assertEquals($variant->literature_id, $variant->literature->id);
    }
}
