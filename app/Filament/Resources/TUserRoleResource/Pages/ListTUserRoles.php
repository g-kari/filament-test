<?php

namespace App\Filament\Resources\TUserRoleResource\Pages;

use App\Filament\Resources\TUserRoleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTUserRoles extends ListRecords
{
    protected static string $resource = TUserRoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\ExportAction::make()
                ->label('CSVエクスポート')
                ->icon('heroicon-o-arrow-down-tray')
                ->exporter(\App\Filament\Exports\TUserRoleExporter::class),
        ];
    }
}