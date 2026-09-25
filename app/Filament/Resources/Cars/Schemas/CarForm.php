<?php

namespace App\Filament\Resources\Cars\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class CarForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('car_model_id')
                    ->required()
                    ,
                TextInput::make('year')
                    ->required()
                    ,
                TextInput::make('price')
                    ->required()
                    
                    ->prefix('$'),
                TextInput::make('mileage')
                    ->required()
                    ,
                TextInput::make('fuel_type')
                    ->required(),
                TextInput::make('transmission')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                \Filament\Forms\Components\FileUpload::make('images')
                    ->image()
                    ->multiple()
                    ->reorderable()
                    ->imageEditor()
                    ->directory('cars')
                    ->columnSpanFull(),
                TextInput::make('status')
                    ->required()
                    ->default('available'),
                TextInput::make('color'),
                TextInput::make('condition')
                    ->required()
                    ->default('occasion'),
            ]);
    }
}
