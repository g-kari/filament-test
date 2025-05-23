<?php

namespace App\Filament\Resources\TUserSettingResource\Pages;

use App\Filament\Resources\TUserSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTUserSettings extends ListRecords
{
    protected static string $resource = TUserSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}