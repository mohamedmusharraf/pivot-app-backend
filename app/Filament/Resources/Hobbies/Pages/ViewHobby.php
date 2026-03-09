<?php

namespace App\Filament\Resources\Hobbies\Pages;

use App\Filament\Resources\Hobbies\HobbyResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewHobby extends ViewRecord
{
    protected static string $resource = HobbyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
