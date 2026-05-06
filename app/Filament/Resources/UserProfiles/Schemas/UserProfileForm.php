<?php

namespace App\Filament\Resources\UserProfiles\Schemas;

use App\Models\country;
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
                    ->options(fn(): array => country::query()
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

                TextInput::make('set_your_goal')
                    ->label('Set Your Goal')
                    ->maxLength(50),
                Toggle::make('onboarding_completed')
                    ->required(),
            ]);
    }
}
