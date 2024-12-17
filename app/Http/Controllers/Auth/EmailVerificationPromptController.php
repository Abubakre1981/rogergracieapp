<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     * 
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function __invoke(Request $request): RedirectResponse|Response
    {
        // Controleer of de gebruiker is ingelogd en een geverifieerd e-mailadres heeft
        if ($request->user()->hasVerifiedEmail()) {
            // Als het e-mailadres al is geverifieerd, omleiden naar het dashboard
            return redirect()->intended(route('dashboard'));
        }

        // Render de e-mailverificatiepagina als het e-mailadres niet geverifieerd is
        return Inertia::render('Auth/VerifyEmail', [
            'status' => session('status'),
        ]);
    }
}
