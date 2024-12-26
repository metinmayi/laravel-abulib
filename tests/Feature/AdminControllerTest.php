<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AdminControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the admin page returns the correct view.
     */
    #[DataProvider('adminPagesProvider')]
    public function testAdminPageRendersCorrectView(string $route, string $view): void
    {
        $this->actingAs(User::factory()->create())
            ->get($route)
            ->assertStatus(200)
            ->assertViewIs($view);
    }

    /**
     * Test that the admin pages are protected.
     */
    #[DataProvider('adminPagesProvider')]
    public function testAdminPagesAreProtected(string $route): void
    {
        $this->get($route)
            ->assertStatus(302)
            ->assertRedirect('/');
    }

    /**
     * Data provider for the admin pages.
     * @return array<array<string>>
     */
    public static function adminPagesProvider(): array
    {
        return [
            ['/admin/newvariant', 'admin.newvariant'],
            ['/admin/newliterature', 'admin.newliterature'],
            ['/admin/editvariant/1', 'admin.editvariant'],
        ];
    }
}
