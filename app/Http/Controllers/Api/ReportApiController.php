<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportApiController extends Controller
{
    public function sales(Request $request): JsonResponse
    {
        $from = $request->input('date_from', now()->startOfMonth()->toDateString());
        $to = $request->input('date_to', now()->toDateString());

        $paidInRange = Invoice::where('status', 'paid')
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to);

        $summary = [
            'total_invoices' => (clone $paidInRange)->count(),
            'total_revenue' => round((float) (clone $paidInRange)->sum('grand_total'), 2),
            'total_tax' => round((float) (clone $paidInRange)->sum('tax_total'), 2),
            'void_count' => Invoice::where('status', 'void')
                ->whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to)->count(),
        ];

        $daily = (clone $paidInRange)
            ->selectRaw("DATE(created_at) as date, COUNT(*) as invoice_count, SUM(grand_total) as total")
            ->groupByRaw('DATE(created_at)')
            ->orderByRaw('DATE(created_at) asc')
            ->get();

        $topItems = InvoiceItem::whereHas('invoice', function ($q) use ($from, $to) {
            $q->where('status', 'paid')
                ->whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to);
        })
            ->selectRaw('item_name, SUM(quantity) as total_qty, SUM(line_total) as total_revenue')
            ->groupBy('item_name')
            ->orderByDesc('total_revenue')
            ->limit(10)
            ->get();

        $topCustomers = (clone $paidInRange)
            ->selectRaw('customer_name, COUNT(*) as invoice_count, SUM(grand_total) as total_spent')
            ->groupBy('customer_name')
            ->orderByDesc('total_spent')
            ->limit(10)
            ->get();

        return response()->json([
            'date_from' => $from,
            'date_to' => $to,
            'summary' => $summary,
            'daily_sales' => $daily,
            'top_items' => $topItems,
            'top_customers' => $topCustomers,
        ]);
    }
}
