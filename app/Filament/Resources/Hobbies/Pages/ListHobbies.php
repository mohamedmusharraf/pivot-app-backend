<?php

namespace App\Filament\Resources\Hobbies\Pages;

use App\Filament\Resources\Hobbies\HobbyResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListHobbies extends ListRecords
{
    protected static string $resource = HobbyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
