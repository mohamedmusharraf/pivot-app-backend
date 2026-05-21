<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Invitation;
use App\Models\TeamConnection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TeamInviteController extends Controller
{
    public function generate(Request $request): JsonResponse
    {
        $user = $request->user();

        $token = $this->generateUniqueToken();

        Invitation::create([
            'inviter_id' => $user->id,
            'token' => $token,
            'expires_at' => now()->addHours(48),
            'status' => Invitation::STATUS_PENDING
        ]);

        $inviteUrl = "https://pivotapp.com/invite?token={$token}";

        return response()->json([
            'success' => true,
            'invite_url' => $inviteUrl
        ]);
    }

    public function preview(string $token): JsonResponse
    {
        $invitation = Invitation::with('inviter')
            ->where('token', $token)
            ->where('status', Invitation::STATUS_PENDING)
            ->first();

        if (!$invitation) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid invitation'
            ], 404);
        }

        if ($invitation->expires_at->isPast()) {
            return response()->json([
                'success' => false,
                'message' => 'Invitation expired'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'inviter' => [
                'id' => $invitation->inviter->id,
                'name' => $invitation->inviter->name,
                'avatar' => $invitation->inviter->avatar ?? null
            ]
        ]);
    }

    public function accept(Request $request): JsonResponse
    {
        $request->validate([
            'token' => 'required|string'
        ]);

        $user = $request->user();

        $invitation = Invitation::where('token', $request->token)->first();

        if (!$invitation) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid invitation'
            ], 404);
        }

        if ($invitation->status === Invitation::STATUS_ACCEPTED) {
            return response()->json([
                'success' => false,
                'message' => 'Invitation already accepted'
            ], 422);
        }

        if ($invitation->status !== Invitation::STATUS_PENDING) {
            return response()->json([
                'success' => false,
                'message' => 'Invitation is not redeemable'
            ], 422);
        }

        if ($invitation->expires_at->isPast()) {
            return response()->json([
                'success' => false,
                'message' => 'Invitation expired'
            ], 422);
        }

        if ($invitation->inviter_id == $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot accept your own invite'
            ], 400);
        }

        DB::transaction(function () use ($invitation, $user) {
            TeamConnection::firstOrCreate([
                'user_id' => $invitation->inviter_id,
                'connected_user_id' => $user->id
            ]);

            TeamConnection::firstOrCreate([
                'user_id' => $user->id,
                'connected_user_id' => $invitation->inviter_id
            ]);

            $invitation->update([
                'status' => Invitation::STATUS_ACCEPTED
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Team member added successfully'
        ]);
    }

    private function generateUniqueToken(): string
    {
        do {
            $token = Str::random(15);
        } while (Invitation::where('token', $token)->exists());

        return $token;
    }
}
