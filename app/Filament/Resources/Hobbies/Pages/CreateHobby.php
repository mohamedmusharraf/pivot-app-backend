<?php

namespace App\Filament\Resources\Hobbies\Pages;

use App\Filament\Resources\Hobbies\HobbyResource;
use Filament\Resources\Pages\CreateRecord;

class CreateHobby extends CreateRecord
{
    protected static string $resource = HobbyResource::class;
}
