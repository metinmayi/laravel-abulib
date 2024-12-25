<?php

namespace Tests\Feature\View;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestView;
use Tests\TestCase;

class LibraryViewTest extends TestCase
{
    use RefreshDatabase;

    private const TEST_TITLE = 'My-Test-Title';
    private const TEST_DESCRIPTION = 'My-Test-Description';
    private const TEST_LANGUAGES = ['My-Test-Language'];
    private const TEST_CATEGORY = 'My-Test-Category';
    private const TEST_ID = 1;

    /**
     * Test that library view renders literatures.
     */
    public function test_library_renders_literatures(): void
    {
        $this->getContent()
            ->assertSeeInOrder([
                self::TEST_TITLE,
                self::TEST_DESCRIPTION,
                implode(',', self::TEST_LANGUAGES),
                self::TEST_CATEGORY,
            ]);
    }

    /**
     * Test that edit button is rendered for logged in users
     */
    public function test_library_renders_edit_button_if_logged_in(): void
    {
        $this->actingAs(User::factory()->create());

        $this->getContent()
            ->assertSeeText('Edit (Abdul only)');
    }

    /**
    * Test that edit button is not rendered for logged out users
     */
    public function test_library_doenst_render_edit_button_if_logged_out(): void
    {
        $this->getContent()
            ->assertDontSee('Edit (Abdul only)');
    }

    /**
     * Get the content for the view testing.
     */
    private function getContent(): TestView
    {
        return $this->view('library.index', [
            'literatureList' => [
                (object) [
                    'title' => self::TEST_TITLE,
                    'description' => self::TEST_DESCRIPTION,
                    'availableLanguages' => self::TEST_LANGUAGES,
                    'category' => self::TEST_CATEGORY,
                    'id' => self::TEST_ID,
                ],
            ],
        ]);
    }
}
