<?php

namespace App\Filament\Resources\TImageResource\Pages;

use App\Filament\Resources\TImageResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTImage extends ViewRecord
{
    protected static string $resource = TImageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}