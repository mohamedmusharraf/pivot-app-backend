<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AppLogs\EmotionLogsService;
use App\Http\Requests\StoreEmotionLogsRequest;
use App\Http\Resources\EmotionLogsResource;

class EmotionLogsController extends Controller
{
    public function __construct(
        protected EmotionLogsService $emotionLogsService
    ){}


    public function store(StoreEmotionLogsRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;
        $emotionLog = $this->emotionLogsService->store($data);
        return New EmotionLogsResource($emotionLog);
    }
}
