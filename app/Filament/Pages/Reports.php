<?php

namespace App\Filament\Pages;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class Reports extends Page
{
    protected static ?int $navigationSort = 5;

    protected string $view = 'filament.pages.reports';

    protected static ?string $title = 'Sales Reports';

    public ?string $date_from = null;

    public ?string $date_to = null;

    public array $dailySales = [];

    public array $topItems = [];

    public array $topCustomers = [];

    public array $summary = [];

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-chart-bar';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Management';
    }

    public function mount(): void
    {
        $this->date_from = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->date_to = Carbon::now()->format('Y-m-d');
        $this->loadReport();
    }

    public function loadReport(): void
    {
        $from = $this->date_from;
        $to = $this->date_to;

        if (! $from || ! $to) {
            return;
        }

        $this->summary = [
            'total_invoices' => Invoice::where('status', 'paid')
                ->whereDate('created_at', '>=', $from)
                ->whereDate('created_at', '<=', $to)
                ->count(),
            'total_revenue' => Invoice::where('status', 'paid')
                ->whereDate('created_at', '>=', $from)
                ->whereDate('created_at', '<=', $to)
                ->sum('grand_total'),
            'total_tax' => Invoice::where('status', 'paid')
                ->whereDate('created_at', '>=', $from)
                ->whereDate('created_at', '<=', $to)
                ->sum('tax_total'),
            'void_count' => Invoice::where('status', 'void')
                ->whereDate('created_at', '>=', $from)
                ->whereDate('created_at', '<=', $to)
                ->count(),
        ];

        $this->dailySales = Invoice::where('status', 'paid')
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to)
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as invoice_count'),
                DB::raw('SUM(grand_total) as total')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->toArray();

        $this->topItems = InvoiceItem::whereHas('invoice', function ($q) use ($from, $to) {
                $q->where('status', 'paid')
                    ->whereDate('created_at', '>=', $from)
                    ->whereDate('created_at', '<=', $to);
            })
            ->select('item_name', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(line_total) as total_revenue'))
            ->groupBy('item_name')
            ->orderByDesc('total_revenue')
            ->limit(10)
            ->get()
            ->toArray();

        $this->topCustomers = Invoice::where('status', 'paid')
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to)
            ->select('customer_name', DB::raw('COUNT(*) as invoice_count'), DB::raw('SUM(grand_total) as total_spent'))
            ->groupBy('customer_name')
            ->orderByDesc('total_spent')
            ->limit(10)
            ->get()
            ->toArray();
    }
}
