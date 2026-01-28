<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserHobby;
use App\Models\Hobby;
use App\Http\Requests\UserHobbyRequest;
use App\Http\Resources\HobbyResource;
use App\Models\Users;
use App\Services\UserHobbyService;
use Illuminate\Support\Facades\Auth;

class UserHobbyController extends Controller
{
    public function __construct(
        protected UserHobbyService $userHobbyService
    ) {}

    public function store(UserHobbyRequest $request)
    {
        /** @var Users $user */
        $user = $request->user();

        $this->userHobbyService->selectHobbies(
            $user,
            $request->validated()['hobby_ids']
        );

        return response()->json([
            'message' => 'Hobbies saved successfully'
        ], 201);
    }

    public function index()
    {
        $userHobbies = $this->userHobbyService->getUserHobbies();

        return HobbyResource::collection(
            $userHobbies->pluck('hobby')
        );
    }

    public function show(Request $request, UserHobby $user_hobby)
    {
        $hobby = $this->userHobbyService->getUserHobby($request, $user_hobby);

        return new HobbyResource($hobby);
    }

    public function update(UserHobbyRequest $request, UserHobby $user_hobby)
    {
        $hobby = $this->userHobbyService->update($request, $user_hobby, $request->validated());

        return new HobbyResource($hobby);
    }

    public function destroy(Request $request, UserHobby $user_hobby)
    {
        $this->userHobbyService->delete($request, $user_hobby);

        return response()->json([
            'message' => 'Hobby removed successfully'
        ]);
    }
}
