<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Reservation;
use App\Models\Car;
use Illuminate\Support\Carbon;

class ConversionWidget extends StatsOverviewWidget
{
    protected ?string $pollingInterval = '30s';
    protected static ?int $sort = 3;

    protected function getStats(): array
    {
        $totalReservations = Reservation::count();
        $confirmed = Reservation::where('status', 'confirmed')->count();
        $pending = Reservation::where('status', 'pending')->count();
        $cancelled = Reservation::where('status', 'cancelled')->count();
        $thisMonth = Reservation::whereMonth('reservation_date', Carbon::now()->month)->count();
        $totalCars = Car::count();
        $reservedCars = Car::where('status', 'reserved')->count();
        $conversionRate = $totalReservations > 0 ? round(($confirmed / $totalReservations) * 100, 1) : 0;

        return [
            Stat::make('Taux de Conversion', "{$conversionRate}%")
                ->description("{$confirmed} confirmées / {$totalReservations} total")
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Réservations ce mois', $thisMonth)
                ->description('Réservations en cours')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('info'),
            Stat::make('En attente', $pending)
                ->description('Réservations à traiter')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
            Stat::make('Véhicules réservés', $reservedCars)
                ->description("sur {$totalCars} disponibles")
                ->descriptionIcon('heroicon-m-bookmark')
                ->color('primary'),
        ];
    }
}
