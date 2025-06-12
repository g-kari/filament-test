<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TImageResource\Pages;
use App\Filament\Traits\HasAuditFields;
use App\Models\TImage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class TImageResource extends Resource
{
    use HasAuditFields;
    
    protected static ?string $model = TImage::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationLabel = '画像';

    protected static ?string $modelLabel = '画像';

    protected static ?string $pluralModelLabel = '画像';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('image_file')
                    ->label('画像ファイル')
                    ->image()
                    ->required()
                    ->acceptedFileTypes(['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'])
                    ->maxSize(10240) // 10MB
                    ->directory('images')
                    ->visibility('public')
                    ->getUploadedFileNameForStorageUsing(
                        fn (TemporaryUploadedFile $file): string => (string) str(
                            hash('sha256', $file->getClientOriginalName() . time()) . '.' . $file->getClientOriginalExtension()
                        )
                    )
                    ->afterStateUpdated(function (callable $set, $state) {
                        if ($state) {
                            // This will be handled in the model's saving event
                            // to populate all the metadata fields
                        }
                    })
                    ->hiddenOn('edit'),
                
                Forms\Components\TextInput::make('original_filename')
                    ->label('オリジナルファイル名')
                    ->disabled()
                    ->dehydrated(false)
                    ->visibleOn('edit'),
                
                Forms\Components\TextInput::make('converted_filename')
                    ->label('変換後ファイル名')
                    ->disabled()
                    ->dehydrated(false)
                    ->visibleOn('edit'),
                
                Forms\Components\TextInput::make('upload_url')
                    ->label('アップロード先URL')
                    ->disabled()
                    ->dehydrated(false)
                    ->visibleOn('edit'),
                
                Forms\Components\TextInput::make('upload_path')
                    ->label('アップロード先パス')
                    ->disabled()
                    ->dehydrated(false)
                    ->visibleOn('edit'),
                
                Forms\Components\TextInput::make('mimetype')
                    ->label('MIMEタイプ')
                    ->disabled()
                    ->dehydrated(false)
                    ->visibleOn('edit'),
                
                Forms\Components\TextInput::make('image_type')
                    ->label('画像タイプ')
                    ->disabled()
                    ->dehydrated(false)
                    ->visibleOn('edit'),
                
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('width')
                            ->label('幅')
                            ->disabled()
                            ->dehydrated(false)
                            ->visibleOn('edit'),
                        
                        Forms\Components\TextInput::make('height')
                            ->label('高さ')
                            ->disabled()
                            ->dehydrated(false)
                            ->visibleOn('edit'),
                    ]),
                
                Forms\Components\TextInput::make('file_size')
                    ->label('ファイルサイズ')
                    ->disabled()
                    ->dehydrated(false)
                    ->visibleOn('edit'),
                
                Forms\Components\TextInput::make('file_hash')
                    ->label('ファイルハッシュ')
                    ->disabled()
                    ->dehydrated(false)
                    ->visibleOn('edit'),
                
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
                
                Tables\Columns\ImageColumn::make('upload_url')
                    ->label('プレビュー')
                    ->size(50)
                    ->circular(),
                
                Tables\Columns\TextColumn::make('original_filename')
                    ->label('オリジナルファイル名')
                    ->searchable()
                    ->limit(30),
                
                Tables\Columns\TextColumn::make('mimetype')
                    ->label('MIMEタイプ')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('dimensions')
                    ->label('サイズ')
                    ->getStateUsing(fn (TImage $record): string => 
                        $record->width && $record->height ? 
                        "{$record->width} x {$record->height}" : 
                        '-'
                    ),
                
                Tables\Columns\TextColumn::make('formatted_file_size')
                    ->label('ファイルサイズ')
                    ->getStateUsing(fn (TImage $record): string => $record->formatted_file_size),
                
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
                Tables\Filters\SelectFilter::make('mimetype')
                    ->label('MIMEタイプ')
                    ->options([
                        'image/jpeg' => 'JPEG',
                        'image/png' => 'PNG',
                        'image/gif' => 'GIF',
                        'image/webp' => 'WebP',
                    ]),
                
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('作成日時（開始）'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('作成日時（終了）'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn ($query, $date) => $query->whereDate('created_at', '>=', $date)
                            )
                            ->when(
                                $data['created_until'],
                                fn ($query, $date) => $query->whereDate('created_at', '<=', $date)
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListTImages::route('/'),
            'create' => Pages\CreateTImage::route('/create'),
            'view' => Pages\ViewTImage::route('/{record}'),
            'edit' => Pages\EditTImage::route('/{record}/edit'),
        ];
    }
}