<?php

namespace App\Filament\Resources\TImageResource\Pages;

use App\Filament\Resources\TImageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTImages extends ListRecords
{
    protected static string $resource = TImageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\ExportAction::make()
                ->label('CSVエクスポート')
                ->icon('heroicon-o-arrow-down-tray')
                ->exporter(\App\Filament\Exports\TImageExporter::class),
        ];
    }
}