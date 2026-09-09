<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class InvoiceApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Invoice::query()->latest();

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($payment = $request->input('payment_method')) {
            $query->where('payment_method', $payment);
        }

        if ($from = $request->input('date_from')) {
            $query->whereDate('created_at', '>=', $from);
        }

        if ($to = $request->input('date_to')) {
            $query->whereDate('created_at', '<=', $to);
        }

        if ($itemName = $request->input('item_name')) {
            $query->whereHas('items', fn ($q) => $q->where('item_name', 'like', "%{$itemName}%"));
        }

        $invoices = $query->paginate($request->integer('per_page', 15));

        return response()->json($invoices);
    }

    public function show(Invoice $invoice): JsonResponse
    {
        $invoice->load('items');

        return response()->json([
            'invoice' => array_merge($invoice->toArray(), [
                'status_color' => $invoice->status_color,
            ]),
            'items' => $invoice->items,
        ]);
    }

    public function void(Request $request, Invoice $invoice): JsonResponse
    {
        $user = $request->user();
        if ($user?->role !== 'admin') {
            return response()->json(['error' => 'Only admins can void invoices.'], 403);
        }

        if ($invoice->status !== 'paid') {
            return response()->json(['error' => 'Only paid invoices can be voided.'], 422);
        }

        $data = $request->validate(['void_reason' => 'required|string|max:1000']);

        $invoice->update(['status' => 'void', 'void_reason' => $data['void_reason']]);

        \App\Models\ActivityLog::log('void', Invoice::class, $invoice->id, "Invoice {$invoice->invoice_number} voided");

        return response()->json(['success' => true, 'invoice' => $invoice->fresh()]);
    }

    public function refund(Request $request, Invoice $invoice): JsonResponse
    {
        $user = $request->user();
        if ($user?->role !== 'admin') {
            return response()->json(['error' => 'Only admins can refund invoices.'], 403);
        }

        if ($invoice->status !== 'paid') {
            return response()->json(['error' => 'Only paid invoices can be refunded.'], 422);
        }

        $data = $request->validate([
            'refund_amount' => 'required|numeric|min:0.01',
        ]);

        $invoice->update(['status' => 'refunded', 'refund_amount' => round((float) $data['refund_amount'], 2)]);

        \App\Models\ActivityLog::log('refund', Invoice::class, $invoice->id, "Invoice {$invoice->invoice_number} refunded JD {$data['refund_amount']}");

        return response()->json(['success' => true, 'invoice' => $invoice->fresh()]);
    }
}
