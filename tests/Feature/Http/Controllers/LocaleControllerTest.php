<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;
use Illuminate\Support\Facades\App;

class LocaleControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testChangeLocaleRedirectsBack(): void
    {
        $this->from(route('library.index'))
            ->get(route('locale.change', ['locale' => 'russian']))
            ->assertRedirect(route('library.index'));
    }

    /**
     * Test change locale sets session locale
     */
    public function testChangeLocaleSetsSessionLocale(): void
    {
        $this->get(route('locale.change', ['locale' => 'russian']))
            ->assertSessionHas('locale', 'russian');

        $this->get(route('landingPage'));
        $this->assertEquals('russian', App::getLocale());
    }
}
