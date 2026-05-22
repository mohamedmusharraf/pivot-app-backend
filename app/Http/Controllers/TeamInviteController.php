<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Invitation;
use App\Models\TeamConnection;
use App\Services\InviteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeamInviteController extends Controller
{
    public function __construct(
        protected InviteService $inviteService
    ) {}

    public function generate(Request $request): JsonResponse
    {
        $user = $request->user();
        $result = $this->inviteService->createInvite($user);
        $invitation = $result['invite'];
        $token = $result['token'];

        $inviteUrl = "https://api.pivotirl.com.au/invite?token={$token}";
        $shareMessage = "Join my team on Pivot!\nTap the link: {$inviteUrl}\nOr open the app and enter code: {$invitation->code}";

        return response()->json([
            'success' => true,
            'invite_id' => $invitation->id,
            'invite_url' => $inviteUrl,
            'code' => $invitation->code,
            'expires_at' => $invitation->expires_at,
            'share_message' => $shareMessage,
        ]);
    }

    public function resolveByToken(string $token): JsonResponse
    {
        $invitation = $this->inviteService->resolvePendingInviteByToken($token);
        if (! $invitation) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired invitation'
            ], 404);
        }

        return $this->buildPreview($invitation);
    }

    public function resolveByCode(Request $request): JsonResponse
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'size:6', 'regex:/^[A-Za-z0-9]{6}$/'],
        ]);

        $invitation = $this->inviteService->resolvePendingInviteByCode($data['code']);
        if (! $invitation) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired invitation'
            ], 404);
        }

        return $this->buildPreview($invitation);
    }

    public function accept(Request $request): JsonResponse
    {
        $data = $request->validate([
            'invite_id' => ['required', 'integer', 'exists:invitations,id'],
        ]);

        $user = $request->user();
        $invitation = Invitation::find($data['invite_id']);

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
            'message' => 'Joined successfully'
        ]);
    }

    private function buildPreview(Invitation $invitation): JsonResponse
    {
        $memberCount = TeamConnection::where('user_id', $invitation->inviter_id)->count() + 1;

        return response()->json([
            'success' => true,
            'invite_id' => $invitation->id,
            'inviter' => [
                'id' => $invitation->inviter->id,
                'name' => $invitation->inviter->name,
                'avatar' => $invitation->inviter->avatar ?? null,
            ],
            'team' => [
                'name' => $invitation->inviter->name . "'s Team",
                'member_count' => $memberCount,
            ],
            'code' => $invitation->code,
            'expires_at' => $invitation->expires_at,
        ]);
    }

    // Backward-compatible alias for old route.
    public function preview(string $token): JsonResponse
    {
        return $this->resolveByToken($token);
    }
}
