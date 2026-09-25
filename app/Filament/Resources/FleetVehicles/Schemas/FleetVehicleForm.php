<?php

namespace App\Filament\Resources\FleetVehicles\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class FleetVehicleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('brand')
                    ->required(),
                TextInput::make('model')
                    ->required(),
                TextInput::make('registration_number')
                    ->required(),
                TextInput::make('year')
                    ->required()
                    ->numeric(),
                TextInput::make('status')
                    ->required()
                    ->default('available'),
                TextInput::make('assigned_driver_id')
                    ->numeric(),
                FileUpload::make('image')
                    ->image(),
            ]);
    }
}
