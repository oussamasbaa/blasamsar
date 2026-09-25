<?php

namespace App\Filament\Resources\FleetVehicles\Pages;

use App\Filament\Resources\FleetVehicles\FleetVehicleResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFleetVehicle extends EditRecord
{
    protected static string $resource = FleetVehicleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
