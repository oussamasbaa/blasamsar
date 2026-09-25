<?php

namespace App\Filament\Resources\Clients\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Clients\ClientResource;

class CreateClient extends CreateRecord
{
    protected static string $resource = ClientResource::class;
}
