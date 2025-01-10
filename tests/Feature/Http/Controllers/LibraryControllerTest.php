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
     * Test the index page returns the correct view.
     */
    public function testSetRequiredLanguagesIfLanguagesQueryStringIsPresent(): void
    {
        $mock = Mockery::mock(GetLiteratureListAction::class);

        /** @phpstan-ignore-next-line */
        $mock->shouldReceive('setRequiredLanguages')
            ->with(['arabic', 'swedish']);
        $mock->shouldReceive('handle');

        // Bind the mock to the service container
        $this->app->instance(GetLiteratureListAction::class, $mock);
        $this->get('/library?languages=arabic%2Cswedish')
            ->assertStatus(200);
    }
}
