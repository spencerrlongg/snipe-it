<?php

namespace App\Livewire;

use App\Models\Actionlog;
use App\Models\Asset;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class ThisYearCheckoutsChart extends ChartWidget
{
    protected static ?string $heading = 'Checkouts Per Month';

    protected function getData(): array
    {
        $data = Trend::query(Actionlog::query()->where('action_type', 'checkout'))
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->count();
        return [
            'datasets' => [
                [
                    'label' => 'Assets',
                    'data'  => $data->map(fn(TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels'   => $data->map(fn(TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
