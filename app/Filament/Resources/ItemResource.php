<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ItemResource\Pages;
use App\Models\Item;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Tables;
use Filament\Tables\Table;

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;

    protected static ?string $navigationLabel = 'Items';

    protected static ?string $modelLabel = 'Item';

    protected static ?string $pluralModelLabel = 'Items';

    protected static ?int $navigationSort = 1;

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-cube';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Management';
    }

    public static function canAccess(): bool
    {
        $user = auth()->user();
        return $user && in_array($user->role, ['admin', 'manager']);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Item Information')
                    ->schema([
                        Forms\Components\FileUpload::make('image')
                            ->image()
                            ->disk('public')
                            ->directory('items')
                            ->maxSize(2048)
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('1:1')
                            ->imagePreviewHeight('150')
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('code')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\TextInput::make('barcode')
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Forms\Components\Select::make('category')
                            ->options([
                                'general' => 'General',
                                'electronics' => 'Electronics',
                                'groceries' => 'Groceries',
                                'clothing' => 'Clothing',
                                'furniture' => 'Furniture',
                                'stationery' => 'Stationery',
                            ])
                            ->searchable()
                            ->preload(),
                        Forms\Components\TextInput::make('unit')
                            ->default('piece')
                            ->maxLength(255),
                    ])
                    ->columns(2),
                Section::make('Pricing & Stock')
                    ->schema([
                        Forms\Components\TextInput::make('pre_tax_price')
                            ->required()
                            ->numeric()
                            ->prefix('JD')
                            ->step(0.01)
                            ->minValue(0),
                        Forms\Components\TextInput::make('quantity')
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->label('Stock Quantity'),
                        Forms\Components\TextInput::make('low_stock_threshold')
                            ->required()
                            ->numeric()
                            ->default(5)
                            ->minValue(0)
                            ->label('Low Stock Alert Threshold'),
                    ])
                    ->columns(3),
                Section::make('Description')
                    ->schema([
                        Forms\Components\Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
                Forms\Components\Toggle::make('is_active')
                    ->default(true)
                    ->label('Active'),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('barcode')
                    ->searchable()
                    ->placeholder('N/A'),
                Tables\Columns\TextColumn::make('category')
                    ->searchable()
                    ->badge(),
                Tables\Columns\TextColumn::make('pre_tax_price')
                    ->money('JD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Stock')
                    ->sortable()
                    ->badge()
                    ->color(fn (Item $record) => $record->quantity <= $record->low_stock_threshold ? 'danger' : 'success'),
                Tables\Columns\TextColumn::make('price_including_tax')
                    ->label('Price (incl. tax)')
                    ->money('JD')
                    ->getStateUsing(fn (Item $record) => $record->price_including_tax),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->options([
                        'general' => 'General',
                        'electronics' => 'Electronics',
                        'groceries' => 'Groceries',
                        'clothing' => 'Clothing',
                        'furniture' => 'Furniture',
                        'stationery' => 'Stationery',
                    ]),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status'),
            ])
            ->actions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\Action::make('print_barcode')
                    ->label('Print Label')
                    ->icon('heroicon-o-printer')
                    ->color('gray')
                    ->url(fn (Item $record) => route('items.print-barcode', $record))
                    ->openUrlInNewTab(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListItems::route('/'),
            'create' => Pages\CreateItem::route('/create'),
            'edit' => Pages\EditItem::route('/{record}/edit'),
        ];
    }
}
