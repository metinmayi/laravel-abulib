<?php

namespace Tests\Feature;

use App\Models\Literature;
use App\Models\Variant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Testing\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Exceptions;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\TestResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;
use TypeError;

class VariantTest extends TestCase
{
    use RefreshDatabase;

    protected \Illuminate\Contracts\Filesystem\Filesystem $storage;

    /**
     * Test validation for uploading a variant
     */
    public function testUploadLiteratureVariantValidationErrors(): void
    {
            $this->actingAs(User::factory()->createOne())
                ->post(route('variant.store', ['variant' => -1]))
                ->assertSessionHasErrors(['title'])
                ->assertStatus(302);
    }

    /**
     * Test uploading a literature variant without an existing literature yields error
     */
    public function testUploadLiteratureVariantWithoutExistingLiterature(): void
    {
        $file = UploadedFile::fake()->create('test.pdf', 100);
        $response = $this->uploadLiteratureVariant(-1, $file);

        $response->assertSessionHas(['Error' => 'Something went wrong. Contact your son.']);
        $response->assertStatus(302);
    }

    /**
     * Test uploading a literature variant
     */
    public function testUploadLiteratureVariant(): void
    {
        $this->uploadVariantWithoutErrors();
    }

    /**
     * Test uploading a literature variant with an existing language yields error
     */
    public function testUploadVariantWithExistingLanguageYieldsError(): void
    {
        $literature = Literature::factory()->create();
        $file = UploadedFile::fake()->create('test.pdf', 100);
        $language = fake()->languageCode();

        $this->uploadLiteratureVariant($literature->id, $file, lang: $language);
        $response = $this->uploadLiteratureVariant($literature->id, $file, lang: $language);

        $response->assertSessionHas(['Error' => 'Something went wrong. Contact your son.']);
        $response->assertStatus(302);
    }

    /**
     * Test deleting a variant
     */
    public function testDeleteVariantRemovesFileAndEntry(): void
    {
        $literature = Literature::factory()->create();
        $file = UploadedFile::fake()->create('test.pdf', 100);
        [$res, $variant] = $this->uploadVariantWithoutErrors($literature->id, $file);

        $this->delete(route('variant.destroy', ['variant' => $variant->id]))
            ->assertRedirect(route('library.index'));
        $this->assertFalse(Storage::exists($file->hashName()));
        $this->assertCount(0, Variant::all());
    }

    /**
     * Test deleting a variant validation
     */
    public function testValidationErrorsWhenDeletingVariant(): void
    {
        Exceptions::fake();

        $this->actingAs(User::factory()->createOne())
            ->delete(route('variant.destroy', ['variant' => 'notNumeric']));

        Exceptions::assertReported(TypeError::class);
    }

    /**
     * Test deleting a variant validation
     */
    public function testProvideErrorIfDeletingNonExistingVariant(): void
    {
        $this->actingAs(User::factory()->createOne())
            ->delete(route('variant.destroy', ['variant' => -1]))
            ->assertSessionHas(['Error' => 'Something went wrong. Contact your son.'])
            ->assertStatus(302);
    }

    /**
     * Test deleting a variant gives error message if cannot delete related file
     */
    public function testProvideErrorIfCannotDeleteFileWhenDeletingVariant(): void
    {
        $literature = Literature::factory()->create();
        $file = UploadedFile::fake()->create('test.pdf', 100);
        [$res, $variant] = $this->uploadVariantWithoutErrors($literature->id, $file);

        Storage::shouldReceive('delete')
            ->once()
            ->andReturn(false);
        $response = $this->delete(route('variant.destroy', ['variant' => $variant->id]));
        $response->assertStatus(302);
        $response->assertSessionHas(['Error' => 'Something went wrong. Contact your son.']);
        $this->assertCount(1, Variant::all());
    }

