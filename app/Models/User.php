<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = ['email_verified_at' => 'datetime'];

    /**
     * Create a new user or return an existing one.
     */
    public static function createNewUser(array $data)
    {
        // Check if the email already exists
        $user = self::where('email', $data['email'])->first();

        if ($user) {
            return $user; // Return existing user instead of throwing exception
        }

        // Create and return new user
        return self::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']), // Ensure password is hashed
            'role' => $data['role'] ?? 'user',
        ]);
    }
}
