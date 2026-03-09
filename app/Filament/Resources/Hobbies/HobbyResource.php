<?php

namespace App\Filament\Resources\Hobbies;

use App\Filament\Resources\Hobbies\Pages\CreateHobby;
use App\Filament\Resources\Hobbies\Pages\EditHobby;
use App\Filament\Resources\Hobbies\Pages\ListHobbies;
use App\Filament\Resources\Hobbies\Pages\ViewHobby;
use App\Filament\Resources\Hobbies\Schemas\HobbyForm;
use App\Filament\Resources\Hobbies\Schemas\HobbyInfolist;
use App\Filament\Resources\Hobbies\Tables\HobbiesTable;
use App\Models\Hobby;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class HobbyResource extends Resource
{
    protected static ?string $model = Hobby::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPaintBrush;

    protected static ?string $recordTitleAttribute = 'Hobby';

    public static function form(Schema $schema): Schema
    {
        return HobbyForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return HobbyInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HobbiesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListHobbies::route('/'),
            'create' => CreateHobby::route('/create'),
            'view' => ViewHobby::route('/{record}'),
            'edit' => EditHobby::route('/{record}/edit'),
        ];
    }
}
