<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Services\AppLogs\FocusSessionLogsService;
use App\Http\Requests\StoreFocusSessionLogsRequest;

class FocusSessionLogsController extends Controller
{
    public function __construct(
        protected FocusSessionLogsService $focusSessionLogsService
    ) {}

    public function store(StoreFocusSessionLogsRequest $request): JsonResponse
    {
        try {
            $this->focusSessionLogsService->store(
                $request->user()->id,
                $request->validated()['events']
            );

            return response()->json([
                'success' => true,
                'message' => 'Focus session logs saved successfully.'
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create focus session logs.',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}