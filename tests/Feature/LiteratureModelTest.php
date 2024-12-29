<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LiteratureModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test literature model to array
     */
    public function test_literature_model_to_array(): void
    {
        $literature = \App\Models\Literature::factory()->make();

        $this->assertEquals(['category'], array_keys($literature->toArray()));
    }

    /**
     * Test retrieving literature variants
     */
    public function test_retrieving_literature_variants(): void
    {
        $literature = \App\Models\Literature::factory()->createOne();
        $variant = \App\Models\LiteratureVariant::factory()->recycle($literature)->createOne();

        $this->assertCount(1, $literature->variants);
        $this->assertEquals($variant->id, $literature->variants->firstOrFail()->id);
    }
}
