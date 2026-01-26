<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hobby;
use App\Http\Requests\HobbyRequest;
use App\Http\Resources\HobbyResource;
use App\Repositories\Contracts\HobbyRepositoryInterface;

class HobbyController extends Controller
{
    public function __construct(
        protected HobbyRepositoryInterface $hobbyRepo
    ) {}

    /**
     * GET /api/v1/hobbies
     */
    public function index()
    {
        return response()->json([
            'data' => HobbyResource::collection($this->hobbyRepo->all())
        ]);
    }

    /**
     * GET /api/v1/hobbies/{hobby}
     */
    public function show(Hobby $hobby)
    {
        $hobby->load('activities');
        return response()->json(
            new HobbyResource($hobby)
        );
    }

    /**
     * POST /api/v1/hobbies
     */
    public function store(HobbyRequest $request)
    {
        $hobby = $this->hobbyRepo->create($request->validated());

        return response()->json([
            'data' => new HobbyResource($hobby)
        ], 201);
    }

    /**
     * PUT /api/v1/hobbies/{hobby}
     */
    public function update(HobbyRequest $request, Hobby $hobby)
    {
        $updatedHobby = $this->hobbyRepo->update(
            $hobby,
            $request->validated()
        );

        return response()->json([
            'data' => new HobbyResource($updatedHobby)
        ]);
    }

    /**
     * DELETE /api/v1/hobbies/{hobby}
     */
    public function destroy(Hobby $hobby)
    {
        $this->hobbyRepo->delete($hobby);

        return response()->json([
            'message' => 'Hobby deleted successfully'
        ]);
    }
}
