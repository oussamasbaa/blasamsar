<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Car;
use App\Models\CarModel;
use App\Models\Brand;

class BrandDistributionWidget extends ChartWidget
{
    protected ?string $heading = 'Répartition par Marque';
    protected ?string $description = 'Nombre de véhicules par marque';
    protected ?string $pollingInterval = '30s';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $brands = Brand::withCount(['carModels' => function ($query) {
            $query->withCount('cars');
        }])->get();

        $labels = [];
        $data = [];
        $colors = [
            'rgba(212, 175, 55, 0.8)',
            'rgba(59, 130, 246, 0.8)',
            'rgba(239, 68, 68, 0.8)',
            'rgba(34, 197, 94, 0.8)',
            'rgba(168, 85, 247, 0.8)',
            'rgba(249, 115, 22, 0.8)',
            'rgba(236, 72, 153, 0.8)',
            'rgba(20, 184, 166, 0.8)',
            'rgba(234, 179, 8, 0.8)',
            'rgba(99, 102, 241, 0.8)',
            'rgba(244, 63, 94, 0.8)',
            'rgba(132, 204, 22, 0.8)',
            'rgba(14, 165, 233, 0.8)',
        ];

        foreach ($brands as $brand) {
            $count = $brand->carModels->sum(fn($cm) => $cm->cars_count);
            if ($count > 0) {
                $labels[] = $brand->name;
                $data[] = $count;
            }
        }

        return [
            'datasets' => [
                [
                    'data' => $data,
                    'backgroundColor' => array_slice($colors, 0, count($data)),
                    'borderWidth' => 0,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
