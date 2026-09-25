<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Car;
use App\Models\CarModel;
use Illuminate\Support\Facades\DB;

class TopVehiclesWidget extends ChartWidget
{
    protected ?string $heading = 'Véhicules les plus réservés';
    protected ?string $description = 'Top 5 des modèles demandés';
    protected ?string $pollingInterval = '30s';
    protected static ?int $sort = 4;

    protected function getData(): array
    {
        $topCars = Car::select('car_model_id', DB::raw('COUNT(*) as reservation_count'))
            ->whereHas('reservations')
            ->groupBy('car_model_id')
            ->with('carModel.brand')
            ->orderByDesc('reservation_count')
            ->limit(5)
            ->get();

        $labels = $topCars->map(fn($car) => $car->carModel->brand->name . ' ' . $car->carModel->name)->toArray();
        $data = $topCars->pluck('reservation_count')->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Réservations',
                    'data' => $data,
                    'backgroundColor' => [
                        'rgba(212, 175, 55, 0.8)',
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(239, 68, 68, 0.8)',
                        'rgba(34, 197, 94, 0.8)',
                        'rgba(168, 85, 247, 0.8)',
                    ],
                    'borderWidth' => 0,
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
