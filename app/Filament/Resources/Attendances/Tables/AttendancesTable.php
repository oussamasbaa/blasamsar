<?php

namespace App\Filament\Resources\Attendances\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AttendancesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('worker_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('date')
                    ->date()
                    ->sortable(),
                TextColumn::make('check_in_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('check_out_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('status')
                    ->searchable(),
                TextColumn::make('total_hours')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('latitude_in')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('longitude_in')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('latitude_out')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('longitude_out')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('ip_in')
                    ->searchable(),
                TextColumn::make('ip_out')
                    ->searchable(),
                TextColumn::make('device_in')
                    ->searchable(),
                TextColumn::make('device_out')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
