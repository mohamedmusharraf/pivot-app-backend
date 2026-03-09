<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResearchRequest;
use App\Http\Resources\ResearchResource;
use App\Models\Research;
use App\Services\ResearchService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class ResearchController extends Controller
{
    public function __construct(protected ResearchService $researchService) {}

    public function index(): JsonResponse
    {
        $researches = $this->researchService->list();
        return response()->json(ResearchResource::collection($researches));
    }

    public function show(Research $research): JsonResponse
    {
        return response()->json(new ResearchResource($research));
    }

    public function store(ResearchRequest $request): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('files')) {
            $data['files'] = $request->file('files')->store('research_files', 'public');
        }

        $research = $this->researchService->store($data);

        return response()->json(new ResearchResource($research), 201);
    }

    public function update(ResearchRequest $request, Research $research): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('files')) {
            if ($research->files) {
                Storage::disk('public')->delete($research->files);
            }
            $data['files'] = $request->file('files')->store('research_files', 'public');
        }

        $updatedResearch = $this->researchService->update($research, $data);

        return response()->json(new ResearchResource($updatedResearch));
    }

    public function destroy(Research $research): JsonResponse
    {
        if ($research->files) {
            Storage::disk('public')->delete($research->files);
        }

        $this->researchService->delete($research);

        return response()->json(['message' => 'Research deleted successfully']);
    }
}