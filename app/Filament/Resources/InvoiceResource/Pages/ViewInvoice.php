<?php

namespace App\Filament\Resources\InvoiceResource\Pages;

use App\Filament\Resources\InvoiceResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewInvoice extends ViewRecord
{
    protected static string $resource = InvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('print_thermal')
                ->label('Print Receipt')
                ->icon('heroicon-o-printer')
                ->url(fn () => route('invoices.print-thermal', $this->record))
                ->openUrlInNewTab(),
            Actions\Action::make('print_a4')
                ->label('Print A4')
                ->icon('heroicon-o-document')
                ->url(fn () => route('invoices.print-a4', $this->record))
                ->openUrlInNewTab(),
        ];
    }
}
