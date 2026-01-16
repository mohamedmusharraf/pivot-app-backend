<?php

namespace App\Services;

use App\Models\Users;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function register(array $data): array
    {
        $user = Users::create([
            'name'     => $data['name'] ?? null,
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'provider' => 'email',
        ]);

        $token = $user->createToken('mobile')->plainTextToken;

        return [
            'user'  => $user,
            'token' => $token,
        ];
    }

    public function login(array $data): array
    {
        $user = Users::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            abort(401, 'Invalid credentials');
        }

        if ($user->provider !== 'email') {
            abort(422, 'Please login using ' . $user->provider);
        }

        $user->update([
            'last_login_at' => now(),
        ]);

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
