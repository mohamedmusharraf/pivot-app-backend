<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Repositories\Auth\PasswordResetRepositoryInterface;

class PasswordResetRepository implements PasswordResetRepositoryInterface
{
    public function createOrUpdateOtp(string $email, int $otp): void
    {
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'otp' => $otp,
                'expires_at' => now()->addMinutes(10),
                'created_at' => now(),
            ]
        );
    }

    public function getValidOtp(string $email, int $otp)
    {
        return DB::table('password_reset_tokens')
            ->where('email', $email)
            ->where('otp', $otp)
            ->first();
    }

    public function deleteOtp(string $email): void
    {
        DB::table('password_reset_tokens')
            ->where('email', $email)
            ->delete();
    }
}
