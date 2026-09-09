<?php

namespace App\Filament\Pages;

use App\Models\Invoice;
use App\Models\Item;
use App\Models\Setting;
use Carbon\Carbon;
use Filament\Pages\Page;

class AdminDashboard extends Page
{
    protected string $view = 'filament.pages.dashboard';

    protected static ?string $title = 'Dashboard';

    protected static ?int $navigationSort = -1;

    public static function getNavigationGroup(): ?string
    {
        return 'POS';
    }

    public static function getNavigationLabel(): string
    {
        return 'Dashboard';
    }

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-home';
    }

    public function getTodayInvoices(): int
    {
        return Invoice::whereDate('created_at', Carbon::today())->count();
    }

    public function getMonthInvoices(): int
    {
        return Invoice::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();
    }

    public function getTodaySales(): float
    {
        return (float) Invoice::where('status', 'paid')
            ->whereDate('created_at', Carbon::today())
            ->sum('grand_total');
    }

    public function getMonthSales(): float
    {
        return (float) Invoice::where('status', 'paid')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('grand_total');
    }

    public function getChartLabels(): array
    {
        $data = Invoice::where('status', 'paid')
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->selectRaw('DATE(created_at) as date, SUM(grand_total) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return $data->pluck('date')->map(fn ($d) => Carbon::parse($d)->format('d M'))->toArray();
    }

    public function getChartData(): array
    {
        $data = Invoice::where('status', 'paid')
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->selectRaw('DATE(created_at) as date, SUM(grand_total) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return $data->pluck('total')->toArray();
    }

    public function getRecentBuyers(): array
    {
        return Invoice::where('status', 'paid')
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->selectRaw('customer_name, SUM(grand_total) as total_spent')
            ->groupBy('customer_name')
            ->orderByDesc('total_spent')
            ->limit(10)
            ->get()
            ->toArray();
    }

    public function getRecentInvoices(): array
    {
        return Invoice::latest()
            ->limit(10)
            ->get()
            ->toArray();
    }

    public function getCashflowLabels(): array
    {
        $data = Invoice::where('status', 'paid')
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->selectRaw('DATE(created_at) as date, SUM(grand_total) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return $data->pluck('date')->map(fn ($d) => Carbon::parse($d)->format('d M'))->toArray();
    }

    public function getCashflowIncome(): array
    {
        $data = Invoice::where('status', 'paid')
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->selectRaw('DATE(created_at) as date, SUM(grand_total) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return $data->pluck('total')->toArray();
    }

    public function getCashflowExpenses(): array
    {
        // For now return zeros - can be linked to expense tracking later
        $labels = $this->getCashflowLabels();
        return array_fill(0, count($labels), 0);
    }

    public function getLowStockItems(): array
    {
        return Item::active()
            ->lowStock()
            ->orderBy('quantity')
            ->limit(10)
            ->get()
            ->toArray();
    }

    public function getRecentTransactions(): array
    {
        return Invoice::latest()
            ->limit(10)
            ->get()
            ->map(fn ($inv) => [
                'date' => $inv->created_at->format('d-m-Y'),
                'account' => $inv->customer_name,
                'debit' => $inv->payment_method === 'cash' ? $inv->grand_total : 0,
                'credit' => $inv->payment_method !== 'cash' ? $inv->grand_total : 0,
                'method' => ucfirst($inv->payment_method),
            ])
            ->toArray();
    }
}
