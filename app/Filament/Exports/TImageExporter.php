<?php

namespace App\Filament\Exports;

use App\Models\TImage;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class TImageExporter extends Exporter
{
    protected static ?string $model = TImage::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('original_filename')
                ->label('オリジナルファイル名'),
            ExportColumn::make('converted_filename')
                ->label('変換後ファイル名'),
            ExportColumn::make('upload_url')
                ->label('アップロード先URL'),
            ExportColumn::make('upload_path')
                ->label('アップロード先パス'),
            ExportColumn::make('mimetype')
                ->label('MIMEタイプ'),
            ExportColumn::make('image_type')
                ->label('画像タイプ'),
            ExportColumn::make('width')
                ->label('幅'),
            ExportColumn::make('height')
                ->label('高さ'),
            ExportColumn::make('file_size')
                ->label('ファイルサイズ'),
            ExportColumn::make('file_hash')
                ->label('ファイルハッシュ'),
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
        $body = '画像のエクスポートが完了しました。' . number_format($export->successful_rows) . ' 件のデータが処理されました。';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' 件の処理に失敗しました。';
        }

        return $body;
    }
}