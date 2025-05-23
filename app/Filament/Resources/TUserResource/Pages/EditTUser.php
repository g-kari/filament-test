<?php

namespace App\Filament\Resources\TUserResource\Pages;

use App\Filament\Resources\TUserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTUser extends EditRecord
{
    protected static string $resource = TUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}