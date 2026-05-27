<?php

namespace App\Filament\Resources\UserProfiles\Schemas;

use App\Models\Country;
use App\Models\User;
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
                Select::make('user_id')
                    ->label('User')
                    ->options(fn(): array => User::query()
                        ->pluck('name', 'id')
                        ->toArray())
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('country')
                    ->options(fn(): array => Country::query()
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
                TextInput::make('birth_year')
                    ->numeric()
                    ->minValue(1900)
                    ->maxValue(date('Y')),
                TextInput::make('weekly_goal_minutes')
                    ->label('Weekly Goal (Hours)')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(168)
                    ->suffix('hours')
                    ->formatStateUsing(fn($state) => $state ? (int) ($state / 60) : null)
                    ->dehydrateStateUsing(fn($state) => $state ? (int) $state * 60 : null),
                Toggle::make('onboarding_completed')
                    ->required(),
            ]);
    }
}
