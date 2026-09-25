<?php

namespace App\Filament\Widgets;

use App\Models\Attendance;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class AttendanceChart extends ChartWidget
{
    protected ?string $heading = 'Weekly Attendance Trends';
    protected static ?int $sort = 3;
    protected ?string $pollingInterval = '10s';

    protected function getData(): array
    {
        $days = [];
        $present = [];
        $late = [];
        $absent = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $days[] = Carbon::parse($date)->format('D');
            
            $present[] = Attendance::whereDate('date', $date)->where('status', 'present')->count();
            $late[] = Attendance::whereDate('date', $date)->where('status', 'late')->count();
            $absent[] = Attendance::whereDate('date', $date)->where('status', 'absent')->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Present',
                    'data' => $present,
                    'backgroundColor' => 'rgba(22, 163, 74, 0.8)',
                ],
                [
                    'label' => 'Late',
                    'data' => $late,
                    'backgroundColor' => 'rgba(202, 138, 4, 0.8)',
                ],
                [
                    'label' => 'Absent',
                    'data' => $absent,
                    'backgroundColor' => 'rgba(220, 38, 38, 0.8)',
                ],
            ],
            'labels' => $days,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
