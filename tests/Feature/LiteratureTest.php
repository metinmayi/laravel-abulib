<?php

namespace Tests\Feature;

use App\Actions\GetLiteratureListAction;
use App\Models\Literature;
use App\Models\LiteratureVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Testing\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Tests for Literature
 */
class LiteratureTest extends TestCase
{
    use RefreshDatabase;

    protected string $literatureCategory = 'research';

    protected string $variantTitle = 'Test Title';
    protected string $variantDescription = 'Test Description';
    protected string $variantLanguage = 'Test Language';
    protected string $fileName;


    /**
     * Test upload literature
     */
    public function test_upload_literature_creates_literature(): void
    {
        $this->uploadLiterature();
    }

    /**
     * Test upload literature requires auth
     */
    public function test_upload_literature_requires_auth(): void
    {
        $this->post(route('literature.upload'))
            ->assertRedirect(route('landingPage'))
            ->assertStatus(302);
    }

    /**
     * Test delete literature requires auth
     */
    public function test_delete_literature_requires_auth(): void
    {
        $this->post(route('literature.delete', ['id' => -1]))
            ->assertRedirect(route('landingPage'))
            ->assertStatus(302);
    }

    /**
     * Tests that a variant is created when uploading a literature.
     */
    public function test_upload_literature_creates_literature_variant(): void
    {
        $literature = $this->uploadLiterature();

        $variants = LiteratureVariant::all();
        $this->assertCount(1, $variants);

        $variant = $variants->first();
        $this->assertNotNull($variant);
        $this->assertEquals($this->variantTitle, $variant->title);
        $this->assertEquals($this->variantDescription, $variant->description);
        $this->assertEquals($this->variantLanguage, $variant->language);
        $this->assertEquals($this->fileName, $variant->url);
        $this->assertEquals($literature->id, $variant->literature_id);
        $this->assertTrue(Storage::disk()->exists($this->fileName));
    }

    /**
     * Test get literature list
     */
    public function test_get_literature_list_returns_expected_variants(): void
    {
        $language = 'my-language';
        $expectedResult = [
            $this->uploadAndGetExpectedVariant($language),
            $this->uploadAndGetExpectedVariant($language),
        ];

        $action = new GetLiteratureListAction($language);
        $literatureList = $action->handle();
        $this-> assertCount(20, LiteratureVariant::all());
        $this->assertCount(2, $literatureList);
        $this->assertEqualsCanonicalizing($expectedResult, $literatureList);
    }

    /**
     * Test delete literature
     */
    public function test_delete_literature(): void
    {
        $file  = UploadedFile::fake()->create('test.pdf', 100);
        $literature = $this->uploadLiterature(file: $file);
        $response = $this->post("/literature/delete/$literature->id");
        $response->assertStatus(302);
        $response->assertRedirect(route('library.index'));

        $this->assertCount(0, LiteratureVariant::all());
        $this->assertCount(0, Literature::all());
        $this->assertFalse(Storage::disk()->exists($file->hashName()));
    }

    /**
     * Test failing delete literature
     */
    public function test_failing_delete_literature_yields_errors(): void
    {
        $this->actingAs(User::factory()->create());
        $response = $this->post('/literature/delete/-1');
        $response->assertStatus(302);
        $response->assertRedirect(route('library.index'));
        $response->assertSessionHas('Error', 'An error occured. Please contact your son.');
    }

    /**
     * Upload several variants and return the expected variant
     */
    protected function uploadAndGetExpectedVariant(string $lang): object
    {
        $literature = Literature::factory()->create();
        $variants = LiteratureVariant::factory()
            -> set('literature_id', $literature->id)
            -> count(9)
            ->create();
        $availableLanguages = $variants->pluck('language')->toArray();

        $variant = LiteratureVariant::factory()
        ->set('literature_id', $literature->id)
        ->set('language', $lang)
        ->createOne();
        $availableLanguages[] = $lang;


        return (object) [
            'id' => $variant->id,
            'literature_id' => $literature->id,
            'language' => $variant->language,
            'availableLanguages' => $availableLanguages,
            'title' => $variant->title,
            'description' => $variant->description,
            'category' => $literature->category
        ];
    }

    /**
     * Helper
     */
    protected function uploadLiterature(?File $file = null): Literature
    {
        $this->actingAs(User::factory()->create());
        $this->assertCount(0, Literature::all());

        Storage::fake();
        $file = $file ?? UploadedFile::fake()->create('test.pdf', 100);
        $this->fileName = $file->hashName();
        $postData = [
            'title' => $this->variantTitle,
            'description' => $this->variantDescription,
            'language' => $this->variantLanguage,
            'category' => $this->literatureCategory,
            'file' => $file,
        ];

        $response = $this->post('/literature', $postData);
        $response->assertStatus(201);

        $literatures = Literature::all();
        $this->assertCount(1, $literatures);
        $this->assertCount(1, LiteratureVariant::all());
        $this->assertInstanceOf(Literature::class, $literatures->first());
        $this->assertEquals($this->literatureCategory, $literatures->first()->category);
        return $literatures->first();
    }
}
