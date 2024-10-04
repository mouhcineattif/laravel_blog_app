<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class TestChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                'label' => 'Temperature',
                'data' => [21,19,23,24.5,22,27,31],
                'tension' => 1,
                'borderColor' => 'rgb(255,56,25)',
                'fill' => true
            ],
        ],
        'labels' => ['Monday','Thuesday','Wednesday','Thursday','Friday','Saturday','Sunday']
        
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
