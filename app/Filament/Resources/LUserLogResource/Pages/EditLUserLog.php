<?php

namespace App\Filament\Resources\LUserLogResource\Pages;

use App\Filament\Resources\LUserLogResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLUserLog extends EditRecord
{
    protected static string $resource = LUserLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}