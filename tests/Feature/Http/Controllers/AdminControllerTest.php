<?php

namespace Tests\Feature;

use App\Models\Literature;
use App\Models\Variant;
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
        $variant = Variant::factory()->createOne();
        $this->actingAs(User::factory()->create())
            ->get(route('variant.edit', ['variant' => $variant->id]))
            ->assertStatus(200)
            ->assertViewIs('variant.edit');
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
            ['/admin/newvariant/-1', 'admin.newvariant'],
        ];
    }
}
