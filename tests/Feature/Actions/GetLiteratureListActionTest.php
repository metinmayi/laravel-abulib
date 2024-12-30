<?php

namespace Tests\Feature\Actions;

use App\Models\Literature;
use App\Models\LiteratureVariant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
        $variants = LiteratureVariant::factory()
            ->for($literature)
            ->count(3)
            ->create();
        $additionalVariant = LiteratureVariant::factory()
            ->for($literature)
            ->state(['language' => 'Test-Lang'])
            ->createOne();

        $languages = $variants->pluck('language')->push($additionalVariant->language)->toArray();

        $action = new \App\Actions\GetLiteratureListAction('Test-Lang');
        $literatureList = $action->handle();

        $this->assertCount(1, $literatureList);
        $this->assertEquals($languages, $literatureList[0]->availableLanguages);
    }
}
