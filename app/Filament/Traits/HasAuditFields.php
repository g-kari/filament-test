<?php

namespace App\Filament\Traits;

use Filament\Forms;

trait HasAuditFields
{
    public static function getAuditFields(): array
    {
        return [
            Forms\Components\TextInput::make('created_by')
                ->label('作成者'),
            Forms\Components\TextInput::make('updated_by')
                ->label('更新者'),
            Forms\Components\TextInput::make('deleted_by')
                ->label('削除者'),
        ];
    }
}