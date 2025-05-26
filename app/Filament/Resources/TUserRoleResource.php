<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TUserRoleResource\Pages;
use App\Filament\Traits\HasAuditFields;
use App\Models\MUserRole;
use App\Models\TUser;
use App\Models\TUserRole;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TUserRoleResource extends Resource
{
    use HasAuditFields;
    protected static ?string $model = TUserRole::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'ユーザーロール割り当て';

    protected static ?string $modelLabel = 'ユーザーロール割り当て';

    protected static ?string $pluralModelLabel = 'ユーザーロール割り当て';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('t_user_id')
                    ->label('ユーザー')
                    ->relationship('user', 'user_name')
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('m_user_role_id')
                    ->label('ロール')
                    ->options(MUserRole::all()->pluck('role_name', 'id'))
                    ->searchable()
                    ->required(),
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
                Tables\Columns\TextColumn::make('role.role_name')
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
            'index' => Pages\ListTUserRoles::route('/'),
            'create' => Pages\CreateTUserRole::route('/create'),
            'edit' => Pages\EditTUserRole::route('/{record}/edit'),
        ];
    }
}