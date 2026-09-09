<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Models\Customer;
use Filament\Forms;
use Filament\Infolists;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Tables;
use Filament\Tables\Table;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationLabel = 'Customers';

    protected static ?string $modelLabel = 'Customer';

    protected static ?string $pluralModelLabel = 'Customers';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-users';
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
                Section::make('Customer Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Forms\Components\Textarea::make('address')
                            ->rows(2)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Section::make('Account')
                    ->schema([
                        Forms\Components\TextInput::make('balance')
                            ->numeric()
                            ->default(0)
                            ->prefix('JD')
                            ->step(0.01)
                            ->label('Credit Balance (owed to you)')
                            ->helperText('Positive = customer owes you money'),
                        Forms\Components\Textarea::make('notes')
                            ->rows(2)
                            ->columnSpanFull(),
                    ])
                    ->columns(1),
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
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->placeholder('N/A'),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable()
                    ->placeholder('N/A'),
                Tables\Columns\TextColumn::make('balance')
                    ->money('JD')
                    ->sortable()
                    ->color(fn (Customer $record) => $record->balance > 0 ? 'danger' : 'success'),
                Tables\Columns\TextColumn::make('total_purchases')
                    ->label('Total Spent')
                    ->getStateUsing(fn (Customer $record) => 'JD ' . number_format($record->totalPurchases(), 2))
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_invoices')
                    ->label('Invoices')
                    ->counts('invoices')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->sortable(),
            ])
            ->defaultSort('name')
            ->actions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Customer Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('name')
                            ->weight('bold'),
                        Infolists\Components\TextEntry::make('email')
                            ->placeholder('N/A'),
                        Infolists\Components\TextEntry::make('phone')
                            ->placeholder('N/A'),
                        Infolists\Components\TextEntry::make('address')
                            ->placeholder('N/A')
                            ->columnSpanFull(),
                    ])
                    ->columns(3),
                Section::make('Account')
                    ->schema([
                        Infolists\Components\TextEntry::make('balance')
                            ->label('Credit Balance')
                            ->money('JD')
                            ->weight('bold')
                            ->color(fn (Customer $record) => $record->balance > 0 ? 'danger' : 'success'),
                        Infolists\Components\TextEntry::make('total_purchases')
                            ->label('Total Spent')
                            ->getStateUsing(fn (Customer $record) => 'JD ' . number_format($record->totalPurchases(), 2)),
                        Infolists\Components\TextEntry::make('total_invoices')
                            ->label('Total Invoices')
                            ->getStateUsing(fn (Customer $record) => $record->totalInvoices()),
                    ])
                    ->columns(3),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
            'view' => Pages\ViewCustomer::route('/{record}'),
        ];
    }
}
