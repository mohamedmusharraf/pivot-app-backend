<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\UpdateUserStatusRequest;
use App\Http\Resources\UserCountryResource;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Http\Request;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\VerifyOtpRequest;
use App\Models\DeviceFingerprint;
use App\Models\Subscription;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ) {}

    public function register(RegisterRequest $request)
    {
        $result = $this->authService->register($request->validated());

        return response()->json([
            'token' => $result['token'],
            'user'  => new UserResource($result['user']),
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        $result = $this->authService->login($request->validated());

        return response()->json([
            'token' => $result['token'],
            'user'  => new UserResource($result['user']),
        ]);
    }

    public function logout(Request $request)
    {
        $this->authService->logout($request->user());

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }

    public function currentUser(Request $request)
    {
        $user = $this->authService->getCurrentUser();

        return response()->json([
            'user' => new UserResource($user)
        ]);
    }

    public function currentUserCountry(Request $request)
    {
        $user = $request->user();
        $user->loadMissing('profile.country');

        $country = $user->profile?->country;

        return response()->json([
            'country' => $country ? new UserCountryResource($country) : null,
        ]);
    }

    public function updateStatus(UpdateUserStatusRequest $request)
    {
        $user = $this->authService->updateCurrentUserStatus($request->validated()['status']);

        return response()->json([
            'message' => 'User status updated successfully',
            'user' => new UserResource($user),
        ]);
    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $this->authService->sendResetPasswordEmail($request->email);

        return response()->json([
            'status'  => 'success',
            'message' => 'OTP has been sent to your email address.',
        ]);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $this->authService->resetPassword($request->validated());

        return response()->json([
            'status'  => 'success',
            'message' => 'Password has been successfully updated.',
        ]);
    }

    public function verifyOtp(VerifyOtpRequest $request)
    {
        $resetToken = $this->authService->verifyOtp($request->validated());

        return response()->json([
            'status'      => 'success',
            'message'     => 'OTP verified successfully.',
            'reset_token' => $resetToken,
        ]);
    }

    public function deleteAccount(Request $request)
    {
        $user = $request->user();

        if ($user) {
            if ($user->profile) {
                $user->profile()->delete();
            }
            $user->hobbies()->detach();

            DeviceFingerprint::where('user_id', $user->id)->delete();

            Subscription::where('user_id', $user->id)->delete();

            $user->tokens()->delete();
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'User account deleted successfully'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'User not found'
        ], 404);
    }
}
