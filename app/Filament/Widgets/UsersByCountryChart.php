<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class UsersByCountryChart extends ChartWidget
{
    protected ?string $heading = 'Users by Country';

    public static function canView(): bool
    {
        return false;
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $rows = DB::table('user_profiles')
            ->whereNotNull('country')
            ->select('country as name', DB::raw('count(*) as total'))
            ->groupBy('country')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Users',
                    'data' => $rows->pluck('total')->all(),
                ],
            ],
            'labels' => $rows->pluck('name')->all(),
        ];
    }
}
