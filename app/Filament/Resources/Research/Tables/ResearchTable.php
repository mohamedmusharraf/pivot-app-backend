<?php

namespace App\Filament\Resources\Research\Tables;

use Dom\Text;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

use const Dom\VALIDATION_ERR;

class ResearchTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('fun_facts')
                    ->label('Fun Facts')
                    ->limit(50)
                    ->searchable(),
                TextColumn::make('summary')
                    ->limit(50)
                     ->formatStateUsing(fn ($state) => strip_tags($state))
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordUrl(null)
            ->recordActions([
                ViewAction::make()
                    ->infolist([])
                    ->modalHeading('Research Details')
                    // ->modalWidth('4xl')
                    ->modalContent(fn($record) => view('filament.research.researchView', [
                        'record' => $record,
                    ])),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
