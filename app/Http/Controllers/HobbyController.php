<?php

namespace App\Http\Controllers;

use App\Models\Hobby;
use App\Http\Requests\HobbyRequest;
use App\Http\Resources\HobbyResource;
use App\Services\HobbyService;

class HobbyController extends Controller
{
    public function __construct(
        protected HobbyService $hobbyService
    ) {}

    public function index()
    {
        $hobbies = $this->hobbyService->list();

        return response()->json([
            HobbyResource::collection($hobbies)
        ]);
    }

    public function show(Hobby $hobby)
    {
        $hobby->load('activities');

        return response()->json([
            new HobbyResource($hobby)
        ]);
    }

    public function store(HobbyRequest $request)
    {
        $hobby = $this->hobbyService->store($request->validated());

        return response()->json([
            new HobbyResource($hobby)
        ], 201);
    }

    public function update(HobbyRequest $request, Hobby $hobby)
    {
        $updatedHobby = $this->hobbyService->update(
            $hobby,
            $request->validated()
        );

        return response()->json([
            new HobbyResource($updatedHobby)
        ]);
    }

    public function destroy(Hobby $hobby)
    {
        $this->hobbyService->delete($hobby);

        return response()->json([
            'message' => 'Hobby deleted successfully'
        ]);
    }
}
