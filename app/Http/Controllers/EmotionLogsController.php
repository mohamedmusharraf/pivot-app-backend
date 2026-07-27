<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmotionLogsRequest;
use App\Services\AppLogs\EmotionLogsService;
use Illuminate\Http\JsonResponse;

class EmotionLogsController extends Controller
{
    public function __construct(
        protected EmotionLogsService $emotionLogsService
    ) {}

    public function store(StoreEmotionLogsRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();

            $this->emotionLogsService->storeBatch(
                $request->user()->id,
                $validated['events']
            );

            return response()->json([
                'success' => true,
                'message' => 'Emotion logs stored successfully.',
            ], 201);

        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to store emotion log.',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}