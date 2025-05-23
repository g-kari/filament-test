<?php

namespace App\Filament\Resources\TUserResource\Pages;

use App\Filament\Resources\TUserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTUser extends CreateRecord
{
    protected static string $resource = TUserResource::class;
}