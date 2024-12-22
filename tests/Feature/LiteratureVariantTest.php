<?php

namespace Tests\Feature;

use App\Models\Literature;
use App\Models\LiteratureVariant;
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

class LiteratureVariantTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a 404 is returned if the literature variant is not found
     */
    public function test_get_literature_binary_404_if_not_found(): void
    {
        $this->assertCount(0, LiteratureVariant::all());
        $response = $this->get('/literatureVariant/1');
        $response->assertStatus(404);
    }

    /**
     * Get literature binary
     */
    public function test_get_literature_binary(): void
    {
        [$res, $variant] = $this->uploadVariantWithoutErrors();

        $response = $this->get('/literatureVariant/' . $variant->id);
        $response->assertStatus(200);
        $this->assertInstanceOf(BinaryFileResponse::class, $response->baseResponse);
    }

    /**
     * Test validation for uploading a variant
     */
    public function test_upload_literature_variant_validation_errors(): void
    {
            $res = $this->post('/literatureVariant/upload/0');
            $res->assertSessionHasErrors(['title','description', 'file']);
            $res->assertStatus(302);
    }

    /**
     * Test uploading a literature variant without an existing literature yields error
     */
    public function test_upload_literature_variant_without_existing_literature(): void
    {
        $file = UploadedFile::fake()->create('test.pdf', 100);
        $response = $this->uploadliteratureVariant(-1, $file);

        $response->assertSessionHas(['Error' => 'Something went wrong. Contact your son.']);
        $response->assertStatus(302);
    }

    /**
     * Test uploading a literature variant
     */
    public function test_upload_literature_variant(): void
    {
        $this->uploadVariantWithoutErrors();
    }

    /**
     * Test uploading a literature variant with an existing language yields error
     */
    public function test_upload_variant_with_existing_language_yields_error(): void
    {
        $literature = Literature::factory()->create();
        $file = UploadedFile::fake()->create('test.pdf', 100);
        $language = fake()->languageCode();

        $this->uploadliteratureVariant($literature->id, $file, lang: $language);
        $response = $this->uploadliteratureVariant($literature->id, $file, lang: $language);

        $response->assertSessionHas(['Error' => 'Something went wrong. Contact your son.']);
        $response->assertStatus(302);
    }

    /**
     * Test deleting a variant
     */
    public function test_delete_variant_removes_file_and_entry(): void
    {
        $literature = Literature::factory()->create();
        $file = UploadedFile::fake()->create('test.pdf', 100);
        [$response] = $this->uploadVariantWithoutErrors($literature->id, $file);

        $response = $this->post(route('variant.delete', ['id' => $literature->id]));
        $response->assertStatus(201);
        $this->assertFalse(Storage::disk()->exists($file->hashName()));
        $this->assertCount(0, LiteratureVariant::all());
    }

    /**
     * Test deleting a variant validation
     */
    public function test_validation_errors_when_deleting_variant(): void
    {
        Exceptions::fake();
        $this->post(route('variant.delete', ['id' => 'notNumeric']));
        Exceptions::assertReported(TypeError::class);
    }

    /**
     * Test deleting a variant validation
     */
    public function test_provide_error_if_deleting_non_existing_variant(): void
    {
        $response = $this->post(route('variant.delete', ['id' => 55]));
        $response->assertSessionHas(['Error' => 'Something went wrong. Contact your son.']);
        $response->assertStatus(302);
    }

    /**
     * Test deleting a variant gives error message if cannot delete related file
     */
    public function test_provide_error_if_cannot_delete_file_when_deleting_variant(): void
    {
        $literature = Literature::factory()->create();
        $file = UploadedFile::fake()->create('test.pdf', 100);
        $this->uploadVariantWithoutErrors($literature->id, $file);

        Storage::shouldReceive('delete')
            ->once()
            ->andReturn(false);
        $response = $this->post(route('variant.delete', ['id' => $literature->id]));
        $response->assertStatus(302);
        $response->assertSessionHas(['Error' => 'Something went wrong. Contact your son.']);
        $this->assertCount(1, LiteratureVariant::all());
    }

    /**
     * Test that deleting a variant doesn't delete the related literature.
     */
    public function test_deleting_variant_does_not_delete_literature(): void
    {
        $literature = Literature::factory()->createOne();
        $this->uploadVariantWithoutErrors(literatureId: $literature->id);

        $file = UploadedFile::fake()->create('test.pdf', 100);
        [$rez, $variant] = $this->uploadVariantWithoutErrors(literatureId:$literature->id, file: $file, lang: 'kurdish');
        $this->assertCount(1, Literature::all());

        $response = $this->post(route('variant.delete', ['id' => $variant->id]));
        $response->assertStatus(201);
        $this->assertFalse(Storage::disk()->exists($file->hashName()));
        $this->assertCount(1, LiteratureVariant::all());
        $this->assertCount(1, Literature::all());
    }

    /**
     */
    public function test_deleting_last_variant_deletes_literature(): void
    {
        $file = UploadedFile::fake()->create('test.pdf', 100);
        [$res, $variant] = $this->uploadVariantWithoutErrors(file: $file);
        $this->assertCount(1, Literature::all());

        $response = $this->post(route('variant.delete', ['id' => $variant->id]));
        $response->assertStatus(201);
        $this->assertFalse(Storage::disk()->exists($file->hashName()));
        $this->assertCount(0, LiteratureVariant::all());
        $this->assertCount(0, Literature::all());
    }

    /**
     * Helper for uploading a literature variant.
     * @return array{TestResponse<Response>, LiteratureVariant}
     */
    private function uploadVariantWithoutErrors(?int $literatureId = null, ?File $file = null, ?string $lang = null): array
    {
        $literatureId = $literatureId ?? Literature::factory()->createOne()->id;
        $file = $file ?? UploadedFile::fake()->create('test.pdf', 100);
        $language = $lang ?? fake()->languageCode();
        $title = fake()->title();
        $description = fake()->sentence();

        $count = count(LiteratureVariant::all());

        $response = $this->uploadliteratureVariant($literatureId, $file, $title, $description, $language);
        $response->assertStatus(201);
        $this->assertCount($count + 1, LiteratureVariant::all());

        $this->assertTrue(Storage::disk()->exists($file->hashName()));

        $variant = LiteratureVariant::query()->where('literature_id', '=', $literatureId)->where('language', '=', $language)->first();
        $this->assertNotNull($variant);
        $this->assertEquals($file->hashName(), $variant->url);
        $this->assertEquals($title, $variant->title);
        $this->assertEquals($description, $variant->description);
        $this->assertEquals($language, $variant->language);
        $this->assertEquals($literatureId, $variant->literature_id);

        return [$response, $variant];
    }

    /**
     * Helper for uploading a literature variant.
     * @return TestResponse<Response>
     */
    private function uploadliteratureVariant(int $literatureId, File $file, ?string $title = null, ?string $description = null, ?string $lang = null): TestResponse
    {
        Storage::fake();

        $language = $lang ?? fake()->languageCode();
        $response = $this->post("/literatureVariant/upload/$literatureId", [
            'title' => $title ?? fake()->title(),
            'description' => $description ?? fake()->sentence(),
            'file' => $file,
            'language' => $language
        ]);

        return $response;
    }
}
