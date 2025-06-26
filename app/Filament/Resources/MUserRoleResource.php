<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MUserRoleResource\Pages;
use App\Filament\Traits\HasAuditFields;
use App\Models\MUserRole;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MUserRoleResource extends Resource
{
    use HasAuditFields;
    protected static ?string $model = MUserRole::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    protected static ?string $navigationLabel = 'ユーザーロール';

    protected static ?string $modelLabel = 'ユーザーロール';

    protected static ?string $pluralModelLabel = 'ユーザーロール';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('role_name')
                    ->required()
                    ->label('ロール名'),
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
                Tables\Columns\TextColumn::make('role_name')
                    ->sortable()
                    ->searchable()
                    ->label('ロール名'),
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
            'index' => Pages\ListMUserRoles::route('/'),
            'create' => Pages\CreateMUserRole::route('/create'),
            'edit' => Pages\EditMUserRole::route('/{record}/edit'),
        ];
    }
}