<?php

namespace App\Filament\Resources\TUserSettingResource\Pages;

use App\Filament\Resources\TUserSettingResource;
use App\Models\TUserSetting;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Form;
use Filament\Resources\Pages\CreateRecord;

class CreateTUserSetting extends CreateRecord
{
    use CreateRecord\Concerns\HasWizard;

    protected static string $resource = TUserSettingResource::class;

    public function form(Form $form): Form
    {
        return parent::form($form)
            ->schema([
                Wizard::make($this->getSteps())
                    ->startOnStep($this->getStartStep())
                    ->cancelAction($this->getCancelFormAction())
                    ->submitAction($this->getSubmitFormAction())
                    ->skippable($this->hasSkippableSteps())
                    ->contained(false),
            ])
            ->columns(null);
    }

    protected function getSteps(): array
    {
        return [
            Step::make('ユーザー選択')
                ->description('設定を作成するユーザーを選択してください')
                ->schema([
                    \Filament\Forms\Components\Select::make('t_user_id')
                        ->label('ユーザー')
                        ->relationship('user', 'user_name')
                        ->searchable()
                        ->required()
                        ->live()
                        ->placeholder('ユーザーを選択してください'),
                ])
                ->columns(1),

            Step::make('設定項目')
                ->description('ユーザーの設定項目を追加してください')
                ->schema([
                    \Filament\Forms\Components\Repeater::make('user_settings')
                        ->label('設定項目')
                        ->schema([
                            \Filament\Forms\Components\TextInput::make('setting_key')
                                ->required()
                                ->label('設定キー')
                                ->placeholder('例: theme, language, timezone')
                                ->columnSpan(1),
                            \Filament\Forms\Components\Textarea::make('setting_value')
                                ->required()
                                ->label('設定値')
                                ->placeholder('例: dark, ja, Asia/Tokyo')
                                ->columnSpan(1),
                        ])
                        ->columns(2)
                        ->addActionLabel('+ 設定項目を追加')
                        ->deleteActionLabel('- 削除')
                        ->reorderableWithButtons()
                        ->minItems(1)
                        ->defaultItems(1)
                        ->collapsible()
                        ->itemLabel(fn (array $state): ?string => $state['setting_key'] ?? '新しい設定')
                        ->cloneable()
                        ->cloneActionLabel('複製'),
                ])
                ->columns(1),

            Step::make('監査情報')
                ->description('監査情報を入力してください（任意）')
                ->schema([
                    \Filament\Forms\Components\Grid::make(3)
                        ->schema([
                            \Filament\Forms\Components\TextInput::make('created_by')
                                ->label('作成者')
                                ->placeholder('作成者名を入力'),
                            \Filament\Forms\Components\TextInput::make('updated_by')
                                ->label('更新者')
                                ->placeholder('更新者名を入力'),
                            \Filament\Forms\Components\TextInput::make('deleted_by')
                                ->label('削除者')
                                ->placeholder('削除者名を入力'),
                        ]),
                ])
                ->columns(1),

            Step::make('確認')
                ->description('入力内容を確認してください')
                ->schema([
                    \Filament\Forms\Components\Placeholder::make('review')
                        ->label('')
                        ->content(function ($get) {
                            $user_id = $get('t_user_id');
                            $settings = $get('user_settings') ?? [];
                            
                            $content = '<div class="space-y-4">';
                            
                            if ($user_id) {
                                $user = \App\Models\TUser::find($user_id);
                                $content .= '<div><strong>選択されたユーザー:</strong> ' . ($user ? $user->user_name : 'ユーザーが見つかりません') . '</div>';
                            }
                            
                            $content .= '<div><strong>設定項目数:</strong> ' . count($settings) . '</div>';
                            
                            if (!empty($settings)) {
                                $content .= '<div><strong>設定項目:</strong></div>';
                                $content .= '<div class="ml-4 space-y-2">';
                                foreach ($settings as $index => $setting) {
                                    $key = $setting['setting_key'] ?? '';
                                    $value = $setting['setting_value'] ?? '';
                                    $content .= '<div class="border-l-4 border-blue-500 pl-3">';
                                    $content .= '<div><strong>' . ($index + 1) . '. ' . htmlspecialchars($key) . '</strong></div>';
                                    $content .= '<div class="text-gray-600">' . htmlspecialchars($value) . '</div>';
                                    $content .= '</div>';
                                }
                                $content .= '</div>';
                            }
                            
                            $content .= '</div>';
                            
                            return new \Illuminate\Support\HtmlString($content);
                        }),
                ])
                ->columns(1),
        ];
    }

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

    protected function hasSkippableSteps(): bool
    {
        return true;
    }
}