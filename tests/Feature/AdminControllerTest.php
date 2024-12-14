<?php

namespace Tests\Feature;

use Tests\TestCase;

class AdminControllerTest extends TestCase
{
    /**
     * Test the index page returns the correct view.
     */
    public function testIndexPageRendersCorrectView(): void
    {
        // Send a GET request to the index route
        $response = $this->get('/admin');

        // Assert the response is OK
        $response->assertStatus(200);

        // Assert the correct view is returned
        $response->assertViewIs('admin.index');
    }

    /**
     * Test the new literature page returns the correct view.
     */
    public function testNewLiteraturePageRendersCorrectView(): void
    {
        // Send a GET request to the new literature route
        $response = $this->get('/admin/newliterature');

        // Assert the response is OK
        $response->assertStatus(200);

        // Assert the correct view is returned
        $response->assertViewIs('admin.newliterature');
    }
}
