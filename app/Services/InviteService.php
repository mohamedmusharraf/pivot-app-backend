<?php

namespace App\Services;

use App\Models\Invitation;
use App\Models\User;
use Illuminate\Support\Str;
use App\DTO\TeamConnectionDTO;

class InviteService
{
    public function createInvite(User $inviter): array
    {
        $rawToken = $this->generateUniqueRawToken();

        $invite = Invitation::create([
            'inviter_id' => $inviter->id,
            'token' => hash('sha256', $rawToken),
            'code' => $this->generateUniqueCode(),
            'expires_at' => now()->addDays(7),
            'status' => Invitation::STATUS_PENDING,
        ]);

        return [
            'invite' => $invite,
            'token' => $rawToken,
        ];
    }

    public function resolvePendingInviteByToken(string $rawToken): ?Invitation
    {
        $normalizedToken = trim($rawToken);
        $hashedToken = hash('sha256', $normalizedToken);

        return Invitation::with('inviter')
            ->where(function ($query) use ($normalizedToken, $hashedToken) {
                $query->where('token', $hashedToken)
                    ->orWhere('token', $normalizedToken);
            })
            ->where('status', Invitation::STATUS_PENDING)
            ->where('expires_at', '>', now())
            ->first();
    }

    public function resolvePendingInviteByCode(string $code): ?Invitation
    {
        $normalizedCode = strtoupper(trim($code));

        return Invitation::with('inviter')
            ->where('code', $normalizedCode)
            ->where('status', Invitation::STATUS_PENDING)
            ->where('expires_at', '>', now())
            ->first();
    }

    private function generateUniqueRawToken(): string
    {
        do {
            $rawToken = Str::random(48);
            $hashedToken = hash('sha256', $rawToken);
        } while (Invitation::where('token', $hashedToken)->exists());

        return $rawToken;
    }

    private function generateUniqueCode(): string
    {
        do {
            $code = strtoupper(Str::random(6));
        } while (Invitation::where('code', $code)->exists());

        return $code;
    }
}
