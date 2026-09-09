<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Quote;
use App\Models\QuoteItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuoteApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Quote::query()->latest();

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('quote_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $quotes = $query->paginate($request->integer('per_page', 15));

        return response()->json($quotes);
    }

    public function show(Quote $quote): JsonResponse
    {
        $quote->load('items');

        return response()->json([
            'quote' => array_merge($quote->toArray(), [
                'status_color' => $quote->status_color,
            ]),
            'items' => $quote->items,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_contact' => 'nullable|string|max:255',
            'valid_until' => 'nullable|date',
            'notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string',
            'items.*.item_code' => 'nullable|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.tax_amount' => 'nullable|numeric|min:0',
        ]);

        $subtotal = 0;
        $taxTotal = 0;
        foreach ($data['items'] as $item) {
            $lineTotal = $item['unit_price'] * $item['quantity'];
            $tax = $item['tax_amount'] ?? 0;
            $subtotal += $lineTotal;
            $taxTotal += $tax;
        }

        $quote = Quote::create([
            'quote_number' => 'Q-' . date('Y') . '-' . str_pad(Quote::count() + 1, 4, '0', STR_PAD_LEFT),
            'customer_name' => $data['customer_name'],
            'customer_contact' => $data['customer_contact'] ?? null,
            'subtotal' => $subtotal,
            'tax_total' => $taxTotal,
            'grand_total' => $subtotal + $taxTotal,
            'status' => 'draft',
            'valid_until' => $data['valid_until'] ?? null,
            'notes' => $data['notes'] ?? null,
            'created_by' => $request->user()?->id,
        ]);

        foreach ($data['items'] as $item) {
            $quote->items()->create([
                'item_id' => null,
                'item_name' => $item['item_name'],
                'item_code' => $item['item_code'] ?? null,
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'tax_amount' => $item['tax_amount'] ?? 0,
                'line_total' => ($item['unit_price'] * $item['quantity']) + ($item['tax_amount'] ?? 0),
            ]);
        }

        return response()->json(['success' => true, 'quote' => $quote->fresh()->load('items')], 201);
    }

    public function update(Request $request, Quote $quote): JsonResponse
    {
        $data = $request->validate([
            'customer_name' => 'sometimes|string|max:255',
            'customer_contact' => 'nullable|string|max:255',
            'status' => 'sometimes|string|in:draft,sent,accepted,rejected,expired',
            'valid_until' => 'nullable|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        $quote->update($data);

        return response()->json(['success' => true, 'quote' => $quote->fresh()]);
    }

    public function destroy(Quote $quote): JsonResponse
    {
        $quote->items()->delete();
        $quote->delete();

        return response()->json(['success' => true]);
    }

    public function convertToInvoice(Request $request, Quote $quote): JsonResponse
    {
        if ($quote->status !== 'accepted') {
            return response()->json(['error' => 'Only accepted quotes can be converted.'], 422);
        }

        $invoice = \App\Models\Invoice::create([
            'invoice_number' => 'INV-' . date('Y') . '-' . str_pad(\App\Models\Invoice::count() + 1, 4, '0', STR_PAD_LEFT),
            'customer_name' => $quote->customer_name,
            'customer_contact' => $quote->customer_contact,
            'subtotal' => $quote->subtotal,
            'tax_total' => $quote->tax_total,
            'grand_total' => $quote->grand_total,
            'status' => 'paid',
            'payment_method' => 'cash',
            'amount_paid' => $quote->grand_total,
            'change_amount' => 0,
            'created_by' => $request->user()?->id,
        ]);

        foreach ($quote->items as $qi) {
            $invoice->items()->create([
                'item_id' => $qi->item_id,
                'item_name' => $qi->item_name,
                'item_code' => $qi->item_code,
                'quantity' => $qi->quantity,
                'unit_price' => $qi->unit_price,
                'tax_amount' => $qi->tax_amount,
                'line_total' => $qi->line_total,
            ]);
        }

        $quote->update(['status' => 'accepted']);

        return response()->json(['success' => true, 'invoice' => $invoice]);
    }
}
