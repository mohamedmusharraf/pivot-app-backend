<?php

namespace App\Filament\Resources\Activities\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ActivityInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('hobby.name')
                    ->label('Hobby')
                    ->placeholder('-'),
                TextEntry::make('activity_title'),
                TextEntry::make('activity_type'),
                TextEntry::make('subcategory'),
                TextEntry::make('instruction'),
                // ->columnSpanFull(),
                TextEntry::make('duration_minutes')
                    ->numeric(),
                TextEntry::make('tier'),
                TextEntry::make('cost'),
                TextEntry::make('location'),
                TextEntry::make('age_range')
                    ->placeholder('-'),
                IconEntry::make('neurodivergent_friendly')
                    ->boolean(),
                TextEntry::make('neurodivergent_notes')
                    ->label('Neurodivergent Friendly Notes')
                    // ->columnSpanFull()
                    ->placeholder('-'),
                TextEntry::make('sensory_tags'),
                TextEntry::make('social_type'),
                TextEntry::make('energy_level'),
                TextEntry::make('outcome_tag'),
                TextEntry::make('mood_match'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
