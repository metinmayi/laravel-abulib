<?php

namespace Tests\Feature;

use App\Models\Literature;
use App\Models\LiteratureVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AdminControllerTest extends TestCase
{
    use RefreshDatabase;


    /**
     * Test the admin edit variant page renders correctly
     */
    public function testEditVariantPageRendersCorrectly(): void
    {
        $variant = LiteratureVariant::factory()->createOne();
        $this->actingAs(User::factory()->create())
            ->get("/admin/editvariant/$variant->id")
            ->assertStatus(200)
            ->assertViewIs('admin.editvariant');
    }

    /**
     * Test the admin edit variant page renders correctly
     */
    public function testNewVariantPageRendersCorrectly(): void
    {
        $literature = Literature::factory()->createOne();
        $this->actingAs(User::factory()->create())
            ->get("/admin/newvariant/$literature->id")
            ->assertStatus(200)
            ->assertViewIs('admin.newvariant');
    }

    /**
     * Test the admin edit variant page renders correctly
     */
    public function testNewLiteraturePageRendersCorrectly(): void
    {
        $this->actingAs(User::factory()->create())
            ->get("/admin/newliterature")
            ->assertStatus(200)
            ->assertViewIs('admin.newliterature');
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
            ['/admin/newliterature', 'admin.newliterature'],
            ['/admin/newvariant/-1', 'admin.newvariant'],
            ['/admin/editvariant/1', 'admin.editvariant'],
        ];
    }
}