    /**
     * Test that deleting a variant doesn't delete the related literature.
     */
    public function testDeletingVariantDoesNotDeleteLiterature(): void
    {
        $literature = Literature::factory()->createOne();
        $this->uploadVariantWithoutErrors(literatureId: $literature->id, lang:'swedish');

        $file = UploadedFile::fake()->create('test.pdf', 100);
        [$rez, $variant] = $this->uploadVariantWithoutErrors(literatureId:$literature->id, file: $file, lang: 'kurdish');
        $this->assertCount(1, Literature::all());

        $this->delete(route('variant.destroy', ['variant' => $variant->id]))
            ->assertRedirect(route('library.index'));
        $this->assertFalse(Storage::exists($file->hashName()));
        $this->assertCount(1, Variant::all());
        $this->assertCount(1, Literature::all());
    }

    /**
     */
    public function testDeletingLastVariantDeletesLiterature(): void
    {
        $file = UploadedFile::fake()->create('test.pdf', 100);
        [$res, $variant] = $this->uploadVariantWithoutErrors(file: $file);
        $this->assertCount(1, Literature::all());

        $this->delete(route('variant.destroy', ['variant' => $variant->id]))
            ->assertRedirect(route('library.index'));
        $this->assertFalse(Storage::exists($file->hashName()));
        $this->assertCount(0, Variant::all());
        $this->assertCount(0, Literature::all());
    }

    /**
     * Test that the update variant endpoint requires authentication
     */
    public function testUpdateVariantEndpointRequiresAuth(): void
    {
        $this->patch(route('variant.update', ['variant' => 1]))
            ->assertStatus(302)
            ->assertRedirect(route('landingPage'));
    }

    /**
     * Test that the update variant endpoint requires a valid variant id
     * @param array<string, string> $input       Input data.
     * @param boolean               $shouldError Whether the request should error.
     * @param array<string>         $errors      Expected errors.
     */
    #[DataProvider('updateVariantValidationProvider')]
    public function testUpdateVariantEndpointValidation(array $input, bool $shouldError = false, array $errors = []): void
    {
        $this->actingAs(User::factory()->createOne());
        $response = $this->patch(route('variant.update', ['variant' => 1]), $input);
        $response->assertStatus(302);

        if ($shouldError) {
            $response->assertSessionHasErrors($errors);
        } else {
            $response->assertSessionHasNoErrors();
        }
    }

    /**
     * Data provider for update variant validation
     * @return array<int, array{array<string, string>, bool, array<string>}>
     */
    public static function updateVariantValidationProvider(): array
    {
        $errors = ['title', 'description', 'file'];
        return [
            [[], true, $errors],
            [['title' => ''],true, $errors],
            [['description' => ''],true,  $errors],
            [['file' => ''], true, $errors],
            [['title' => 'test'], false, []],
            [['description' => 'test'], false, []],
            [['file' => UploadedFile::fake()->create('test.pdf', 100)], false, []],
        ];
    }

    /**
     * Test updating a variant that does not exist yields errors and logs.
     */
    public function testUpdateVariantThatDoesNotExist(): void
    {
        Log::shouldReceive('error')->once();

        $this->actingAs(User::factory()->createOne())
            ->patch(route('variant.update', ['variant' => -1]), ['title' => 'test'])
            ->assertStatus(302)
            ->assertSessionHas(['Error' => 'Something went wrong. Contact your son.']);
    }

    /**
     * Test updating a variant title, description and language
     */
    #[DataProvider('updateVariantPropertyProvider')]
    public function testUpdateVariantProperty(string $property, string $value): void
    {
        [$res, $variant] = $this->uploadVariantWithoutErrors();

        $this->actingAs(User::factory()->createOne())
            ->patch(route('variant.update', ['variant' => $variant->id]), [$property => $value])
            ->assertSessionHasNoErrors();

        $variant = Variant::query()->findOrFail($variant->id);
        $this->assertEquals($value, $variant->$property);
    }

    /**
     * Data provider for updating a variant title, description and/or language
     * @return array<int, array{string, string}>
     */
    public static function updateVariantPropertyProvider(): array
    {
        return [
            ['title', 'new title'],
            ['description', 'new description'],
        ];
    }

