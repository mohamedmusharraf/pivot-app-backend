<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use BackedEnum;

class Dashboard extends Page
{
    protected static string|null $title = 'Dashboard';
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-home';

    protected function getHeaderWidgets(): array
    {
        return parent::getHeaderWidgets();
    }
}
