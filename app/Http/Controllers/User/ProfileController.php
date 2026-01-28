<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use App\Http\Resources\UserProfileResource;
use App\Services\ProfileService;
use Illuminate\Http\Request;
use App\Models\UserProfile;


class ProfileController extends Controller
{
    public function __construct(
        protected ProfileService $profileService
    ) {}

    public function store(ProfileRequest $request)
    {
        $profile = $this->profileService->store($request->validated());

        return response()->json([
            new UserProfileResource($profile)
        ], 201);
    }

    public function index()
    {
        $profiles = $this->profileService->list();

        return response()->json([
            UserProfileResource::collection($profiles)
        ]);
    }

    public function show(UserProfile $profile)
    {

        return response()->json([
            new UserProfileResource($profile)
        ]);
    }

    public function update(ProfileRequest $request, UserProfile $profile)
    {
        $this->profileService->update(
            $profile,
            $request->validated()
        );

        return response()->json([
            'message' => "data updated successfully"
        ]);
    }

    public function destroy(UserProfile $profile)
    {
        $this->profileService->delete($profile);

        return response()->json([
            'message' => 'Profile deleted successfully'
        ]);
    }
}