    /**
     * Test updating a variant file
     */
    public function testUpdateVariantFile(): void
    {
        [$res, $variant] = $this->uploadVariantWithoutErrors();
        $oldUrl = $variant->url;
        $this->assertNotNull($oldUrl);

        $newFile = UploadedFile::fake()->create('test.pdf', 100);
        $this->actingAs(User::factory()->createOne())
            ->from(route('variant.edit', ['variant' => $variant->id]))
            ->patch(route('variant.update', ['variant' => $variant->id]), ['file' => $newFile])
            ->assertStatus(302)
            ->assertRedirect(route('variant.edit', ['variant' => $variant->id]))
            ->assertSessionHasNoErrors();

        $variant = Variant::query()->findOrFail($variant->id);
        $this->assertEquals($newFile->hashName(), $variant->url);
        $this->assertTrue(Storage::exists($newFile->hashName()));
        $this->assertFalse(Storage::exists($oldUrl));
    }

    /**
     * Test updating a variant file yields error if uploading new file fails
     */
    public function testUpdateVariantFileFailsIfUploadingNewFileFails(): void
    {
        [$res, $variant] = $this->uploadVariantWithoutErrors();
        $oldUrl = $variant->url;
        $newFile = UploadedFile::fake()->create('test.pdf', 100);

        Storage::shouldReceive('putFile')
            ->once()
            ->andReturn(false);
        Storage::shouldReceive('delete')->never();

        $this->actingAs(User::factory()->createOne())
            ->patch(route('variant.update', ['variant' => $variant->id]), ['file' => $newFile])
            ->assertStatus(302)
            ->assertSessionHas(['Error' => 'Something went wrong. Contact your son.']);

        $variant = Variant::query()->findOrFail($variant->id);
        $this->assertEquals($oldUrl, $variant->url);
    }

    /**
     * Test updating a variant loggs error if deleting old file fails
     */
    public function testUpdateVariantFileFailsIfDeletingOldFileFails(): void
    {
        [$res, $variant] = $this->uploadVariantWithoutErrors();
        $newFile = UploadedFile::fake()->create('test.pdf', 100);

        Storage::shouldReceive('putFile')
            ->once()
            ->andReturn(true);
            Storage::shouldReceive('delete')
            ->once()
            ->andReturn(false);
        Log::shouldReceive('error')->once();

        $this->actingAs(User::factory()->createOne())
            ->patch(route('variant.update', ['variant' => $variant->id]), ['file' => $newFile])
            ->assertStatus(302)
            ->assertSessionHasNoErrors();
    }

    /**
     * Helper for uploading a literature variant.
     * @return array{TestResponse<Response>, Variant}
     */
    private function uploadVariantWithoutErrors(?int $literatureId = null, ?File $file = null, ?string $lang = null): array
    {
        $literatureId = $literatureId ?? Literature::factory()->createOne()->id;
        $file = $file ?? UploadedFile::fake()->create('test.pdf', 100);
        /** @var string */
        $language = $lang ?? fake()->randomElement(Literature::LANGUAGES);
        $title = fake()->title();
        $description = fake()->sentence();

        $count = count(Variant::all());

        $response = $this->uploadLiteratureVariant($literatureId, $file, $title, $description, $language);
        $response->assertStatus(201);
        $this->assertCount($count + 1, Variant::all());

        $this->assertTrue(Storage::exists($file->hashName()));

        $variant = Variant::query()->where('literature_id', '=', $literatureId)->where('language', '=', $language)->first();
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
    private function uploadLiteratureVariant(int $literatureId, File $file, ?string $title = null, ?string $description = null, ?string $lang = null): TestResponse
    {
        $this->actingAs(User::factory()->createOne());
        $this->storage = Storage::fake();

        $language = $lang ?? fake()->randomElement(Literature::LANGUAGES);
        $response = $this->post(route('variant.store'), [
            'title' => $title ?? fake()->title(),
            'description' => $description ?? fake()->sentence(),
            'file' => $file,
            'language' => $language,
            'literature_id' => $literatureId,
        ]);

        return $response;
    }
}
