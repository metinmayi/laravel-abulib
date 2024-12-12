<?php

namespace Tests\Feature;

use App\Models\Litterature;
use App\Models\LitteratureVariant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Tests\TestCase;

class LitteratureVariantTest extends TestCase
{
    use RefreshDatabase;

    protected const DISK_STORE = 'litteratures';

    protected string $variantTitle = 'Test Title';
    protected string $variantDescription = 'Test Description';
    protected string $variantLanguage = 'Test Language';

    /**
     * Test that a 404 is returned if the litterature variant is not found
     */
    public function test_get_litterature_binary_404_if_not_found(): void
    {
        $this->assertCount(0, LitteratureVariant::all());
        $response = $this->get('/litteratureVariant/1');
        $response->assertStatus(404);
    }

    /**
     * Get litterature binary
     */
    public function test_get_litterature_binary(): void
    {
        $litteratureVariant = LitteratureVariant::factory()->create();
        $response = $this->get('/litteratureVariant/' . $litteratureVariant->id);
        $response->assertStatus(200);
        $this->assertInstanceOf(BinaryFileResponse::class, $response->baseResponse);
    }

    /**
     * Test validation for uploading a variant
     */
    public function test_upload_litterature_variant_validation_errors(): void
    {
        $this->followingRedirects()
            ->post('/litteratureVariant')
            ->assertSessionHasErrors(['title','description', 'file', 'litterature_id']);
    }

    /**
     * Test uploading a litterature variant without an existing litterature yields error
     */
    public function test_upload_litterature_variant_without_existing_litterature(): void
    {
        $file = UploadedFile::fake()->create('test.pdf', 100);
        $response = $this->post('/litteratureVariant', [
            'title' => $this->variantTitle,
            'description' => $this->variantDescription,
            'file' => $file,
            'litterature_id' => 0,
            'language' => $this->variantLanguage
        ]);

        $response->assertSessionHas(['Error' => 'Something went wrong. Contact your son.']);
        $response->assertStatus(302);
    }

    /**
     * Test uploading a litterature variant
     */
    public function test_upload_litterature_variant(): void
    {
        Storage::fake(self::DISK_STORE);
        $litterature = Litterature::factory()->create();
        $file = UploadedFile::fake()->create('test.pdf', 100);
        $response = $this->post('/litteratureVariant', [
            'title' => $this->variantTitle,
            'description' => $this->variantDescription,
            'file' => $file,
            'litterature_id' => $litterature->id,
            'language' => $this->variantLanguage
        ]);

        $response->assertStatus(201);
        $variants = LitteratureVariant::all();
        $this->assertCount(1, $variants);
        $variant = $variants->first();
        $this->assertNotNull($variant);
        $this->assertEquals($this->variantTitle, $variant->title);
        $this->assertEquals($this->variantDescription, $variant->description);
        $this->assertEquals($this->variantLanguage, $variant->language);
        $this->assertEquals($file->hashName(), $variant->url);
        $this->assertEquals($litterature->id, $variant->litterature_id);
        $this->assertTrue(Storage::disk(self::DISK_STORE)->exists($file->hashName()));
    }

    /**
     * Helper method to get the expected file name
     */
    protected function getExpectedFileName(): string
    {
        return "{$this->variantTitle}-{$this->variantLanguage}.pdf";
    }
}
