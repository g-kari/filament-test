<?php

namespace App\Filament\Resources\LUserLogResource\Pages;

use App\Filament\Resources\LUserLogResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLUserLogs extends ListRecords
{
    protected static string $resource = LUserLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\ExportAction::make()
                ->label('CSVエクスポート')
                ->icon('heroicon-o-arrow-down-tray')
                ->exporter(\App\Filament\Exports\LUserLogExporter::class),
        ];
    }
}