<?php

namespace App\Livewire;

use App\Models\Asset;
use App\Models\Component;
use App\Models\License;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class ThisYearComponentsLicensesChart extends ChartWidget
{
    protected static ?string $heading = 'Components and Licenses Created Per Month';

    protected function getData(): array
    {
        $assets = Trend::model(Component::class)
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->count();
        $licenses = Trend::model(License::class)
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->count();
        return [
            'datasets' => [
                [
                    'label'           => 'Components',
                    'data'            => $assets->map(fn(TrendValue $value) => $value->aggregate),
                    'backgroundColor' => ['rgba(255, 99, 132, 0.2)'],
                ],
                [
                    'label'           => 'Licenses',
                    'data'            => $licenses->map(fn(TrendValue $value) => $value->aggregate),
                    'backgroundColor' => ['rgba(54, 162, 235, 0.2)'],
                ]
            ],
            'labels'   => $assets->map(fn(TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
