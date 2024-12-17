<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Voeg een nieuwe gebruiker toe.
     */
    public function addUser(Request $request)
    {
        // Validatie van invoer
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:user,admin',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validatiefouten',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $user = User::createNewUser([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => $request->input('password'),
                'role' => $request->input('role'),
            ]);

            return response()->json([
                'message' => 'Gebruiker succesvol aangemaakt',
                'user' => $user,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Er is een fout opgetreden bij het aanmaken van de gebruiker',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
