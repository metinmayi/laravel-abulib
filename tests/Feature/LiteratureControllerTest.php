<?php

namespace Tests\Feature;

use App\Models\Literature;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
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
     * @return array<array<string>>
     */
    public static function protectedRoutesProvider(): array
    {
        return [
            ['literature.create'],
            ['literature.store'],
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

    /**
     * Test storing literature
     */
    public function test_store_literature(): void
    {
        $this->assertEquals(0, Literature::count());

        $this->actingAs(User::factory()->create())
            ->post(route('literature.store'), [
                'title' => 'Test Title',
                'description' => 'Test Description',
                'language' => 'Test Language',
                'category' => 'research',
                'file' => UploadedFile::fake()->create('test.pdf', 100),
            ])
            ->assertRedirect(route('library.index'))
            ->assertStatus(302);

            $this->assertEquals(1, Literature::count());
    }
}
