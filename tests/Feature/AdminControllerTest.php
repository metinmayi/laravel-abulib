<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the index page returns the correct view.
     */
    public function testIndexPageRendersCorrectView(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/admin')
            ->assertStatus(200)
            ->assertViewIs('admin.index');
    }

    /**
     * Test the new variant page returns the correct view.
     */
    public function testNewVariantPageRendersCorrectView(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/admin/newvariant')
            ->assertStatus(200)
            ->assertViewIs('admin.newvariant');
    }

    /**
     * Test index page only accessible to authenticated users.
     */
    public function testIndexPageOnlyAccessibleToAuthenticatedUsers(): void
    {
        $this->get('/admin')
            ->assertStatus(302)
            ->assertRedirect(route('landingPage'));
    }

    /**
     * Test the new literature page returns the correct view.
     */
    public function testNewLiteraturePageRendersCorrectView(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/admin/newliterature')
            ->assertStatus(200)
            ->assertViewIs('admin.newliterature');
    }

    /**
     * Test index page only accessible to authenticated users.
     */
    public function testNewLiteratureOnlyAccessibleToAuthenticatedUsers(): void
    {
        $this->get('/admin/newliterature')
            ->assertStatus(302)
            ->assertRedirect(route('landingPage'));
    }
}
