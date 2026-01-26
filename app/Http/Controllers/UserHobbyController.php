<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserHobbyRequest;
use App\Services\UserHobbyService;

class UserHobbyController extends Controller
{
    public function __construct(
        protected UserHobbyService $service
    ) {}

    public function store(UserHobbyRequest $request)
    {
        $this->service->sync(
            $request->user(),
            $request->hobby_ids
        );

        return response()->json([
            'message' => 'Hobbies saved successfully'
        ]);
    }
}

