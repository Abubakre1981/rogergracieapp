<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Voeg een nieuwe gebruiker toe.
     */
    public function addUser(Request $request)
    {
        // Stap 1: Log de volledige inkomende request data
        Log::info('Ontvangen request data in addUser:', ['data' => $request->all()]);

        // Stap 2: Gebruik dd() om de input te dumpen en hier te stoppen
        dd($request->all());

        // Stap 3: Validatie van invoergegevens
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:user,admin',
        ]);

        // Controleer of validatie faalt
        if ($validator->fails()) {
            Log::error('Validatie fouten:', ['errors' => $validator->errors()]);
            return response()->json([
                'message' => 'Validatiefouten',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            // Stap 4: Maak een nieuwe gebruiker aan en log de details
            Log::info('Poging om een nieuwe gebruiker aan te maken');
            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => bcrypt($request->input('password')),
                'role' => $request->input('role'),
            ]);

            Log::info('Nieuwe gebruiker succesvol aangemaakt:', ['user' => $user]);

            return response()->json([
                'message' => 'Gebruiker succesvol aangemaakt',
                'user' => $user,
            ], 201);

        } catch (\Exception $e) {
            // Stap 5: Log de fout en stuur een foutmelding terug
            Log::error('Fout bij het aanmaken van gebruiker:', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Er is een fout opgetreden bij het aanmaken van de gebruiker',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
