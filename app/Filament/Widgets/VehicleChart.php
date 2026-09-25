<?php

namespace App\Filament\Widgets;

use App\Models\FleetVehicle;
use Filament\Widgets\ChartWidget;

class VehicleChart extends ChartWidget
{
    protected ?string $heading = 'Fleet Vehicle Status';
    protected static ?int $sort = 4;
    protected ?string $pollingInterval = '10s';

    protected function getData(): array
    {
        $available = FleetVehicle::where('status', 'available')->count();
        $inUse = FleetVehicle::where('status', 'in_use')->count();
        $maintenance = FleetVehicle::where('status', 'maintenance')->count();
        $outOfService = FleetVehicle::where('status', 'out_of_service')->count();

        return [
            'datasets' => [
                [
                    'label' => 'Vehicles',
                    'data' => [$available, $inUse, $maintenance, $outOfService],
                    'backgroundColor' => [
                        'rgba(22, 163, 74, 0.8)',
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(202, 138, 4, 0.8)',
                        'rgba(220, 38, 38, 0.8)',
                    ],
                ],
            ],
            'labels' => ['Available', 'In Use', 'Maintenance', 'Out of Service'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
