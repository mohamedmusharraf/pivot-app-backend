<?php

namespace App\Repositories\Auth;

interface PasswordResetRepositoryInterface
{
    public function createOrUpdateOtp(string $email, int $otp): void;
    public function getValidOtp(string $email, int $otp);
    public function deleteOtp(string $email): void;
}
