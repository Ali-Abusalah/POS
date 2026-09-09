<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceResource\Pages;
use App\Models\Invoice;
use Filament\Forms;
use Filament\Infolists;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Tables;
use Filament\Tables\Table;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static ?string $navigationLabel = 'Invoices';

    protected static ?string $modelLabel = 'Invoice';

    protected static ?string $pluralModelLabel = 'Invoices';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'invoice_number';

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-document-text';
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

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Invoice Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('invoice_number')
                            ->label('Invoice #')
                            ->weight('bold'),
                        Infolists\Components\TextEntry::make('created_at')
                            ->dateTime('M d, Y h:i A'),
                        Infolists\Components\TextEntry::make('status')
                            ->badge()
                            ->color(fn (Invoice $record) => $record->status_color),
                        Infolists\Components\TextEntry::make('payment_method')
                            ->label('Payment'),
                    ])
                    ->columns(4),
                Section::make('Customer')
                    ->schema([
                        Infolists\Components\TextEntry::make('customer_name'),
                        Infolists\Components\TextEntry::make('customer_contact')
                            ->label('Contact')
                            ->placeholder('N/A'),
                    ])
                    ->columns(2),
                Section::make('Line Items')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('items')
                            ->schema([
                                Infolists\Components\TextEntry::make('item_name')
                                    ->label('Item'),
                                Infolists\Components\TextEntry::make('item_code')
                                    ->label('Code'),
                                Infolists\Components\TextEntry::make('quantity')
                                    ->label('Qty'),
                                Infolists\Components\TextEntry::make('unit_price')
                                    ->label('Unit Price')
                                    ->money('JD'),
                                Infolists\Components\TextEntry::make('tax_amount')
                                    ->label('Tax')
                                    ->money('JD'),
                                Infolists\Components\TextEntry::make('line_total')
                                    ->label('Total')
                                    ->money('JD'),
                            ])
                            ->columns(6),
                    ]),
                Section::make('Totals')
                    ->schema([
                        Infolists\Components\TextEntry::make('subtotal')
                            ->label('Subtotal')
                            ->money('JD'),
                        Infolists\Components\TextEntry::make('tax_total')
                            ->label('Tax Total')
                            ->money('JD'),
                        Infolists\Components\TextEntry::make('grand_total')
                            ->label('Grand Total')
                            ->money('JD')
                            ->weight('bold'),
                    ])
                    ->columns(3),
                Section::make('Payment')
                    ->schema([
                        Infolists\Components\TextEntry::make('amount_paid')
                            ->label('Amount Paid')
                            ->money('JD'),
                        Infolists\Components\TextEntry::make('change_amount')
                            ->label('Change')
                            ->money('JD'),
                    ])
                    ->columns(2)
                    ->visible(fn (Invoice $record) => $record->status === 'paid'),
                Section::make('Void / Refund')
                    ->schema([
                        Infolists\Components\TextEntry::make('void_reason')
                            ->label('Reason'),
                        Infolists\Components\TextEntry::make('refund_amount')
                            ->label('Refund Amount')
                            ->money('JD'),
                    ])
                    ->columns(2)
                    ->visible(fn (Invoice $record) => in_array($record->status, ['void', 'refunded'])),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('invoice_number')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('customer_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('M d, Y h:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('grand_total')
                    ->money('JD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (Invoice $record) => $record->status_color)
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'paid' => 'Paid',
                        'void' => 'Void',
                        'refunded' => 'Refunded',
                    ]),
                Tables\Filters\SelectFilter::make('payment_method')
                    ->options([
                        'cash' => 'Cash',
                        'card' => 'Card',
                        'other' => 'Other',
                    ]),
                Tables\Filters\Filter::make('date_range')
                    ->form([
                        Forms\Components\DatePicker::make('date_from')
                            ->label('From'),
                        Forms\Components\DatePicker::make('date_to')
                            ->label('To'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['date_from'], fn ($q, $date) => $q->whereDate('created_at', '>=', $date))
                            ->when($data['date_to'], fn ($q, $date) => $q->whereDate('created_at', '<=', $date));
                    }),
                Tables\Filters\Filter::make('item_name')
                    ->form([
                        Forms\Components\TextInput::make('item_name')
                            ->label('Contains Item'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query->when($data['item_name'], function ($q, $name) {
                            return $q->whereHas('items', function ($iq) use ($name) {
                                $iq->where('item_name', 'like', "%{$name}%");
                            });
                        });
                    }),
            ])
            ->actions([
                \Filament\Actions\ViewAction::make(),
                \Filament\Actions\Action::make('print_thermal')
                    ->label('Receipt')
                    ->icon('heroicon-o-printer')
                    ->url(fn (Invoice $record) => route('invoices.print-thermal', $record))
                    ->openUrlInNewTab()
                    ->color('gray'),
                \Filament\Actions\Action::make('print_a4')
                    ->label('A4')
                    ->icon('heroicon-o-document')
                    ->url(fn (Invoice $record) => route('invoices.print-a4', $record))
                    ->openUrlInNewTab()
                    ->color('gray'),
                \Filament\Actions\Action::make('void')
                    ->label('Void')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Void Invoice')
                    ->modalDescription('This action cannot be undone. The invoice will be marked as void.')
                    ->form([
                        Forms\Components\Textarea::make('void_reason')
                            ->label('Reason for voiding')
                            ->required()
                            ->rows(3),
                    ])
                    ->action(function (Invoice $record, array $data): void {
                        $old = $record->toArray();
                        $record->update([
                            'status' => 'void',
                            'void_reason' => $data['void_reason'],
                        ]);
                        \App\Models\ActivityLog::log('void', \App\Models\Invoice::class, $record->id, "Invoice {$record->invoice_number} voided - Reason: {$data['void_reason']}", $old, $record->toArray());
                    })
                    ->visible(fn (Invoice $record) => $record->status === 'paid' && (auth()->user()?->role ?? '') === 'admin'),
                \Filament\Actions\Action::make('refund')
                    ->label('Refund')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Refund Invoice')
                    ->modalDescription('Mark this invoice as refunded.')
                    ->form([
                        Forms\Components\TextInput::make('refund_amount')
                            ->label('Refund Amount')
                            ->required()
                            ->numeric()
                            ->default(fn (Invoice $record) => $record->grand_total)
                            ->prefix('JD'),
                    ])
                    ->action(function (Invoice $record, array $data): void {
                        $old = $record->toArray();
                        $record->update([
                            'status' => 'refunded',
                            'refund_amount' => $data['refund_amount'],
                        ]);
                        \App\Models\ActivityLog::log('refund', \App\Models\Invoice::class, $record->id, "Invoice {$record->invoice_number} refunded - Amount: JD {$data['refund_amount']}", $old, $record->toArray());
                    })
                    ->visible(fn (Invoice $record) => $record->status === 'paid' && (auth()->user()?->role ?? '') === 'admin'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInvoices::route('/'),
            'view' => Pages\ViewInvoice::route('/{record}'),
        ];
    }
}
