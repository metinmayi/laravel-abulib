<?php

namespace Tests\Feature;

use App\Models\Litterature;
use App\Models\LitteratureVariant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Testing\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Exceptions;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\TestResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Tests\TestCase;
use TypeError;

class LitteratureVariantTest extends TestCase
{
    use RefreshDatabase;

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
        [$res, $variant] = $this->uploadVariantWithoutErrors();

        $response = $this->get('/litteratureVariant/' . $variant->id);
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
        $response = $this->uploadLitteratureVariant(-1, $file);

        $response->assertSessionHas(['Error' => 'Something went wrong. Contact your son.']);
        $response->assertStatus(302);
    }

    /**
     * Test uploading a litterature variant
     */
    public function test_upload_litterature_variant(): void
    {
        $this->uploadVariantWithoutErrors();
    }

    /**
     * Test uploading a litterature variant with an existing language yields error
     */
    public function test_upload_variant_with_existing_language_yields_error(): void
    {
        $litterature = Litterature::factory()->create();
        $file = UploadedFile::fake()->create('test.pdf', 100);
        $language = fake()->languageCode();

        $this->uploadLitteratureVariant($litterature->id, $file, lang: $language);
        $response = $this->uploadLitteratureVariant($litterature->id, $file, lang: $language);

        $response->assertSessionHas(['Error' => 'Something went wrong. Contact your son.']);
        $response->assertStatus(302);
    }

    /**
     * Test deleting a variant
     */
    public function test_delete_variant_removes_file_and_entry(): void
    {
        $litterature = Litterature::factory()->create();
        $file = UploadedFile::fake()->create('test.pdf', 100);
        [$response] = $this->uploadVariantWithoutErrors($litterature->id, $file);

        $response = $this->delete(route('variant.delete', ['id' => $litterature->id]));
        $response->assertStatus(201);
        $this->assertFalse(Storage::disk()->exists($file->hashName()));
        $this->assertCount(0, LitteratureVariant::all());
    }

    /**
     * Test deleting a variant validation
     */
    public function test_validation_errors_when_deleting_variant(): void
    {
        Exceptions::fake();
        $this->delete(route('variant.delete', ['id' => 'notNumeric']));
        Exceptions::assertReported(TypeError::class);
    }

    /**
     * Test deleting a variant validation
     */
    public function test_provide_error_if_deleting_non_existing_variant(): void
    {
        $response = $this->delete(route('variant.delete', ['id' => 55]));
        $response->assertSessionHas(['Error' => 'Something went wrong. Contact your son.']);
        $response->assertStatus(302);
    }

    /**
     * Test deleting a variant gives error message if cannot delete related file
     */
    public function test_provide_error_if_cannot_delete_file_when_deleting_variant(): void
    {
        $litterature = Litterature::factory()->create();
        $file = UploadedFile::fake()->create('test.pdf', 100);
        $this->uploadVariantWithoutErrors($litterature->id, $file);

        Storage::shouldReceive('delete')
            ->once()
            ->andReturn(false);
        $response = $this->delete(route('variant.delete', ['id' => $litterature->id]));
        $response->assertStatus(302);
        $response->assertSessionHas(['Error' => 'Something went wrong. Contact your son.']);
        $this->assertCount(1, LitteratureVariant::all());
    }

    /**
     * Test that deleting a variant doesn't delete the related literature.
     */
    public function test_deleting_variant_does_not_delete_literature(): void
    {
        $litterature = Litterature::factory()->createOne();
        $this->uploadVariantWithoutErrors(litteratureId: $litterature->id);

        $file = UploadedFile::fake()->create('test.pdf', 100);
        [$rez, $variant] = $this->uploadVariantWithoutErrors(litteratureId:$litterature->id, file: $file, lang: 'kurdish');
        $this->assertCount(1, Litterature::all());

        $response = $this->delete(route('variant.delete', ['id' => $variant->id]));
        $response->assertStatus(201);
        $this->assertFalse(Storage::disk()->exists($file->hashName()));
        $this->assertCount(1, LitteratureVariant::all());
        $this->assertCount(1, Litterature::all());
    }

    /**
     */
    public function test_deleting_last_variant_deletes_literature(): void
    {
        $file = UploadedFile::fake()->create('test.pdf', 100);
        [$res, $variant] = $this->uploadVariantWithoutErrors(file: $file);
        $this->assertCount(1, Litterature::all());

        $response = $this->delete(route('variant.delete', ['id' => $variant->id]));
        $response->assertStatus(201);
        $this->assertFalse(Storage::disk()->exists($file->hashName()));
        $this->assertCount(0, LitteratureVariant::all());
        $this->assertCount(0, Litterature::all());
    }

    /**
     * Helper for uploading a litterature variant.
     * @return array{TestResponse<Response>, LitteratureVariant}
     */
    private function uploadVariantWithoutErrors(?int $litteratureId = null, ?File $file = null, ?string $lang = null): array
    {
        $litteratureId = $litteratureId ?? Litterature::factory()->createOne()->id;
        $file = $file ?? UploadedFile::fake()->create('test.pdf', 100);
        $language = $lang ?? fake()->languageCode();
        $title = fake()->title();
        $description = fake()->sentence();

        $count = count(LitteratureVariant::all());

        $response = $this->uploadLitteratureVariant($litteratureId, $file, $title, $description, $language);
        $response->assertStatus(201);
        $this->assertCount($count + 1, LitteratureVariant::all());

        $this->assertTrue(Storage::disk()->exists($file->hashName()));

        $variant = LitteratureVariant::query()->where('litterature_id', '=', $litteratureId)->where('language', '=', $language)->first();
        $this->assertNotNull($variant);
        $this->assertEquals($file->hashName(), $variant->url);
        $this->assertEquals($title, $variant->title);
        $this->assertEquals($description, $variant->description);
        $this->assertEquals($language, $variant->language);
        $this->assertEquals($litteratureId, $variant->litterature_id);

        return [$response, $variant];
    }

    /**
     * Helper for uploading a litterature variant.
     * @return TestResponse<Response>
     */
    private function uploadLitteratureVariant(int $litteratureId, File $file, ?string $title = null, ?string $description = null, ?string $lang = null): TestResponse
    {
        Storage::fake();

        $language = $lang ?? fake()->languageCode();
        $response = $this->post('/litteratureVariant', [
            'title' => $title ?? fake()->title(),
            'description' => $description ?? fake()->sentence(),
            'file' => $file,
            'litterature_id' => $litteratureId,
            'language' => $language
        ]);

        return $response;
    }
}
