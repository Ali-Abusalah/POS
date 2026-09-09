<?php

use App\Http\Controllers\BarcodePrintController;
use App\Http\Controllers\InvoicePrintController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\WebAuthController;
use App\Models\{Invoice, Item, Customer, Setting, Quote, Subscription, CreditNote};
use Illuminate\Support\Facades\Route;

// Auth routes
Route::get('/login', [WebAuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [WebAuthController::class, 'login']);
Route::post('/logout', [WebAuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::get('/', function () {
    return redirect()->route('pos.index');
});

// Print routes - no auth required
Route::get('/invoices/{invoice}/print-thermal', [InvoicePrintController::class, 'printThermal'])
    ->name('invoices.print-thermal');
Route::get('/invoices/{invoice}/print-a4', [InvoicePrintController::class, 'printA4'])
    ->name('invoices.print-a4');
Route::get('/items/{item}/print-barcode', [BarcodePrintController::class, 'printSingle'])
    ->name('items.print-barcode');
Route::get('/items/print-barcodes', [BarcodePrintController::class, 'printBulk'])
    ->name('items.print-barcode-bulk');

// Blade View Routes
Route::middleware('auth')->group(function () {
    Route::get('/pos', function () {
        $products = Item::where('is_active', true)->latest()->get();
        $settings = [
            'tax_rate' => (float) (Setting::getValue('tax_rate') ?? 16),
            'primary_symbol' => Setting::getValue('primary_symbol') ?? 'JD',
        ];
        return view('pos.index', ['products' => $products, 'settings' => $settings]);
    })->name('pos.index');

    Route::get('/dashboard', function () {
        $today = now()->startOfDay();
        $todaySales = (float) Invoice::where('status', 'paid')->whereDate('created_at', $today)->sum('grand_total');
        $todayInvoices = Invoice::whereDate('created_at', $today)->count();
        $monthSales = (float) Invoice::where('status', 'paid')->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->sum('grand_total');
        $totalInvoices = Invoice::where('status', 'paid')->count();
        $totalRevenue = (float) Invoice::where('status', 'paid')->sum('grand_total');
        $totalTax = (float) Invoice::where('status', 'paid')->sum('tax_total');
        $totalDiscount = (float) Invoice::where('status', 'paid')->sum('discount_amount');
        $series = [];
        for ($i = 13; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $total = (float) Invoice::where('status', 'paid')->whereDate('created_at', $date)->sum('grand_total');
            $series[] = ['date' => $date, 'total' => $total];
        }
        $recentInvoices = Invoice::latest()->limit(8)->get(['id', 'invoice_number', 'customer_name', 'grand_total', 'status', 'payment_method', 'created_at']);
        $lowStock = Item::active()->lowStock()->get()->map(fn ($item) => ['id' => $item->id, 'name' => $item->name, 'code' => $item->code, 'quantity' => $item->quantity, 'low_stock_threshold' => $item->low_stock_threshold]);

        return view('dashboard', ['data' => [
            'today_sales' => round($todaySales, 2),
            'today_invoices' => $todayInvoices,
            'month_sales' => round($monthSales, 2),
            'total_invoices' => $totalInvoices,
            'total_revenue' => round($totalRevenue, 2),
            'total_tax' => round($totalTax, 2),
            'total_discount' => round($totalDiscount, 2),
            'target_sales' => (float) (Setting::getValue('target_sales') ?? 999999),
            'series' => $series,
            'recent_invoices' => $recentInvoices,
            'low_stock' => $lowStock,
            'currency_symbol' => Setting::getValue('primary_symbol') ?? 'JD',
        ]]);
    })->name('dashboard');

    Route::get('/items', function () {
        $items = Item::latest()->paginate(15);
        return view('items.index', ['items' => $items]);
    })->name('items.index');

    Route::get('/sales', function () {
        $invoices = Invoice::latest()->paginate(15);
        return view('sales.index', ['invoices' => $invoices]);
    })->name('sales.index');

    Route::get('/sales/create', function () {
        return view('sales.create');
    })->name('sales.create');

    Route::get('/invoices/{invoice}', function (Invoice $invoice) {
        $invoice->load('items');
        return view('invoices.show', ['invoice' => $invoice, 'items' => $invoice->items]);
    })->name('invoices.show');

    Route::get('/customers', function () {
        $customers = Customer::withCount('invoices')->latest()->paginate(15);
        return view('customers.index', ['customers' => $customers]);
    })->name('customers.index');

    Route::get('/quotes', function () {
        $quotes = Quote::latest()->paginate(15);
        return view('quotes.index', ['quotes' => $quotes]);
    })->name('quotes.index');

    Route::get('/subscriptions', function () {
        $subscriptions = Subscription::latest()->paginate(15);
        return view('subscriptions.index', ['subscriptions' => $subscriptions]);
    })->name('subscriptions.index');

    Route::get('/credit-notes', function () {
        $credit_notes = CreditNote::latest()->paginate(15);
        return view('credit-notes.index', ['credit_notes' => $credit_notes]);
    })->name('credit-notes.index');

    Route::get('/reports', function () {
        return view('reports.index');
    })->name('reports.index');

    Route::get('/settings', function () {
        $keys = ['tax_rate', 'site_url', 'primary_currency', 'primary_symbol', 'secondary_currency', 'secondary_symbol', 'exchange_rate', 'target_income', 'target_expenses', 'target_sales', 'target_net_income'];
        $settings = [];
        foreach ($keys as $key) {
            $settings[$key] = Setting::getValue($key);
        }
        return view('settings.index', ['settings' => $settings]);
    })->name('settings.index');
});
