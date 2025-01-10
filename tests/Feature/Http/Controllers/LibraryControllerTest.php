<?php

namespace Tests\Feature;

use App\Actions\GetLiteratureListAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class LibraryControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the index page returns the correct view.
     */
    public function testIndexPageRendersCorrectView(): void
    {
        $this->get('/library')
            ->assertStatus(200)
            ->assertViewIs('library.index');
    }

    /**
     * Test the index page correctly invokes the GetLiteratureListAction by language.
     */
    public function testSetRequiredLanguagesIfLanguagesQueryStringIsPresent(): void
    {
        $mock = Mockery::mock(GetLiteratureListAction::class);

        /** @phpstan-ignore-next-line */
        $mock->shouldReceive('setRequiredLanguages')
            ->once()
            ->with(['arabic', 'swedish']);
        /** @phpstan-ignore-next-line */
        $mock->shouldReceive('handle')
            ->once();

        $this->app->instance(GetLiteratureListAction::class, $mock);
        $this->get('/library?languages=arabic%2Cswedish')
            ->assertStatus(200);
    }

    /**
     * Test the index page correctly invokes the GetLiteratureListAction by category.
     */
    public function testSetRequiredCategoriesIfCategoriesQueryStringIsPresent(): void
    {
        $mock = Mockery::mock(GetLiteratureListAction::class);


        /** @phpstan-ignore-next-line */
        $mock->shouldReceive('setRequiredCategories')
            ->once()
            ->with(['poem', 'article']);
        /** @phpstan-ignore-next-line */
        $mock->shouldReceive('handle')
            ->once();


        $this->app->instance(GetLiteratureListAction::class, $mock);
        $this->get('/library?categories=poem%2Carticle')
            ->assertStatus(200);
    }
}
