<?php

namespace Tests\Feature\Actions;

use App\Actions\GetLiteratureListAction;
use App\Models\Literature;
use App\Models\Variant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetLiteratureListActionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that available languages are correct
     */
    public function testAvailableLanguages(): void
    {
        $literature = Literature::factory()->createOne();
        $variants = Variant::factory()
            ->for($literature)
            ->count(3)
            ->create();
        $additionalVariant = Variant::factory()
            ->for($literature)
            ->state(['language' => 'Test-Lang'])
            ->createOne();

        // Add a variant without a URL to test that it is not included in the available languages
        Variant::factory()
            ->for($literature)
            ->state(['language' => 'Test-Lang-2', 'url' => null])
            ->createOne();

        $languages = $variants->pluck('language')->push($additionalVariant->language)->toArray();

        $action = new \App\Actions\GetLiteratureListAction();
        $literatureList = $action
            ->setLanguage('Test-Lang')
            ->handle();

        $this->assertCount(1, $literatureList);
        $this->assertEquals($languages, $literatureList[0]->availableLanguages);
    }

    /**
     * Test that only literatures with the correct language are returned
     */
    public function testGetByFilteredLanguages(): void
    {
        $literature = Literature::factory()->createOne();
        Variant::factory()
            ->for($literature)
            ->state(['language' => 'english'])
            ->createOne();

        Variant::factory()
            ->for($literature)
            ->state(['language' => 'swedish', 'url' => null])
            ->createOne();

        Variant::factory()
            ->for($literature)
            ->state(['language' => 'kurdish'])
            ->createOne();

        $action = new GetLiteratureListAction();
        $list = $action
            ->setRequiredLanguages(['english', 'kurdish'])
            ->handle();
        $this->assertCount(1, $list);

        $list = $action
            ->setRequiredLanguages(['swedish'])
            ->handle();
        $this->assertCount(0, $list);
    }

    /**
     * Test that only literatures with the correct categories are returned
     */
    public function testGetByFilteredCategories(): void
    {
        Literature::factory()->withVariants()->createOne(['category' => 'poem']);
        Literature::factory()->withVariants()->createOne(['category' => 'poem']);
        Literature::factory()->withVariants()->createOne(['category' => 'article']);
        Literature::factory()->withVariants()->createOne(['category' => 'book']);
        Literature::factory()->withVariants()->createOne(['category' => 'book']);
        Literature::factory()->withVariants()->createOne(['category' => 'book']);

        app()->setLocale('english');
        $action = new GetLiteratureListAction();
        $list = $action
            ->setRequiredCategories(['poem'])
            ->handle();
        $this->assertCount(2, $list);


        $list = $action
            ->setRequiredCategories(['article'])
            ->handle();
        $this->assertCount(1, $list);

        $list = $action
            ->setRequiredCategories(['book'])
            ->handle();
        $this->assertCount(3, $list);
    }
}
