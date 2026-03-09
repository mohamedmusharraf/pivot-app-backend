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
                TextEntry::make('gender')
                    ->placeholder('-'),
                TextEntry::make('age_range')
                    ->placeholder('-'),
                TextEntry::make('screen_goal_hours')
                    ->numeric()
                    ->placeholder('-'),
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
