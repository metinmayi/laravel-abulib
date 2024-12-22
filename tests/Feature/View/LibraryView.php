<?php

namespace Tests\Feature\View;

use App\Models\User;
use Tests\TestCase;

class ZzaTest extends TestCase
{
    /**
     * Test that library view renders literatures.
     */
    public function test_library_renders_literatures(): void
    {
        $contents = $this->view('zza', [
            'litteratureList' => [
                [
                    'title' => 'The Book of Zza',
                    'author' => 'Zza',
                    'language' => 'kurdish',
                    'description' => 'The Book of Zza is a book written by Zza in kurdish language.',
                    'category' => 'book',
                ],
            ],
        ]);

        $contents->assertSee('The book of Zza');
    }

    /**
     * Test that edit button is rendered for logged in users
     */
    public function test_library_renders_edit_button_if_logged_in(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $contents = $this->view('zza', [
            'litteratureList' => [
                [
                    'title' => 'The Book of Zza',
                    'author' => 'Zza',
                    'language' => 'kurdish',
                    'description' => 'The Book of Zza is a book written by Zza in kurdish language.',
                    'category' => 'book',
                ],
            ],
        ]);

        $contents->assertSee('The book of Zza');
        $contents->assertSeeText('Edit (Abdul only)');
    }

    /**
    * Test that edit button is not rendered for logged out users
     */
    public function test_library_doenst_render_edit_button_if_logged_out(): void
    {
        $contents = $this->view('zza', [
            'litteratureList' => [
                [
                    'title' => 'The Book of Zza',
                    'author' => 'Zza',
                    'language' => 'kurdish',
                    'description' => 'The Book of Zza is a book written by Zza in kurdish language.',
                    'category' => 'book',
                ],
            ],
        ]);

        $contents->assertDontSee('Edit (Abdul only)');
    }
}
