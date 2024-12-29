<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('current')]
class LiteratureControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test routes are protected
     */
    #[DataProvider('protectedRoutesProvider')]
    public function test_routes_are_protected(string $route): void
    {
        $this->get(route($route))
            ->assertRedirect(route('landingPage'))
            ->assertStatus(302);
    }

    /**
     * Data provider for protected routes
     */
    public static function protectedRoutesProvider(): array
    {
        return [
            ['literature.create'],
        ];
    }

    /**
     * Test getting form for creating new literature
     */
    public function test_render_form_for_creating_literature(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('literature.create'))
            ->assertStatus(200)
            ->assertViewIs('literature.create-literature')
            ->assertSeeTextInOrder(['Upload PDF', 'Title', 'Description',  'Language', 'Category']);
    }
}
