<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Item;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class POSApiController extends Controller
{
    public function products(Request $request): JsonResponse
    {
        $search = $request->input('search', '');
        $filterField = $request->input('filterField', 'all');

        $query = Item::active();

        if (!empty($search)) {
            $query->where(function ($q) use ($search, $filterField) {
                match ($filterField) {
                    'name' => $q->where('name', 'like', "%{$search}%"),
                    'code' => $q->where('code', 'like', "%{$search}%"),
                    'barcode' => $q->where('barcode', 'like', "%{$search}%"),
                    default => $q->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%")
                        ->orWhere('barcode', 'like', "%{$search}%"),
                };
            });
        }

        $products = $query->limit(50)->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'code' => $item->code,
                'barcode' => $item->barcode,
                'category' => $item->category,
                'pre_tax_price' => (float) $item->pre_tax_price,
                'quantity' => $item->quantity,
                'image_url' => $item->image_url,
            ];
        });

        return response()->json($products);
    }

    public function createInvoice(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'customer_name' => 'required|string',
            'payment_method' => 'required|in:cash,card,split',
            'amount_paid' => 'required_if:payment_method,cash|nullable|numeric|min:0',
            'card_amount' => 'nullable|numeric|min:0',
            'cart' => 'required|array|min:1',
            'cart.*.item_id' => 'required|integer|exists:items,id',
            'cart.*.quantity' => 'required|integer|min:1',
        ]);

        $cartData = $validated['cart'];
        $taxRate = (float) (Setting::getValue('tax_rate') ?? 16);
        $paymentMethod = $validated['payment_method'] === 'split' ? 'other' : $validated['payment_method'];

        $cart = [];
        foreach ($cartData as $cartItem) {
            $item = Item::active()->find($cartItem['item_id']);
            if (!$item) {
                return response()->json(['error' => "Item {$cartItem['item_id']} not found"], 422);
            }

            if ($cartItem['quantity'] > $item->quantity) {
                return response()->json(['error' => "{$item->name} has only {$item->quantity} units available"], 422);
            }

            $unitPrice = (float) $item->pre_tax_price;
            $taxAmount = round($unitPrice * $cartItem['quantity'] * $taxRate / 100, 2);
            $lineTotal = round($unitPrice * $cartItem['quantity'] + $taxAmount, 2);

            $cart[] = [
                'item_id' => $item->id,
                'item_name' => $item->name,
                'item_code' => $item->code,
                'quantity' => $cartItem['quantity'],
                'unit_price' => $unitPrice,
                'tax_amount' => $taxAmount,
                'line_total' => $lineTotal,
            ];
        }

        $subtotal = array_reduce($cart, fn ($carry, $item) => $carry + ($item['unit_price'] * $item['quantity']), 0);
        $taxTotal = array_reduce($cart, fn ($carry, $item) => $carry + $item['tax_amount'], 0);
        $grandTotal = array_reduce($cart, fn ($carry, $item) => $carry + $item['line_total'], 0);
        $amountPaid = (float) ($validated['amount_paid'] ?? $grandTotal);
        $change = max(0, $amountPaid - $grandTotal);

        try {
            $invoice = DB::transaction(function () use ($cart, $validated, $grandTotal, $subtotal, $taxTotal, $amountPaid, $change, $paymentMethod) {
                $invoice = Invoice::create([
                    'invoice_number' => Invoice::generateInvoiceNumber(),
                    'customer_name' => $validated['customer_name'],
                    'customer_contact' => null,
                    'subtotal' => $subtotal,
                    'tax_total' => $taxTotal,
                    'discount_value' => 0,
                    'discount_type' => 'none',
                    'discount_amount' => 0,
                    'grand_total' => $grandTotal,
                    'status' => 'paid',
                    'payment_method' => $paymentMethod,
                    'amount_paid' => $amountPaid,
                    'card_amount' => $validated['card_amount'] ?? 0,
                    'other_amount' => $validated['payment_method'] === 'split' ? ($grandTotal - ($validated['card_amount'] ?? 0)) : 0,
                    'other_payment_method' => $validated['payment_method'] === 'split' ? 'split' : null,
                    'change_amount' => $change,
                    'created_by' => auth()->id() ?? 1,
                ]);

                foreach ($cart as $cartItem) {
                    $invoice->items()->create([
                        'item_id' => $cartItem['item_id'],
                        'item_name' => $cartItem['item_name'],
                        'item_code' => $cartItem['item_code'],
                        'quantity' => $cartItem['quantity'],
                        'unit_price' => $cartItem['unit_price'],
                        'tax_amount' => $cartItem['tax_amount'],
                        'line_total' => $cartItem['line_total'],
                    ]);

                    $item = Item::find($cartItem['item_id']);
                    if ($item) {
                        $item->decrement('quantity', $cartItem['quantity']);
                    }
                }

                \App\Models\ActivityLog::log('create', Invoice::class, $invoice->id, "Invoice {$invoice->invoice_number} created - Total: JD {$invoice->grand_total}");

                return $invoice;
            });

            return response()->json([
                'success' => true,
                'invoice' => [
                    'id' => $invoice->id,
                    'invoice_number' => $invoice->invoice_number,
                    'grand_total' => $invoice->grand_total,
                ],
            ]);
        } catch (\Exception $e) {
            report($e);
            return response()->json(['error' => 'Failed to create invoice'], 500);
        }
    }
}
