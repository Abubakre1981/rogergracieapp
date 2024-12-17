<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Logging toegevoegd
use Inertia\Inertia;
use Illuminate\Http\RedirectResponse;

class AuthenticatedSessionController extends Controller
{
    /**
     * Show the login form.
     */
    public function create()
    {
        return Inertia::render('Auth/Login');
    }

    /**
     * Handle the login request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        Log::info('Login attempt', ['email' => $request->input('email')]);

        // Controleer inloggegevens
        if (!Auth::attempt($request->only('email', 'password'))) {
            Log::warning('Login failed', ['email' => $request->input('email')]);
            return back()->withErrors(['email' => 'Invalid credentials']);
        }

        // Sessieregeneratie na succesvolle login
        $request->session()->regenerate();
        Log::info('Login successful', ['email' => $request->input('email')]);

        return redirect()->intended('dashboard');
    }

    /**
     * Handle the logout request.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::user();
        Log::info('Logout attempt', ['email' => $user->email ?? 'guest']);

        // Log de gebruiker uit
        Auth::guard('web')->logout();

        // Invalideer sessie
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Log::info('Logout successful', ['email' => $user->email ?? 'guest']);

        return redirect('/');
    }
}
