<?php

namespace App\Services;

use App\Models\Users;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function register(array $data): array
    {
        try {
            // Create new user - validation already done by RegisterRequest
            $user = Users::create([
                'name'     => $data['name'] ?? null,
                'email'    => $data['email'],
                'password' => Hash::make($data['password']),
                'provider' => 'email',
            ]);

            // Generate authentication token
            $token = $user->createToken('mobile')->plainTextToken;

            return [
                'user'  => $user,
                'token' => $token,
            ];
        } catch (\Exception $e) {
            abort(500, 'Registration failed. Please try again later.');
        }
    }

    public function login(array $data): array
    {
        // Check if email is empty or not provided
        if (empty($data['email'])) {
            abort(422, 'Email address is required.');
        }

        // Check if password is empty or not provided
        if (empty($data['password'])) {
            abort(422, 'Password is required.');
        }

        // Find user by email
        $user = Users::where('email', $data['email'])->first();

        // Check if user exists
        if (!$user) {
            abort(404, 'No account found with this email address. Please check your email or register for a new account.');
        }

        // Verify password
        if (!Hash::check($data['password'], $user->password)) {
            abort(401, 'The password you entered is incorrect. Please try again.');
        }

        // Check if user is using the correct authentication provider
        if ($user->provider !== 'email') {
            abort(422, 'This account was created using ' . ucfirst($user->provider) . '. Please use ' . ucfirst($user->provider) . ' to login.');
        }

        // Update last login timestamp
        $user->update([
            'last_login_at' => now(),
        ]);

        // Generate authentication token
        $token = $user->createToken('mobile')->plainTextToken;

        return [
            'user'  => $user,
            'token' => $token,
        ];
    }

    public function logout(Users $user): void
    {
        $user->tokens()->delete();
    }
}
