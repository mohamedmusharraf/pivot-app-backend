<?php

namespace App\Http\Controllers;

use App\Events\NewTeamConnectionAdded;
use App\Http\Controllers\Controller;
use App\Models\Invitation;
use App\Models\TeamConnection;
use App\Models\Users;
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

    public function joinByCode(Request $request): JsonResponse
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'size:6', 'regex:/^[A-Za-z0-9]{6}$/'],
        ]);

        $user = $request->user();
        $invitation = $this->inviteService->resolvePendingInviteByCode($data['code']);

        if (! $invitation) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired invitation'
            ], 404);
        }

        if ($invitation->inviter_id == $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot accept your own invite'
            ], 400);
        }

        $connection = $this->acceptInvitation($invitation, $user);

        return response()->json([
            'success' => true,
            'message' => 'Joined successfully',
            'connection' => $connection,
        ]);
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

        $connection = $this->acceptInvitation($invitation, $user);

        return response()->json([
            'success' => true,
            'message' => 'Joined successfully',
            'connection' => $connection,
        ]);
    }

    public function reject(Request $request): JsonResponse
    {
        $data = $request->validate([
            'invite_id' => ['required', 'integer', 'exists:invitations,id'],
        ]);

        $user = $request->user();
        $invitation = Invitation::find($data['invite_id']);

        if (! $invitation) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid invitation'
            ], 404);
        }

        if ($invitation->status === Invitation::STATUS_REJECTED) {
            return response()->json([
                'success' => false,
                'message' => 'Invitation already rejected'
            ], 422);
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
                'message' => 'You cannot reject your own invite'
            ], 400);
        }

        $invitation->update([
            'status' => Invitation::STATUS_REJECTED
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Invitation rejected successfully'
        ]);
    }

    public function connectedUsers(Request $request): JsonResponse
    {
        $user = $request->user();

        $connections = TeamConnection::query()
            ->where('user_id', $user->id)
            ->with(['connectedUser.profile.country'])
            ->latest()
            ->get();

        $connectedUsers = $connections
            ->map(function (TeamConnection $connection) {
                $connectedUser = $connection->connectedUser;

                if (! $connectedUser) {
                    return null;
                }

                return [
                    'connection_id' => $connection->id,
                    'connected_at' => $connection->created_at,
                    'connected_user' => [
                        'id' => $connectedUser->id,
                        'name' => $connectedUser->name,
                        'email' => $connectedUser->email,
                        'status' => $connectedUser->status,
                        'country' => $connectedUser->profile?->country?->name,
                        'gender' => $connectedUser->profile?->gender,
                        'date_of_birth' => $connectedUser->profile?->date_of_birth,
                        'onboarding_completed' => $connectedUser->profile?->onboarding_completed,
                    ],
                ];
            })
            ->filter()
            ->values();

        return response()->json([
            'success' => true,
            'count' => $connectedUsers->count(),
            'connections' => $connectedUsers,
        ]);
    }

    public function removeConnection(Request $request, int $connection): JsonResponse
    {
        $user = $request->user();
        $teamConnection = TeamConnection::find($connection);

        if (! $teamConnection) {
            return response()->json([
                'success' => false,
                'message' => 'Team member not found'
            ], 404);
        }

        if ($teamConnection->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to remove this team member'
            ], 403);
        }

        DB::transaction(function () use ($teamConnection, $user) {
            TeamConnection::query()
                ->where(function ($query) use ($user, $teamConnection) {
                    $query->where('user_id', $user->id)
                        ->where('connected_user_id', $teamConnection->connected_user_id);
                })
                ->orWhere(function ($query) use ($user, $teamConnection) {
                    $query->where('user_id', $teamConnection->connected_user_id)
                        ->where('connected_user_id', $user->id);
                })
                ->delete();
        });

        return response()->json([
            'success' => true,
            'message' => 'Team member removed successfully'
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

    public function preview(string $token): JsonResponse
    {
        return $this->resolveByToken($token);
    }

    private function acceptInvitation(Invitation $invitation, Users $user): array
    {
        DB::transaction(function () use ($invitation, $user) {
            TeamConnection::firstOrCreate([
                'user_id' => $invitation->inviter_id,
                'connected_user_id' => $user->id,
            ]);

            TeamConnection::firstOrCreate([
                'user_id' => $user->id,
                'connected_user_id' => $invitation->inviter_id,
            ]);

            $invitation->update([
                'status' => Invitation::STATUS_ACCEPTED,
            ]);
        });

        $invitation->loadMissing('inviter');

        $connection = [
            'invite_id' => $invitation->id,
            'inviter' => [
                'id' => $invitation->inviter->id,
                'name' => $invitation->inviter->name,
                'avatar' => $invitation->inviter->avatar ?? null,
            ],
            'connected_user' => [
                'id' => $user->id,
                'name' => $user->name,
                'avatar' => $user->avatar ?? null,
            ],
            'team_member_count' => TeamConnection::where('user_id', $invitation->inviter_id)->count() + 1,
            'status' => Invitation::STATUS_ACCEPTED,
            'accepted_at' => now()->toIso8601String(),
        ];

        event(new NewTeamConnectionAdded($connection));

        return $connection;
    }
}
