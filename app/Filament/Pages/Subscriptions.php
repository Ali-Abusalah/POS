<?php

namespace App\Filament\Pages;

use App\Models\Subscription;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Pages\Page;

class Subscriptions extends Page
{
    protected static ?string $title = 'Subscriptions';

    protected static ?int $navigationSort = 4;

    protected string $view = 'filament.pages.subscriptions';

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-arrow-path';
    }

    public static function getNavigationLabel(): string
    {
        return 'Subscriptions';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Sales';
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public function getSubscriptions()
    {
        return Subscription::latest()->get();
    }

    public function getTable(): Table
    {
        return Table::make(Subscription::class)
            ->columns([
                Tables\Columns\TextColumn::make('subscription_number')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('customer_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('plan_name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->money('JD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('billing_cycle')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'daily' => 'info',
                        'weekly' => 'warning',
                        'monthly' => 'success',
                        'quarterly' => 'info',
                        'yearly' => 'primary',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (Subscription $record): string => $record->status_color),
                Tables\Columns\TextColumn::make('next_billing_date')
                    ->date('M d, Y')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'paused' => 'Paused',
                        'cancelled' => 'Cancelled',
                        'expired' => 'Expired',
                    ]),
            ])
            ->actions([
                \Filament\Actions\ViewAction::make(),
            ]);
    }
}
