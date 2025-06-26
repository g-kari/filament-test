<?php

namespace App\Filament\Exports;

use App\Models\LUserLog;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class LUserLogExporter extends Exporter
{
    protected static ?string $model = LUserLog::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('user.user_name')
                ->label('ユーザー名'),
            ExportColumn::make('log_type')
                ->label('ログタイプ'),
            ExportColumn::make('log_message')
                ->label('ログメッセージ'),
            ExportColumn::make('created_at')
                ->label('作成日時'),
            ExportColumn::make('updated_at')
                ->label('更新日時'),
            ExportColumn::make('created_by')
                ->label('作成者'),
            ExportColumn::make('updated_by')
                ->label('更新者'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'ユーザーログのエクスポートが完了しました。' . number_format($export->successful_rows) . ' 件のデータが処理されました。';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' 件の処理に失敗しました。';
        }

        return $body;
    }
}