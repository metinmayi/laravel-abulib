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
     * Test literature form is rendered correctly
     */
    public function test_form_is_rendered_correctly(): void
    {
        // Get the page
        $response = $this->actingAs(User::factory()->createOne())
            ->get(route('literature.create'));

        // Assert that the form is visible
        $response->assertStatus(200);
        $response->assertSee('Upload New Literature'); // Checks for page title
        $response->assertSee('Category:'); // Ensures the category label exists
        $response->assertSee('Submit'); // Ensures the submit button exists

        // Check that all languages' collapsible sections are present
        foreach (\App\Models\Literature::LANGUAGES as $language) {
            $response->assertSee(ucfirst($language) . ' Literature');
            $response->assertSee(ucfirst($language) . ' Title');
            $response->assertSee(ucfirst($language) . ' Description');
        }
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

        $file = UploadedFile::fake()->create('test.pdf', 100);  // Fake file for the main literature

        $this->actingAs(User::factory()->create())
        ->post(route('literature.store'), [
            'category' => 'research',
            'file' => $file,
            'literatures' => [
                'english' => [
                    'title' => 'English Title',
                    'description' => 'English Description',
                    'file' => UploadedFile::fake()->create('english_file.pdf', 100),
                    'language' => 'english',
                ],
                'kurdish' => [
                    'title' => 'Kurdish Title',
                    'description' => 'Kurdish Description',
                    'file' => UploadedFile::fake()->create('kurdish_file.pdf', 100),
                    'language' => 'kurdish',
                ],
                'swedish' => [
                    'title' => 'Swedish Title',
                    'description' => 'Swedish Description',
                    'file' => UploadedFile::fake()->create('swedish_file.pdf', 100),
                    'language' => 'swedish',
                ],
                'arabic' => [
                    'title' => 'Arabic Title',
                    'description' => 'Arabic Description',
                    'file' => UploadedFile::fake()->create('arabic_file.pdf', 100),
                    'language' => 'arabic',
                ],
            ]
        ])
        ->assertRedirect(route('library.index'))
        ->assertStatus(302);

    // Assert the Literature entry has been created
        $this->assertEquals(1, Literature::count());
        $literature = Literature::query()->firstOrFail();

    // Assert the LiteratureVariant entries have been created
        $this->assertEquals(4, LiteratureVariant::count());

    // Check that each language has the correct number of variants
        $this->assertEquals(1, LiteratureVariant::where('language', 'english')->count());
        $this->assertEquals(1, LiteratureVariant::where('language', 'kurdish')->count());
        $this->assertEquals(1, LiteratureVariant::where('language', 'swedish')->count());
        $this->assertEquals(1, LiteratureVariant::where('language', 'arabic')->count());

    // Assert the correct properties for each LiteratureVariant
        $this->assertDatabaseHas('literature_variants', [
        'literature_id' => $literature->id,
        'language' => 'english',
        'title' => 'English Title',
        'description' => 'English Description',
        ]);

        $this->assertDatabaseHas('literature_variants', [
        'literature_id' => $literature->id,
        'language' => 'kurdish',
        'title' => 'Kurdish Title',
        'description' => 'Kurdish Description',
        ]);

        $this->assertDatabaseHas('literature_variants', [
        'literature_id' => $literature->id,
        'language' => 'swedish',
        'title' => 'Swedish Title',
        'description' => 'Swedish Description',
        ]);

        $this->assertDatabaseHas('literature_variants', [
        'literature_id' => $literature->id,
        'language' => 'arabic',
        'title' => 'Arabic Title',
        'description' => 'Arabic Description',
        ]);

        return $literature;
    }
}