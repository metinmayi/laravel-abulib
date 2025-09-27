<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Literature;
use App\Models\Variant;
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
     * Test literature form is rendered correctly
     */
    public function test_form_is_rendered_correctly(): void
    {

        $this->actingAs(User::factory()->createOne())
            ->get(route('literature.create'))
            ->assertViewIs('literature.create-literature');
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

        $this->assertCount(0, Variant::all());
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
        $this->assertEquals(0, Variant::count());

        $file = UploadedFile::fake()->create('test.pdf', 100);  // Fake file for the main literature

        $this->actingAs(User::factory()->create())
        ->post(route('literature.store'), [
            'category' => 'research',
            'file' => $file,
            'variants' => [
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
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('library.index'))
        ->assertStatus(302);

    // Assert the Literature entry has been created
        $this->assertEquals(1, Literature::count());
        $literature = Literature::query()->firstOrFail();

    // Assert the Variant entries have been created
        $this->assertEquals(4, Variant::count());

    // Check that each language has the correct number of variants
        $this->assertEquals(1, Variant::where('language', 'english')->count());
        $this->assertEquals(1, Variant::where('language', 'kurdish')->count());
        $this->assertEquals(1, Variant::where('language', 'swedish')->count());
        $this->assertEquals(1, Variant::where('language', 'arabic')->count());

    // Assert the correct properties for each Variant
        $this->assertDatabaseHas('variants', [
        'literature_id' => $literature->id,
        'language' => 'english',
        'title' => 'English Title',
        'description' => 'English Description',
        ]);

        $this->assertDatabaseHas('variants', [
        'literature_id' => $literature->id,
        'language' => 'kurdish',
        'title' => 'Kurdish Title',
        'description' => 'Kurdish Description',
        ]);

        $this->assertDatabaseHas('variants', [
        'literature_id' => $literature->id,
        'language' => 'swedish',
        'title' => 'Swedish Title',
        'description' => 'Swedish Description',
        ]);

        $this->assertDatabaseHas('variants', [
        'literature_id' => $literature->id,
        'language' => 'arabic',
        'title' => 'Arabic Title',
        'description' => 'Arabic Description',
        ]);

        return $literature;
    }

    /**
     * Test storing literature fails when all variant titles are empty.
     */
    public function test_store_requires_at_least_one_variant_title(): void
    {
        $this->actingAs(User::factory()->create());

        $response = $this->post(route('literature.store'), [
            'category' => 'research',
            'variants' => [
                'english' => [
                    'title' => '',
                    'description' => 'Desc',
                    'file' => UploadedFile::fake()->create('en.pdf', 10),
                    'language' => 'english',
                ],
                'kurdish' => [
                    'title' => '',
                    'description' => 'Desc',
                    'file' => UploadedFile::fake()->create('ku.pdf', 10),
                    'language' => 'kurdish',
                ],
                'swedish' => [
                    'title' => '',
                    'description' => 'Desc',
                    'file' => UploadedFile::fake()->create('sv.pdf', 10),
                    'language' => 'swedish',
                ],
                'arabic' => [
                    'title' => '',
                    'description' => 'Desc',
                    'file' => UploadedFile::fake()->create('ar.pdf', 10),
                    'language' => 'arabic',
                ],
            ],
        ]);

        $response->assertStatus(302)
            ->assertSessionHas('Error', 'At least one literature variant must have a title.');
    }
}
