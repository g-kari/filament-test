<?php

namespace App\Filament\Resources\TUserRoleResource\Pages;

use App\Filament\Resources\TUserRoleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTUserRole extends EditRecord
{
    protected static string $resource = TUserRoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}