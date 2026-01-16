<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use App\Http\Resources\UserProfileResource;
use App\Services\ProfileService;
use Illuminate\Http\Request;


class ProfileController extends Controller
{
    public function __construct(
        protected ProfileService $profileService
    ) {}

    public function store(ProfileRequest $request)
    {
        $profile = $this->profileService->save(
            $request->user(),
            $request->validated()
        );

        return response()->json([
            'message' => 'Profile saved successfully',
            'profile' => new UserProfileResource($profile),
        ]);
    }
}
