<?php

namespace Tests\Feature;

use App\Actions\GetLiteratureListAction;
use App\Models\Literature;
use App\Models\LiteratureVariant;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
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
     * Test get literature list
     */
    public function test_get_literature_list_gets_all_literatures(): void
    {
        $this->createLiteratureWithVariants(5);
        $this->createLiteratureWithVariants(5);

        $literatureList = (new GetLiteratureListAction('Test Language'))->handle();
        $this->assertCount(2, $literatureList);
    }

    /**
     * Test get literature list has correct data
     */
    public function test_get_literature_list_has_correct_data_for_matching_language(): void
    {
        $lang = 'Test-Lang';
        $literature = Literature::factory()->create();
        $variant = LiteratureVariant::factory()
            ->set('language', $lang)
            ->set('literature_id', $literature->id)
            ->createOne();

        $literatureList = (new GetLiteratureListAction($lang))->handle();
        $this->assertEquals($variant->title, $literatureList[0]->title);
        $this->assertEquals($variant->description, $literatureList[0]->description);
        $this->assertEquals([$lang], $literatureList[0]->availableLanguages);
        $this->assertEquals($literature->category, $literatureList[0]->category);
    }

    /**
     * Test get literature list has substituted data for non-matching language
     */
    public function test_get_literature_list_has_substituted_data_for_non_matching_language(): void
    {
        $lang = 'Test-Lang';
        $literature = Literature::factory()->create();
        LiteratureVariant::factory()
            ->set('language', 'Test-Lang')
            ->set('literature_id', $literature->id)
            ->createOne();

        $literatureList = (new GetLiteratureListAction('Non-Matching-Lang'))->handle();
        $this->assertEquals('Not available in english', $literatureList[0]->title);
        $this->assertEquals('Not available in english', $literatureList[0]->description);
        $this->assertEquals([$lang], $literatureList[0]->availableLanguages);
        $this->assertEquals($literature->category, $literatureList[0]->category);
    }

    /**
     * Test getLiteratureList has correct languages for multiple variants
     */
    public function test_get_literature_list_has_correct_languages_for_multiple_variants(): void
    {
        $variants = $this->createLiteratureWithVariants(5);
        $languages = $variants->pluck('language')->toArray();

        $literatureList = (new GetLiteratureListAction('Test-Lang'))->handle();
        $this->assertEquals($languages, $literatureList[0]->availableLanguages);
    }

    /**
     * Create a literature with a given number of variants
     * @return Collection<int, LiteratureVariant>
     */
    protected function createLiteratureWithVariants(int $variantCount): Collection
    {
        $literature = Literature::factory()->create();
        $variants = LiteratureVariant::factory()
            ->count($variantCount)
            ->set('literature_id', $literature->id)
            ->create();

        return $variants;
    }
}
