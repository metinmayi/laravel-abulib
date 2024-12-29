<?php

namespace Tests\Feature;

use App\Models\Literature;
use App\Models\LiteratureVariant;
use App\Models\User;
use Illuminate\Http\Testing\File;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

class LiteratureControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test routes are protected
     * @param string             $route  Route.
     * @param array<string, int> $params Route parameters.
     */
    #[DataProvider('protectedRoutesProvider')]
    public function test_routes_are_protected(string $route, array $params = []): void
    {
        $this->get(route($route, $params))
            ->assertRedirect(route('landingPage'))
            ->assertStatus(302);
    }

    /**
     * Data provider for protected routes
     * @return array<int, list<array<string, int>|string>>
     */
    public static function protectedRoutesProvider(): array
    {
        return [
            ['literature.create'],
            ['literature.store'],
            ['literature.destroy', ['literature' => 1]],
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
        $this->storeLiterature();
    }

    /**
     * Test delete literature
     */
    public function test_delete_literature(): void
    {
        $file  = UploadedFile::fake()->create('test.pdf', 100);
        $literature = $this->storeLiterature(file: $file);

        $this->delete(route('literature.destroy', ['literature' => $literature->id]))
            ->assertStatus(302)
            ->assertRedirect(route('library.index'));

        $this->assertCount(0, LiteratureVariant::all());
        $this->assertCount(0, Literature::all());
        $this->assertFalse(Storage::exists($file->hashName()));
    }

    /**
     * Test failing delete literature
     */
    public function test_failing_delete_literature_yields_errors(): void
    {
        $this->actingAs(User::factory()->create())
            ->delete(route('literature.destroy', ['literature' => 1]))
            ->assertStatus(302)
            ->assertRedirect(route('library.index'))
            ->assertSessionHas('Error', 'An error occured. Please contact your son.');
    }

    /**
     * Helper method to store literature
     */
    protected function storeLiterature(?File $file = null): Literature
    {
        $this->assertEquals(0, Literature::count());
        $this->assertEquals(0, LiteratureVariant::count());

        $this->actingAs(User::factory()->create())
            ->post(route('literature.store'), [
                'title' => 'Test Title',
                'description' => 'Test Description',
                'language' => 'kurdish',
                'category' => 'research',
                'file' => $file ?? UploadedFile::fake()->create('test.pdf', 100),
                'english-title' => 'English Title',
                'kurdish-title' => 'Kurdish Title',
                'swedish-title' => 'Swedish Title',
                'arabic-title' => 'Arabic Title',
            ])
            ->assertRedirect(route('library.index'))
            ->assertStatus(302);

            $this->assertEquals(1, Literature::count());
            $this->assertEquals(4, LiteratureVariant::count());
            $this->assertEquals(1, LiteratureVariant::query()->where('language', 'kurdish')->count());
            $this->assertEquals(1, LiteratureVariant::query()->where('language', 'english')->count());
            $this->assertEquals(1, LiteratureVariant::query()->where('language', 'swedish')->count());
            $this->assertEquals(1, LiteratureVariant::query()->where('language', 'arabic')->count());

            return Literature::query()->firstOrFail();
    }
}
