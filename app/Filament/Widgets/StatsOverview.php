<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Worker;
use App\Models\FleetVehicle;

class StatsOverview extends StatsOverviewWidget
{
    protected ?string $pollingInterval = '10s';

    protected function getStats(): array
    {
        $totalEmployees = Worker::where('status', 'active')->count();

        $totalVehicles = FleetVehicle::count();
        $availableVehicles = FleetVehicle::where('status', 'available')->count();
        $maintenanceVehicles = FleetVehicle::where('status', 'maintenance')->count();

        return [
            Stat::make('Total Employees', $totalEmployees)
                ->description('Active staff members')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),
            Stat::make('Vehicles Available', $availableVehicles)
                ->description('Ready for use')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
            Stat::make('In Maintenance', $maintenanceVehicles)
                ->description('Under repair')
                ->descriptionIcon('heroicon-m-wrench-screwdriver')
                ->color('danger'),
        ];
    }
}
