<?php

namespace App\Http\Controllers;

use App\Models\GroupChallengeSession;
use App\Services\GroupChallengeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GroupChallengeController extends Controller
{
    public function __construct(
        protected GroupChallengeService $groupChallengeService
    ) {}

    public function start(Request $request): JsonResponse
    {
        $data = $request->validate([
            'teammate_ids' => ['required', 'array', 'min:1'],
            'teammate_ids.*' => ['integer', 'distinct'],
            'challenge_id' => ['nullable', 'integer', 'exists:activities,id'],
        ]);

        $session = $this->groupChallengeService->start(
            $request->user(),
            $data['teammate_ids'],
            $data['challenge_id'] ?? null,
        );

        return response()->json([
            'success' => true,
            'session' => $session,
        ], 201);
    }

    public function show(Request $request, GroupChallengeSession $session): JsonResponse
    {
        $session = $this->groupChallengeService->getSessionForUser($request->user(), $session);

        return response()->json([
            'success' => true,
            'session' => $session,
        ]);
    }

    public function invite(Request $request, GroupChallengeSession $session): JsonResponse
    {
        $data = $request->validate([
            'invited_user_ids' => ['required', 'array', 'min:1'],
            'invited_user_ids.*' => ['integer', 'distinct'],
        ]);

        $session = $this->groupChallengeService->inviteMore($request->user(), $session, $data['invited_user_ids']);

        return response()->json([
            'success' => true,
            'session' => $session,
        ], 201);
    }

    public function accept(Request $request, GroupChallengeSession $session): JsonResponse
    {
        $participant = $this->groupChallengeService->accept($request->user(), $session);

        return response()->json([
            'success' => true,
            'participant' => $participant,
        ]);
    }

    public function decline(Request $request, GroupChallengeSession $session): JsonResponse
    {
        $participant = $this->groupChallengeService->decline($request->user(), $session);

        return response()->json([
            'success' => true,
            'participant' => $participant,
        ]);
    }

    public function begin(Request $request, GroupChallengeSession $session): JsonResponse
    {
        $session = $this->groupChallengeService->begin($request->user(), $session);

        return response()->json([
            'success' => true,
            'session' => $session,
        ]);
    }

    public function cancel(Request $request, GroupChallengeSession $session): JsonResponse
    {
        $session = $this->groupChallengeService->cancelByHost($request->user(), $session);

        return response()->json([
            'success' => true,
            'session' => $session,
        ]);
    }

    public function pause(Request $request, GroupChallengeSession $session): JsonResponse
    {
        $session = $this->groupChallengeService->pause($request->user(), $session);

        return response()->json([
            'success' => true,
            'session' => $session,
        ]);
    }

    public function resume(Request $request, GroupChallengeSession $session): JsonResponse
    {
        $session = $this->groupChallengeService->resume($request->user(), $session);

        return response()->json([
            'success' => true,
            'session' => $session,
        ]);
    }

    public function leave(Request $request, GroupChallengeSession $session): JsonResponse
    {
        $participant = $this->groupChallengeService->leave($request->user(), $session);

        return response()->json([
            'success' => true,
            'participant' => $participant,
        ]);
    }

    public function progress(Request $request, GroupChallengeSession $session): JsonResponse
    {
        $data = $request->validate([
            'progress' => ['required', 'integer', 'min:0', 'max:100'],
        ]);

        $participant = $this->groupChallengeService->updateProgress($request->user(), $session, $data['progress']);

        return response()->json([
            'success' => true,
            'participant' => $participant,
        ]);
    }

    public function complete(Request $request, GroupChallengeSession $session): JsonResponse
    {
        $participant = $this->groupChallengeService->complete($request->user(), $session);

        return response()->json([
            'success' => true,
            'participant' => $participant,
        ]);
    }
}
