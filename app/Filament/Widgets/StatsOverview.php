<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User as Users;

class StatsOverview extends StatsOverviewWidget
{

    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', Users::count())
                ->description('All registered users')
                ->icon('heroicon-o-users')
                ->color('primary'),

            Stat::make('Active Users', Users::where('last_login_at', '>=', now()->subDays(30))->count())
                ->description('Logged in last 30 days')
                ->icon('heroicon-o-bolt')
                ->color('success'),

            Stat::make('New This Week', Users::whereBetween('created_at', [now()->subWeek(), now()])->count())
                ->description('Last 7 days')
                ->icon('heroicon-o-arrow-trending-up')
                ->color('warning'),
                
            Stat::make('Paying Users', 42)
                ->description('Growth + Mastery')
                ->color('warning'),

            Stat::make('MRR', '$5,200')
                ->description('Monthly recurring revenue')
                ->color('danger'),
        ];
    }
}
