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
            $data = $request->validated();
            $data['user_id'] = $request->user()->id;

            $focusSessionLog = $this->focusSessionLogsService->store($data);

            return response()->json([
                'message' => 'Focus session log created successfully.',
                'data' => $focusSessionLog
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Failed to create Focus session log.',
                'error' => $th->getMessage()
            ], 500);
        };
    }
}
