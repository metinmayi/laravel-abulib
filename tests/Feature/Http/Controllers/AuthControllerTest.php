<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Auth\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test registering a user validation yields errors.
     */
    public function test_register_user_without_input_yields_validation_errors(): void
    {
        $response = $this->post('/register');

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['email', 'password', 'name']);
    }

    /**
     * Test registering a user creates a user.
     */
    public function test_registering_user_creates_user(): void
    {
        $credentials = [
            'name' => fake()->name(),
            'password' => fake()->password(),
            'email' => fake()->email(),
        ];

        $response = $this->post('/register', $credentials);
        $response->assertStatus(302);
        $response->assertRedirect(route('library.index'));

        $user = User::query()->where('email', $credentials['email'])->first();
        $this->assertNotNull($user);
    }

    /**
     * Test registering a user with a duplicate email yields an error.
     */
    public function test_registering_user_duplicate_email_yields_error(): void
    {
        $user = \App\Models\User::factory()->create();

        $response = $this->post('/register', [
            'name' => fake()->name(),
            'password' => fake()->password(),
            'email' => $user->email,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('Error', 'An error occured. Please contact your son.');
    }

    /**
     * Test logging in a user.
     */
    public function test_login_user_without_input_yields_validation_errors(): void
    {
        $response = $this->post('/login');

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['email', 'password']);
    }

    /**
     * Test logging in a user.
     */
    public function test_logging_in_user(): void
    {
        $user = \App\Models\User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('library.index'));
        $this->assertTrue(Auth::check());
    }

    /**
     * Test logging in a user with invalid credentials.
     */
    public function test_logging_in_user_with_invalid_credentials(): void
    {
        $user = \App\Models\User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'invalid-password',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('Error', 'Invalid credentials.');
    }
}
