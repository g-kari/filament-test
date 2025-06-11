<?php

namespace App\Filament\Resources\TImageResource\Pages;

use App\Filament\Resources\TImageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTImage extends EditRecord
{
    protected static string $resource = TImageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}