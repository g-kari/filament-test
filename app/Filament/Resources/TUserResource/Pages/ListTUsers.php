<?php

namespace App\Filament\Resources\TUserResource\Pages;

use App\Filament\Resources\TUserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTUsers extends ListRecords
{
    protected static string $resource = TUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\ExportAction::make()
                ->label('CSVエクスポート')
                ->icon('heroicon-o-arrow-down-tray')
                ->exporter(\App\Filament\Exports\TUserExporter::class),
        ];
    }
}