<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AppLogs\GoalLogsService;
use App\Http\Requests\StoreGoalLogsRequest;

class GoalLogsController extends Controller
{
    public function __construct(
        protected GoalLogsService $goalLogsService
    ){}
    

    public function store(StoreGoalLogsRequest $request)
    {
        try {
            $data = $request->validated();
            $data['user_id'] = $request->user()->id;

            $goalLog = $this->goalLogsService->store($data);

            return response()->json([
                'message' => 'Goal log created successfully.',
                'data' => $goalLog
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Failed to create Goal log.',
                'error' => $th->getMessage()
            ], 500);
        };
    }
}
