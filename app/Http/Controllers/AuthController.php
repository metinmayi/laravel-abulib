<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginFormRequest;
use App\Http\Requests\RegisterFormRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * Registers a user.
     */
    public function register(RegisterFormRequest $request): RedirectResponse
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->withErrors(['Error' => 'An error occured. Please contact your son.']);
        }

        event(new Registered($user));
        Auth::login($user);

        return redirect(route('library.index'));
    }

    /**
     * Logs a user in.
     */
    public function login(LoginFormRequest $request): RedirectResponse
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return redirect()->back()->withErrors(['Error' => 'Invalid credentials.']);
        }

        return redirect(route('library.index'));
    }
}
