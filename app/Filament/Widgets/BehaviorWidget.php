<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class BehaviorWidget extends ChartWidget
{
    protected ?string $heading = 'Analyse Comportementale';
    protected ?string $description = 'Pages les plus visitées cette semaine';
    protected ?string $pollingInterval = '60s';
    protected static ?int $sort = 5;

    protected function getData(): array
    {
        Carbon::setLocale('fr');
        $days = collect();
        for ($i = 6; $i >= 0; $i--) {
            $days->push(Carbon::now()->subDays($i));
        }

        $labels = $days->map(fn($d) => ucfirst($d->dayName))->toArray();

        $vehicleViews = [];
        $searches = [];
        $comparisons = [];

        foreach ($days as $day) {
            $vehicleViews[] = rand(45, 120);
            $searches[] = rand(15, 60);
            $comparisons[] = rand(5, 25);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Véhicules consultés',
                    'data' => $vehicleViews,
                    'backgroundColor' => 'rgba(212, 175, 55, 0.8)',
                    'borderColor' => '#D4AF37',
                    'borderWidth' => 2,
                    'tension' => 0.4,
                    'fill' => true,
                ],
                [
                    'label' => 'Recherches',
                    'data' => $searches,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.6)',
                    'borderColor' => '#3B82F6',
                    'borderWidth' => 2,
                    'tension' => 0.4,
                    'fill' => true,
                ],
                [
                    'label' => 'Comparaisons',
                    'data' => $comparisons,
                    'backgroundColor' => 'rgba(34, 197, 94, 0.6)',
                    'borderColor' => '#22C55E',
                    'borderWidth' => 2,
                    'tension' => 0.4,
                    'fill' => true,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
