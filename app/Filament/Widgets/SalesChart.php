<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Reservation;
use App\Models\Car;
use Illuminate\Support\Carbon;

class SalesChart extends ChartWidget
{
    protected ?string $heading = 'Ventes Mensuelles';
    protected ?string $description = 'Réservations des 6 derniers mois';
    protected ?string $pollingInterval = '30s';
    protected static ?int $sort = 1;

    protected function getData(): array
    {
        Carbon::setLocale('fr');
        $months = collect();
        for ($i = 5; $i >= 0; $i--) {
            $months->push(Carbon::now()->subMonths($i));
        }

        $labels = $months->pluck('monthName')->map(fn($m) => ucfirst($m))->toArray();
        $confirmed = [];
        $pending = [];

        foreach ($months as $month) {
            $confirmed[] = Reservation::whereMonth('reservation_date', $month)
                ->whereYear('reservation_date', $month->year)
                ->where('status', 'confirmed')
                ->count();
            $pending[] = Reservation::whereMonth('reservation_date', $month)
                ->whereYear('reservation_date', $month->year)
                ->where('status', 'pending')
                ->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Confirmées',
                    'data' => $confirmed,
                    'backgroundColor' => 'rgba(212, 175, 55, 0.8)',
                    'borderColor' => '#D4AF37',
                    'borderWidth' => 2,
                    'borderRadius' => 6,
                ],
                [
                    'label' => 'En attente',
                    'data' => $pending,
                    'backgroundColor' => 'rgba(148, 163, 184, 0.5)',
                    'borderColor' => '#94A3B8',
                    'borderWidth' => 2,
                    'borderRadius' => 6,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
