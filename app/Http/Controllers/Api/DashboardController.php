<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $today = now()->startOfDay();

        $todaySales = (float) Invoice::where('status', 'paid')->whereDate('created_at', $today)->sum('grand_total');
        $todayInvoices = Invoice::whereDate('created_at', $today)->count();
        $monthSales = (float) Invoice::where('status', 'paid')->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->sum('grand_total');
        $totalInvoices = Invoice::where('status', 'paid')->count();
        $totalRevenue = (float) Invoice::where('status', 'paid')->sum('grand_total');
        $totalTax = (float) Invoice::where('status', 'paid')->sum('tax_total');
        $totalDiscount = (float) Invoice::where('status', 'paid')->sum('discount_amount');

        // 14-day sales series
        $series = [];
        for ($i = 13; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $total = (float) Invoice::where('status', 'paid')->whereDate('created_at', $date)->sum('grand_total');
            $series[] = ['date' => $date, 'total' => $total];
        }

        $recentInvoices = Invoice::latest()->limit(8)->get(['id', 'invoice_number', 'customer_name', 'grand_total', 'status', 'payment_method', 'created_at']);

        $lowStock = Item::active()->lowStock()->get()->map(fn ($item) => [
            'id' => $item->id,
            'name' => $item->name,
            'code' => $item->code,
            'quantity' => $item->quantity,
            'low_stock_threshold' => $item->low_stock_threshold,
        ]);

        return response()->json([
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
        ]);
    }
}
