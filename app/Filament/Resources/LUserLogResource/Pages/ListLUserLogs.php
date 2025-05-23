<?php

namespace App\Filament\Resources\LUserLogResource\Pages;

use App\Filament\Resources\LUserLogResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLUserLogs extends ListRecords
{
    protected static string $resource = LUserLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}