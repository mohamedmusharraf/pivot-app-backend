<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStreakLogsRequest;
use App\Http\Resources\StreakLogsResource;
use App\Services\AppLogs\StreakLogsService;
use Illuminate\Http\Request;

class StreakLogsController extends Controller
{
    public function __construct(
        protected StreakLogsService $streakLogsService
    ){}

    public function store(StoreStreakLogsRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;
        $streakLog = $this->streakLogsService->store($data);
        return New StreakLogsResource($streakLog);
    }
}
