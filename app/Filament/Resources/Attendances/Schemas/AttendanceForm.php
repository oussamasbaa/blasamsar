<?php

namespace App\Filament\Resources\Attendances\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class AttendanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('worker_id')
                    ->required()
                    ->numeric(),
                DatePicker::make('date')
                    ->required(),
                DateTimePicker::make('check_in_at'),
                DateTimePicker::make('check_out_at'),
                TextInput::make('status')
                    ->required()
                    ->default('absent'),
                TextInput::make('total_hours')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('latitude_in')
                    ->numeric(),
                TextInput::make('longitude_in')
                    ->numeric(),
                TextInput::make('latitude_out')
                    ->numeric(),
                TextInput::make('longitude_out')
                    ->numeric(),
                Textarea::make('selfie_in')
                    ->columnSpanFull(),
                Textarea::make('selfie_out')
                    ->columnSpanFull(),
                TextInput::make('ip_in'),
                TextInput::make('ip_out'),
                TextInput::make('device_in'),
                TextInput::make('device_out'),
            ]);
    }
}
