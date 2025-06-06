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
            Actions\ExportAction::make()
                ->label('CSVエクスポート')
                ->icon('heroicon-o-arrow-down-tray')
                ->exporter(\App\Filament\Exports\TUserSettingExporter::class),
        ];
    }
}