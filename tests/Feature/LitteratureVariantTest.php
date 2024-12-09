<?php

namespace Tests\Feature;

use App\Models\LitteratureVariant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Tests\TestCase;

class LitteratureVariantTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_litterature_binary_404_if_not_found(): void
    {
        $this->assertCount(0, LitteratureVariant::all());
        $response = $this->get('/litteratureVariant/1');
        $response->assertStatus(404);
    }

    public function test_get_litterature_binary(): void
    {
        $litteratureVariant = LitteratureVariant::factory()->create();
        $response = $this->get('/litteratureVariant/' . $litteratureVariant->id);
        $response->assertStatus(200);
        $this->assertInstanceOf(BinaryFileResponse::class, $response->baseResponse);
    }
}
