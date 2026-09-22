<?php

namespace App\Repositories\Contracts;

interface EventRepositoryInterface
{
    public function getActiveEvents();
    public function findActiveEvent($id);
}
