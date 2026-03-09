<?php

namespace App\Filament\Resources\UserProfiles\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UserProfileForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                TextInput::make('gender'),
                TextInput::make('age_range'),
                TextInput::make('screen_goal_hours')
                    ->numeric(),
                Toggle::make('onboarding_completed')
                    ->required(),
            ]);
    }
}
