<?php

namespace Tests\Feature;

use App\Actions\GetLitteratureListAction;
use App\Models\Litterature;
use App\Models\LitteratureVariant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Testing\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Tests for Litterature
 */
class LitteratureTest extends TestCase
{
    use RefreshDatabase;

    protected const DISK_STORE = 'litteratures';
    protected string $litteratureCategory = 'research';

    protected string $variantTitle = 'Test Title';
    protected string $variantDescription = 'Test Description';
    protected string $variantLanguage = 'Test Language';
    protected string $fileName;


    /**
     * Test upload litterature
     */
    public function test_upload_litterature_creates_litterature(): void
    {
        $this->uploadLiterature();
    }

    /**
     * Tests that a variant is created when uploading a litterature.
     */
    public function test_upload_litterature_creates_litterature_variant(): void
    {
        $litterature = $this->uploadLiterature();

        $variants = LitteratureVariant::all();
        $this->assertCount(1, $variants);

        $variant = $variants->first();
        $this->assertNotNull($variant);
        $this->assertEquals($this->variantTitle, $variant->title);
        $this->assertEquals($this->variantDescription, $variant->description);
        $this->assertEquals($this->variantLanguage, $variant->language);
        $this->assertEquals($this->fileName, $variant->url);
        $this->assertEquals($litterature->id, $variant->litterature_id);
        $this->assertTrue(Storage::disk()->exists($this->fileName));
    }

    /**
     * Test get litterature list
     */
    public function test_get_litterature_list_returns_expected_variants(): void
    {
        $language = 'ku';
        $expectedResult = [
            $this->uploadAndGetExpectedVariant($language),
            $this->uploadAndGetExpectedVariant($language),
        ];

        $action = new GetLitteratureListAction($language);
        $litteratureList = $action->handle();
        $this-> assertCount(20, LitteratureVariant::all());
        $this->assertCount(2, $litteratureList);
        $this->assertEqualsCanonicalizing($expectedResult, $litteratureList);
    }

    /**
     * Test delete litterature
     */
    public function test_delete_literature(): void
    {
        $file  = UploadedFile::fake()->create('test.pdf', 100);
        $literature = $this->uploadLiterature(file: $file);
        $response = $this->post("/literature/delete/$literature->id");
        $response->assertStatus(302);
        $response->assertRedirect(route('library.index'));

        $this->assertCount(0, LitteratureVariant::all());
        $this->assertCount(0, Litterature::all());
        $this->assertFalse(Storage::disk()->exists($file->hashName()));
    }

    /**
     * Test failing delete litterature
     */
    public function test_failing_delete_literature_yields_errors(): void
    {
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
        $litterature = Litterature::factory()->create();
        $variants = LitteratureVariant::factory()
            -> set('litterature_id', $litterature->id)
            -> count(9)
            ->create();
        $availableLanguages = $variants->pluck('language')->toArray();

        $variant = LitteratureVariant::factory()
        ->set('litterature_id', $litterature->id)
        ->set('language', $lang)
        ->createOne();
        $availableLanguages[] = $lang;


        return (object) [
            'id' => $variant->id,
            'litterature_id' => $litterature->id,
            'language' => $variant->language,
            'availableLanguages' => $availableLanguages,
            'title' => $variant->title,
            'description' => $variant->description,
            'category' => $litterature->category
        ];
    }

    /**
     * Helper
     */
    protected function uploadLiterature(?File $file = null): Litterature
    {
        $this->assertCount(0, Litterature::all());

        Storage::fake();
        $file = $file ?? UploadedFile::fake()->create('test.pdf', 100);
        $this->fileName = $file->hashName();
        $postData = [
            'title' => $this->variantTitle,
            'description' => $this->variantDescription,
            'language' => $this->variantLanguage,
            'category' => $this->litteratureCategory,
            'file' => $file,
        ];

        $response = $this->post('/literature', $postData);
        $response->assertStatus(201);

        $litteratures = Litterature::all();
        $this->assertCount(1, $litteratures);
        $this->assertCount(1, LitteratureVariant::all());
        $this->assertInstanceOf(Litterature::class, $litteratures->first());
        $this->assertEquals($this->litteratureCategory, $litteratures->first()->category);
        return $litteratures->first();
    }
}
