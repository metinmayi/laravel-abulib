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
        $title = fake()->title();
        $description = fake()->sentence();
        $language = fake()->languageCode();

        $response = $this->post('/litteratureVariant', [
            'title' => $title,
            'description' => $description,
            'file' => $file,
            'litterature_id' => -1,
            'language' => $language
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

        $title = fake()->title();
        $description = fake()->sentence();
        $language = fake()->languageCode();
        $response = $this->post('/litteratureVariant', [
            'title' => $title,
            'description' => $description,
            'file' => $file,
            'litterature_id' => $litterature->id,
            'language' => $language
        ]);

        $response->assertStatus(201);
        $variants = LitteratureVariant::all();
        $this->assertCount(1, $variants);
        $variant = $variants->first();
        $this->assertNotNull($variant);
        $this->assertEquals($title, $variant->title);
        $this->assertEquals($description, $variant->description);
        $this->assertEquals($language, $variant->language);
        $this->assertEquals($file->hashName(), $variant->url);
        $this->assertEquals($litterature->id, $variant->litterature_id);
        $this->assertTrue(Storage::disk(self::DISK_STORE)->exists($file->hashName()));
    }

    /**
     * Test uploading a litterature variant with an existing language yields error
     */
    public function test_upload_variant_with_existing_language_yields_error(): void
    {
        Storage::fake(self::DISK_STORE);
        $litterature = Litterature::factory()->create();
        $file = UploadedFile::fake()->create('test.pdf', 100);

        $language = fake()->languageCode();
        $this->post('/litteratureVariant', [
            'title' => fake()->title(),
            'description' => fake()->sentence(),
            'file' => $file,
            'litterature_id' => $litterature->id,
            'language' => $language
        ]);

        $response = $this->post('/litteratureVariant', [
            'title' => fake()->title(),
            'description' => fake()->sentence(),
            'file' => $file,
            'litterature_id' => $litterature->id,
            'language' => $language
        ]);

        $response->assertSessionHas(['Error' => 'Something went wrong. Contact your son.']);
        $response->assertStatus(302);
    }
}
