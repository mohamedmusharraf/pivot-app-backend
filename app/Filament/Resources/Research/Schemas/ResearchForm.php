<?php

namespace App\Filament\Resources\Research\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ResearchForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                TextInput::make('fun_facts')
                    ->label('Fun Facts')
                    ->columnSpanFull(),
                    // ->required(),

                RichEditor::make('summary')
                    ->label('Summary')
                    ->toolbarButtons([
                        'blockquote',
                        'bold',
                        'bulletList',
                        'codeBlock',
                        'h1',
                        'h2',
                        'h3',
                        'italic',
                        'link',
                        'orderedList',
                        'redo',
                        'strike',
                        'underline',
                        'undo',
                    ])
                    ->columnSpanFull()
                    ->disableToolbarButtons([
                        'attachFiles',
                    ])
                    // ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, callable $set) {
                        $clean = preg_replace('/(<p>.*?<\/p>)(\s*\1)+/s', '$1', $state);
                        $set('summary', $clean);
                    }),

                TextInput::make('video_link')
                    ->label('Video Link')
                    ->columnSpanFull()
                    ->url()
                    ->placeholder('https://video_link.com/watch?v=...'),

                // Radio::make('video_type')
                //     ->label('Video For')
                //     ->options([
                //         'fun_facts' => 'Fun Facts',
                //         'summary' => 'Summary',
                //         'both' => 'Both',
                //     ])
                //     ->inline(),

                FileUpload::make('files')
                    ->label('Article Files')
                    ->directory('research-files')
                    ->disk('public')
                    ->preserveFilenames()
                    ->acceptedFileTypes([
                        'application/pdf',
                        'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        'text/csv',
                        'image/*',
                    ])
                    ->maxSize(10240),
            ]);
    }
}
