<?php

namespace App\Filament\Resources\Research\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ResearchForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                Textarea::make('research_summary')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('research_full_text')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('files'),
                TextInput::make('category')
                    ->required(),
            ]);
    }
}
