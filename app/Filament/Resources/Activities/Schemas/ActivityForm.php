<?php

namespace App\Filament\Resources\Activities\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ActivityForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('hobby_id')
                    ->label('Hobby')
                    ->relationship('hobby', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('activity_title')
                    ->required(),
                Textarea::make('instruction')
                    ->required(),
                    // ->columnSpanFull(),
                TextInput::make('activity_type'),
                TextInput::make('subcategory'),
                TextInput::make('duration_minutes')
                    ->required()
                    ->numeric(),
                TextInput::make('tier'),
                TextInput::make('cost'),
                TextInput::make('location'),
                TextInput::make('age_range'),
                TextInput::make('sensory_tags'),
                TextInput::make('social_type'),
                TextInput::make('outcome_tag'),
                TextInput::make('mood_match'),
                TextInput::make('energy_level')
                    ->required(),
                TextInput::make('neurodivergent_notes')
                    ->label('Neurodivergent Friendly Notes'),
                    // ->columnSpanFull(),
                Select::make('neurodivergent_friendly')
                    ->options([
                        'Yes' => 'Yes',
                        'No' => 'No',
                        'Partial' => 'Partial',
                    ])
                    ->required(),
            ]);
    }
}
