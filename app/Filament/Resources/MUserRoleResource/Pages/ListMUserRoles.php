<?php

namespace App\Filament\Resources\MUserRoleResource\Pages;

use App\Filament\Resources\MUserRoleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMUserRoles extends ListRecords
{
    protected static string $resource = MUserRoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}