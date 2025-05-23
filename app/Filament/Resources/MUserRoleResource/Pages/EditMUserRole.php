<?php

namespace App\Filament\Resources\MUserRoleResource\Pages;

use App\Filament\Resources\MUserRoleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMUserRole extends EditRecord
{
    protected static string $resource = MUserRoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}