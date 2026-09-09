<?php

namespace App\Filament\Actions;

use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;

class PrintBarcodeLabel
{
    public static function make(): Action
    {
        return Action::make('print_barcode')
            ->label('Print Label')
            ->icon('heroicon-o-printer')
            ->color('gray')
            ->form([
                Forms\Components\TextInput::make('copies')
                    ->label('Number of Labels')
                    ->numeric()
                    ->default(1)
                    ->minValue(1)
                    ->maxValue(50),
            ])
            ->url(fn ($record) => route('items.print-barcode', ['item' => $record->id, 'copies' => 1]))
            ->openUrlInNewTab();
    }

    public static function bulk(string $routeName = 'items.print-barcode-bulk'): BulkAction
    {
        return BulkAction::make('print_barcodes')
            ->label('Print Labels')
            ->icon('heroicon-o-printer')
            ->form([
                Forms\Components\TextInput::make('copies')
                    ->label('Labels per Item')
                    ->numeric()
                    ->default(1)
                    ->minValue(1)
                    ->maxValue(50),
            ])
            ->action(function (Collection $records, array $data) use ($routeName) {
                $ids = $records->pluck('id')->implode(',');
                $url = route($routeName, [
                    'ids' => $ids,
                    'copies' => $data['copies'] ?? 1,
                ]);
                return response()->json(['url' => $url]);
            })
            ->after(function () {
                // This will be handled by the frontend
            });
    }
}
