<?php

namespace App\Filament\Pages;

use App\Models\Invoice;
use Carbon\Carbon;
use Filament\Pages\Page;

class ReportsExport extends Page
{
    protected static ?string $title = 'Export Reports';

    protected string $view = 'filament.pages.reports-export';

    public ?string $date_from = null;

    public ?string $date_to = null;

    public ?string $status = null;

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-arrow-down-tray';
    }

    public static function getNavigationLabel(): string
    {
        return 'Reports Export';
    }

    public static function getNavigationSort(): ?int
    {
        return 5;
    }

    public static function getNavigationGroup(): string | \UnitEnum | null
    {
        return 'Management';
    }

    public static function canAccess(): bool
    {
        $user = auth()->user();
        return $user && in_array($user->role, ['admin', 'manager']);
    }

    public function mount(): void
    {
        $this->date_from = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->date_to = Carbon::now()->format('Y-m-d');
    }

    public function exportCsv(): \Illuminate\Http\Response
    {
        $query = Invoice::query()
            ->whereDate('created_at', '>=', $this->date_from)
            ->whereDate('created_at', '<=', $this->date_to);

        if ($this->status) {
            $query->where('status', $this->status);
        }

        $invoices = $query->with('items')->get();

        $filename = 'invoices_' . $this->date_from . '_to_' . $this->date_to . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($invoices) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'Invoice #', 'Date', 'Customer', 'Contact', 'Payment Method',
                'Subtotal', 'Tax', 'Discount', 'Grand Total', 'Paid', 'Change', 'Status',
            ]);

            foreach ($invoices as $invoice) {
                fputcsv($file, [
                    $invoice->invoice_number,
                    $invoice->created_at->format('Y-m-d H:i'),
                    $invoice->customer_name,
                    $invoice->customer_contact ?? '',
                    $invoice->payment_method,
                    number_format($invoice->subtotal, 2),
                    number_format($invoice->tax_total, 2),
                    number_format($invoice->discount_amount ?? 0, 2),
                    number_format($invoice->grand_total, 2),
                    number_format($invoice->amount_paid, 2),
                    number_format($invoice->change_amount, 2),
                    $invoice->status,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
