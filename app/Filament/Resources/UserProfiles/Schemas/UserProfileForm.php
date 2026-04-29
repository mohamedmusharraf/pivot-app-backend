<?php

namespace App\Filament\Resources\UserProfiles\Schemas;

use App\Models\country;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
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
                Select::make('country')
                    ->options(fn (): array => country::query()
                        ->where('is_active', true)
                        ->orderBy('name')
                        ->pluck('name', 'name')
                        ->toArray())
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('gender')
                    ->options([
                        'male' => 'Male',
                        'female' => 'Female',
                        'other' => 'Other',
                        'prefer not to say' => 'Prefer not to say'
                    ]),
                DatePicker::make('date_of_birth'),

                TextInput::make('screen_goal_minutes')
                    ->label('Screen Goal Minutes')
                    ->numeric(),
                Toggle::make('onboarding_completed')
                    ->required(),
            ]);
    }
}
