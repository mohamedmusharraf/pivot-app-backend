<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserProfileResource;
use App\Services\ProfileService;
use Illuminate\Http\Request;
use App\Models\UserProfile;
use App\Models\country;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class ProfileController extends Controller
{
    public function __construct(
        protected ProfileService $profileService
    ) {}

    public function store(ProfileRequest $request)
    {
        $profile = $this->profileService->store($request->validated());

        return (new UserProfileResource($profile))
            ->response()
            ->setStatusCode(201);
    }

    public function index()
    {
        $profiles = $this->profileService->list();

        return UserProfileResource::collection($profiles);
    }

    public function countries()
    {
        $options = country::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['name'])
            ->map(fn ($row) => [
                'value' => $row->name,
                'label' => $row->name,
            ])
            ->values();

        return response()->json([
            'countries' => $options,
        ]);
    }

    public function show(UserProfile $profile)
    {
        return new UserProfileResource($profile);
    }

    public function me(Request $request)
    {
        try {
            $profile = $this->profileService->getByUserId($request->user()->id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'user' => new UserResource($request->user()),
            ]);
        }

        return response()->json([
            'user' => new UserResource($request->user()),
            'profile' => new UserProfileResource($profile),
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
