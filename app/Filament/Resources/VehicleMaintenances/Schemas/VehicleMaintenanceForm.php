<?php

namespace App\Filament\Resources\VehicleMaintenances\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class VehicleMaintenanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('vehicle_id')
                    ->required()
                    ->numeric(),
                TextInput::make('type')
                    ->required(),
                Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                DatePicker::make('scheduled_date')
                    ->required(),
                DatePicker::make('completed_date'),
                TextInput::make('cost')
                    ->numeric()
                    ->prefix('$'),
                Textarea::make('notes')
                    ->columnSpanFull(),
                DatePicker::make('insurance_expiration_date'),
                DatePicker::make('technical_inspection_date'),
            ]);
    }
}
