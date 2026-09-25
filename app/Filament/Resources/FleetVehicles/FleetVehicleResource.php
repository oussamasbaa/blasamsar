<?php

namespace App\Filament\Resources\FleetVehicles;

use App\Filament\Resources\FleetVehicles\Pages\CreateFleetVehicle;
use App\Filament\Resources\FleetVehicles\Pages\EditFleetVehicle;
use App\Filament\Resources\FleetVehicles\Pages\ListFleetVehicles;
use App\Filament\Resources\FleetVehicles\Schemas\FleetVehicleForm;
use App\Filament\Resources\FleetVehicles\Tables\FleetVehiclesTable;
use App\Models\FleetVehicle;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FleetVehicleResource extends Resource
{
    protected static ?string $model = FleetVehicle::class;

    protected static bool $shouldRegisterNavigation = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return FleetVehicleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FleetVehiclesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFleetVehicles::route('/'),
            'create' => CreateFleetVehicle::route('/create'),
            'edit' => EditFleetVehicle::route('/{record}/edit'),
        ];
    }
}
