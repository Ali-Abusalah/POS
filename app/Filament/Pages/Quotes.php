<?php

namespace App\Filament\Pages;

use App\Models\Quote;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Pages\Page;

class Quotes extends Page
{
    protected static ?string $title = 'Quotes';

    protected static ?int $navigationSort = 3;

    protected string $view = 'filament.pages.quotes';

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-document-text';
    }

    public static function getNavigationLabel(): string
    {
        return 'Quotes';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Sales';
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public function getQuotes()
    {
        return Quote::latest()->get();
    }

    public function getTable(): Table
    {
        return Table::make(Quote::class)
            ->columns([
                Tables\Columns\TextColumn::make('quote_number')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('customer_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('grand_total')
                    ->money('JD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (Quote $record): string => $record->status_color),
                Tables\Columns\TextColumn::make('valid_until')
                    ->date('M d, Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('M d, Y h:i A')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'sent' => 'Sent',
                        'accepted' => 'Accepted',
                        'rejected' => 'Rejected',
                        'expired' => 'Expired',
                    ]),
            ])
            ->actions([
                \Filament\Actions\ViewAction::make(),
            ]);
    }
}
