<?php

namespace App\Filament\Widgets;

use App\Models\Attendance;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\BadgeColumn;

class RecentAttendancesWidget extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';
    
    // Polling allows the table to auto-refresh in real time!
    protected ?string $pollingInterval = '5s';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Attendance::query()->latest('created_at')->limit(10)
            )
            ->columns([
                ImageColumn::make('worker.profile_picture')
                    ->label('Photo')
                    ->circular()
                    ->defaultImageUrl('https://ui-avatars.com/api/?name=User'),
                TextColumn::make('worker.name')
                    ->label('Employee')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('worker.department')
                    ->label('Department'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'present' => 'success',
                        'late' => 'warning',
                        'absent' => 'danger',
                        'on_leave' => 'info',
                        default => 'gray',
                    }),
                TextColumn::make('check_in_at')
                    ->label('Check-In')
                    ->dateTime('h:i A')
                    ->sortable(),
                TextColumn::make('check_out_at')
                    ->label('Check-Out')
                    ->dateTime('h:i A')
                    ->placeholder('-'),
                ImageColumn::make('selfie_in')
                    ->label('Selfie In')
                    ->circular(),
            ])
            ->paginated(false);
    }
}
