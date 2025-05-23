<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TUserResource\Pages;
use App\Models\TUser;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TUserResource extends Resource
{
    protected static ?string $model = TUser::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $navigationLabel = 'ユーザー';

    protected static ?string $modelLabel = 'ユーザー';

    protected static ?string $pluralModelLabel = 'ユーザー';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('public_user_id')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->label('公開用ユーザーID'),
                Forms\Components\TextInput::make('user_name')
                    ->required()
                    ->label('ユーザー名'),
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
                Tables\Columns\TextColumn::make('public_user_id')
                    ->sortable()
                    ->searchable()
                    ->label('公開用ユーザーID'),
                Tables\Columns\TextColumn::make('user_name')
                    ->sortable()
                    ->searchable()
                    ->label('ユーザー名'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('作成日時'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->label('更新日時'),
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
            'index' => Pages\ListTUsers::route('/'),
            'create' => Pages\CreateTUser::route('/create'),
            'edit' => Pages\EditTUser::route('/{record}/edit'),
        ];
    }
}