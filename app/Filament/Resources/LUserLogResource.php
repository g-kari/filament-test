<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LUserLogResource\Pages;
use App\Models\LUserLog;
use App\Models\TUser;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LUserLogResource extends Resource
{
    protected static ?string $model = LUserLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationLabel = 'ユーザーログ';

    protected static ?string $modelLabel = 'ユーザーログ';

    protected static ?string $pluralModelLabel = 'ユーザーログ';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('t_user_id')
                    ->label('ユーザー')
                    ->relationship('user', 'user_name')
                    ->searchable()
                    ->required(),
                Forms\Components\TextInput::make('log_type')
                    ->required()
                    ->label('ログタイプ'),
                Forms\Components\Textarea::make('log_message')
                    ->required()
                    ->label('ログメッセージ'),
                Forms\Components\TextInput::make('created_by')
                    ->label('作成者'),
                Forms\Components\TextInput::make('updated_by')
                    ->label('更新者'),
                Forms\Components\TextInput::make('deleted_by')
                    ->label('削除者'),
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
                Tables\Columns\TextColumn::make('log_type')
                    ->sortable()
                    ->searchable()
                    ->label('ログタイプ'),
                Tables\Columns\TextColumn::make('log_message')
                    ->limit(50)
                    ->label('ログメッセージ'),
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
            'index' => Pages\ListLUserLogs::route('/'),
            'create' => Pages\CreateLUserLog::route('/create'),
            'edit' => Pages\EditLUserLog::route('/{record}/edit'),
        ];
    }
}