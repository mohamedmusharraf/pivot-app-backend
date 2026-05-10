<?php

namespace App\Filament\Resources\UserProfiles\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class UserProfileInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user_id')
                    ->numeric(),
                TextEntry::make('country.name')
                    ->label('Country')
                    ->placeholder('-'),
                TextEntry::make('gender')
                    ->placeholder('-'),
                TextEntry::make('date_of_birth')
                    ->placeholder('Date of Birth'),
                TextEntry::make('weekly_goal_minutes')
                    ->label('Weekly Goal (Hours)')
                    ->formatStateUsing(fn($state) => $state ? (int) ($state / 60) . ' hours' : '-'),
                IconEntry::make('onboarding_completed')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
