<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Car;
use App\Models\CarModel;

class StockAlertWidget extends StatsOverviewWidget
{
    protected ?string $pollingInterval = '30s';
    protected static ?int $sort = 6;

    protected function getStats(): array
    {
        $totalCars = Car::count();
        $availableCars = Car::where('status', 'available')->count();
        $reservedCars = Car::where('status', 'reserved')->count();
        $soldCars = Car::where('status', 'sold')->count();

        return [
            Stat::make('Stock Total', $totalCars)
                ->description('Véhicules en catalogue')
                ->descriptionIcon('heroicon-m-cube')
                ->color('info'),
            Stat::make('Disponibles', $availableCars)
                ->description('Prêts à la vente')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
            Stat::make('Réservés', $reservedCars)
                ->description('En attente de confirmation')
                ->descriptionIcon('heroicon-m-bookmark')
                ->color('warning'),
            Stat::make('Vendus', $soldCars)
                ->description('Véhicules vendus')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('primary'),
        ];
    }
}
