<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TUserSettingResource\Pages;
use App\Filament\Traits\HasAuditFields;
use App\Models\TUser;
use App\Models\TUserSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TUserSettingResource extends Resource
{
    use HasAuditFields;
    protected static ?string $model = TUserSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static ?string $navigationLabel = 'ユーザー設定';

    protected static ?string $modelLabel = 'ユーザー設定';

    protected static ?string $pluralModelLabel = 'ユーザー設定';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('t_user_id')
                    ->label('ユーザー')
                    ->relationship('user', 'user_name')
                    ->searchable()
                    ->required()
                    ->disabled(fn (string $operation): bool => $operation === 'edit'),
                
                // Show single fields for editing existing records
                Forms\Components\Group::make([
                    Forms\Components\TextInput::make('setting_key')
                        ->required()
                        ->label('設定キー'),
                    Forms\Components\Textarea::make('setting_value')
                        ->required()
                        ->label('設定値'),
                ])
                ->visible(fn (string $operation): bool => $operation === 'edit'),

                // Show repeater for creating new records
                Forms\Components\Repeater::make('user_settings')
                    ->label('設定項目')
                    ->schema([
                        Forms\Components\TextInput::make('setting_key')
                            ->required()
                            ->label('設定キー')
                            ->columnSpan(1),
                        Forms\Components\Textarea::make('setting_value')
                            ->required()
                            ->label('設定値')
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
                    ->visible(fn (string $operation): bool => $operation === 'create'),
                
                ...self::getAuditFields(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.user_name')
                    ->sortable()
                    ->searchable()
                    ->label('ユーザー名'),
                Tables\Columns\TextColumn::make('setting_key')
                    ->sortable()
                    ->searchable()
                    ->label('設定キー'),
                Tables\Columns\TextColumn::make('setting_value')
                    ->limit(50)
                    ->label('設定値'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('作成日時'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTUserSettings::route('/'),
            'create' => Pages\CreateTUserSetting::route('/create'),
            'edit' => Pages\EditTUserSetting::route('/{record}/edit'),
        ];
    }
}