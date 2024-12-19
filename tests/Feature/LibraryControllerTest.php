<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LibraryControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the index page returns the correct view.
     */
    public function testIndexPageRendersCorrectView(): void
    {
        // Send a GET request to the index route
        $response = $this->get('/library');

        // Assert the response is OK
        $response->assertStatus(200);

        // Assert the correct view is returned
        $response->assertViewIs('library.index');
    }
}
