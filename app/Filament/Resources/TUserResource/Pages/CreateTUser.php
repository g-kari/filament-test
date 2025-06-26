<?php

namespace App\Filament\Resources\TUserResource\Pages;

use App\Filament\Resources\TUserResource;
use App\Models\TUserRole;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateTUser extends CreateRecord
{
    protected static string $resource = TUserResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $roles = $data['roles'] ?? [];
        unset($data['roles']);

        $record = static::getModel()::create($data);

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