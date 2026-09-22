<?php

namespace App\Services;

use App\Repositories\Contracts\EventRepositoryInterface;

class EventService
{
    protected $eventRepository;

    public function __construct(EventRepositoryInterface $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    public function getActiveEvents()
    {
        return $this->eventRepository->getActiveEvents();
    }

    public function getEventDetails($id)
    {
        return $this->eventRepository->findActiveEvent($id);
    }
}
