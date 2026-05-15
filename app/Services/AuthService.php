<?php

namespace App\Services;

use App\Models\Users;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;
use App\Repositories\Auth\AuthRepositoryInterface;
use App\Repositories\Auth\PasswordResetRepositoryInterface;
use App\Repositories\Auth\LogoutRepositoryInterface;
use Carbon\Carbon;

class AuthService
{
    public function __construct(
        protected AuthRepositoryInterface $authRepositoryInterface,
        protected PasswordResetRepositoryInterface $passwordResetRepositoryInterface,
        protected LogoutRepositoryInterface $logoutRepositoryInterface
    ) {}

    public function register(array $data): array
    {
        $user = $this->authRepositoryInterface->createUser($data);
        $token = $user->createToken('mobile')->plainTextToken;

        return compact('user', 'token');
    }

    public function login(array $data): array
    {
        if (empty($data['email']) || empty($data['password'])) {
            throw ValidationException::withMessages([
                'email' => ['Email address is required.'],
                'password' => ['Password is required.'],
            ]);
        }

        $user = $this->authRepositoryInterface->findByEmail($data['email']);

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => ['No account found with this email address.'],
            ]);
        }

        if (!Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid email or password.'],
            ]);
        }

        if ($user->provider !== 'email') {
            throw ValidationException::withMessages([
                'email' => ['Please login using ' . ucfirst($user->provider) . '.'],
            ]);
        }

        $this->authRepositoryInterface->updateLastLogin($user);

        // Load subscription relationship
        $user->load('subscription');

        $token = $user->createToken('mobile')->plainTextToken;

        return compact('user', 'token');
    }

    public function getCurrentUser()
    {
        return $this->authRepositoryInterface->getCurrentUser();
    }

    public function logout(): void
    {
        $this->logoutRepositoryInterface->logoutCurrentUser();
    }

    public function sendResetPasswordEmail(string $email): void
    {
        $user = $this->authRepositoryInterface->findByEmail($email);

        if (!$user) {
            abort(404, 'User not found');
        }

        $otp = $this->generateOtp();

        $this->passwordResetRepositoryInterface->createOrUpdateOtp($email, $otp);

        $this->sendMail($email, $otp, $user->name);
    }

    public function resetPassword(array $data): void
    {
        $record = $this->passwordResetRepositoryInterface->getValidOtp(
            $data['email'],
            $data['otp']
        );

        if (!$record) {
            abort(400, 'Invalid OTP');
        }

        if (Carbon::parse($record->expires_at)->isPast()) {
            abort(400, 'OTP expired');
        }

        $this->authRepositoryInterface->updatePassword($data['email'], $data['password']);
        $this->passwordResetRepositoryInterface->deleteOtp($data['email']);
    }

    public function generateOtp(): string
    {
        return (string) random_int(100000, 999999);
    }

    public function sendMail(string $toEmail, string $otp, string $userName): void
    {
        Mail::to($toEmail)->send(new ResetPasswordMail($otp, $userName));
    }
}
