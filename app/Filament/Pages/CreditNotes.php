<?php

namespace App\Filament\Pages;

use App\Models\CreditNote;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Pages\Page;

class CreditNotes extends Page
{
    protected static ?string $title = 'Credit Notes';

    protected static ?int $navigationSort = 5;

    protected string $view = 'filament.pages.credit-notes';

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-receipt-refund';
    }

    public static function getNavigationLabel(): string
    {
        return 'Credit Notes';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Sales';
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public function getCreditNotes()
    {
        return CreditNote::latest()->get();
    }

    public function getTable(): Table
    {
        return Table::make(CreditNote::class)
            ->columns([
                Tables\Columns\TextColumn::make('credit_note_number')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('customer_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('invoice.invoice_number')
                    ->label('Invoice')
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->money('JD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('reason')
                    ->limit(50),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (CreditNote $record): string => $record->status_color),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('M d, Y h:i A')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'issued' => 'Issued',
                        'applied' => 'Applied',
                        'voided' => 'Voided',
                    ]),
            ])
            ->actions([
                \Filament\Actions\ViewAction::make(),
            ]);
    }
}
