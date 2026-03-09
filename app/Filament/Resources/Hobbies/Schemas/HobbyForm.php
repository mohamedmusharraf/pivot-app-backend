<?php

namespace App\Filament\Resources\Hobbies\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class HobbyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('icon_url')
                    ->url(),
            ]);
    }
}
