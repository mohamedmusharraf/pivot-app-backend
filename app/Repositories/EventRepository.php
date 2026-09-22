<?php

namespace App\Repositories;

use App\Models\Event;
use App\Repositories\Contracts\EventRepositoryInterface;

class EventRepository implements EventRepositoryInterface
{
    public function getActiveEvents()
    {
        return Event::where('is_active', true)
            ->where('date_and_time', '>=', now())
            ->orderBy('date_and_time', 'asc')
            ->get();
    }

    public function findActiveEvent($id)
    {
        return Event::where('is_active', true)
            ->findOrFail($id);
    }
}
