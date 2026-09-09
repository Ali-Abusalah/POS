<?php

namespace App\Filament\Pages;

use App\Models\Invoice;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Pages\Page;

class POSSalesHistory extends Page implements HasTable
{
    use InteractsWithTable;
    protected static ?string $title = 'POS Sales History';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.pages.pos-sales-history';

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-clock';
    }

    public static function getNavigationLabel(): string
    {
        return 'POS Sales';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Sales';
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public function getTable(): Table
    {
        return Table::make($this)
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
                    ->sortable(),
                Tables\Columns\TextColumn::make('grand_total')
                    ->money('JD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'cash' => 'success',
                        'card' => 'info',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (Invoice $record): string => $record->status_color),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('payment_method')
                    ->options([
                        'cash' => 'Cash',
                        'card' => 'Card',
                        'other' => 'Other',
                    ]),
                Tables\Filters\Filter::make('date_range')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('date_from')->label('From'),
                        \Filament\Forms\Components\DatePicker::make('date_to')->label('To'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['date_from'], fn ($q, $d) => $q->whereDate('created_at', '>=', $d))
                            ->when($data['date_to'], fn ($q, $d) => $q->whereDate('created_at', '<=', $d));
                    }),
            ])
            ->actions([
                \Filament\Actions\ViewAction::make(),
            ]);
    }
}
