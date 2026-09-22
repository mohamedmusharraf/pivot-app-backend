<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Event\ShowEventRequest;
use App\Http\Resources\EventResource;
use App\Services\EventService;
use Illuminate\Http\Request;

class EventController extends Controller
{
    protected $eventService;

    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;
    }

    /**
     * Get list of active events
     */
    public function index(Request $request)
    {
        $events = $this->eventService->getActiveEvents();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Events retrieved successfully',
            'data' => EventResource::collection($events),
        ]);
    }

    /**
     * Get specific event details
     */
    public function show($id, ShowEventRequest $request)
    {
        $event = $this->eventService->getEventDetails($id);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Event details retrieved successfully',
            'data' => new EventResource($event),
        ]);
    }
}
