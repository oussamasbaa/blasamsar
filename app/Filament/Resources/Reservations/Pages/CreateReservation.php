<?php

namespace App\Filament\Resources\Reservations\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Reservations\ReservationResource;

class CreateReservation extends CreateRecord
{
    protected static string $resource = ReservationResource::class;
}
