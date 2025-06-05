<?php

namespace App\Filament\Resources\TUserSettingResource\Pages;

use App\Filament\Resources\TUserSettingResource;
use App\Models\TUserSetting;
use Filament\Resources\Pages\CreateRecord;

class CreateTUserSetting extends CreateRecord
{
    protected static string $resource = TUserSettingResource::class;

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        // Extract the common data
        $commonData = [
            't_user_id' => $data['t_user_id'],
            'created_by' => $data['created_by'] ?? null,
            'updated_by' => $data['updated_by'] ?? null,
            'deleted_by' => $data['deleted_by'] ?? null,
        ];

        // Create multiple records from the repeater data
        $userSettings = $data['user_settings'] ?? [];
        $createdRecords = [];

        foreach ($userSettings as $setting) {
            $recordData = array_merge($commonData, [
                'setting_key' => $setting['setting_key'],
                'setting_value' => $setting['setting_value'],
            ]);
            
            $createdRecords[] = TUserSetting::create($recordData);
        }

        // Return the first created record for Filament's purposes
        return $createdRecords[0] ?? TUserSetting::create($commonData);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}