<?php

namespace App\Filament\Resources\TUserResource\Pages;

use App\Filament\Resources\TUserResource;
use App\Models\TUserRole;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditTUser extends EditRecord
{
    protected static string $resource = TUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $roles = $data['roles'] ?? [];
        unset($data['roles']);

        $record->update($data);

        // 既存のロール割当を削除
        TUserRole::where('t_user_id', $record->id)->delete();

        // 新しいロール割当を作成
        if (!empty($roles)) {
            foreach ($roles as $roleId) {
                TUserRole::create([
                    't_user_id' => $record->id,
                    'm_user_role_id' => $roleId,
                    'created_by' => $data['created_by'] ?? null,
                    'updated_by' => $data['updated_by'] ?? null,
                ]);
            }
        }

        return $record;
    }
}