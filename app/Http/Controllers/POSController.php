<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Invoice;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class POSController extends Controller
{
    public function index(Request $request)
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

        $products = $query->limit(50)->get();
        $taxRate = (float) (Setting::getValue('tax_rate') ?? 16);
        $cart = session('pos_cart', []);
        $customerName = session('pos_customer_name', 'Walk-in Customer');

        return Inertia::render('Pages/POS', [
            'products' => $products,
            'cart' => $cart,
            'customerName' => $customerName,
            'taxRate' => $taxRate,
            'search' => $search,
            'filterField' => $filterField,
        ]);
    }

    public function search(Request $request)
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

        $products = $query->limit(50)->get();

        return back()->with('products', $products);
    }

    public function scanBarcode(Request $request)
    {
        $code = trim($request->input('search', ''));
        if (empty($code)) {
            return back();
        }

        // Try exact barcode match first
        $item = Item::active()->where('barcode', $code)->first();
        if ($item) {
            return $this->addToCartById($item->id);
        }

        // Try exact code match
        $item = Item::active()->where('code', $code)->first();
        if ($item) {
            return $this->addToCartById($item->id);
        }

        // If only one search result, add it directly
        $search = $request->input('search', '');
        $filterField = $request->input('filterField', 'all');
        $query = Item::active();
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

        $results = $query->limit(2)->get();
        if ($results->count() === 1) {
            return $this->addToCartById($results->first()->id);
        }

        // Fallback: redirect with search query
        return redirect()->route('pos.index', ['search' => $search, 'filterField' => $filterField]);
    }

    protected function addToCartById(int $itemId)
    {
        $item = Item::active()->find($itemId);
        if (!$item) {
            return back()->withErrors(['item' => 'Item not found']);
        }

        $cart = session('pos_cart', []);

        $currentQtyInCart = 0;
        foreach ($cart as $cartItem) {
            if ($cartItem['item_id'] === $itemId) {
                $currentQtyInCart = $cartItem['quantity'];
                break;
            }
        }

        if ($currentQtyInCart >= $item->quantity) {
            return back()->withErrors(['stock' => "{$item->name} has only {$item->quantity} units available."]);
        }

        $taxRate = (float) (Setting::getValue('tax_rate') ?? 16);
        $existingIndex = null;

        foreach ($cart as $index => $cartItem) {
            if ($cartItem['item_id'] === $itemId) {
                $existingIndex = $index;
                break;
            }
        }

        if ($existingIndex !== null) {
            $cart[$existingIndex]['quantity']++;
            $cart[$existingIndex]['tax_amount'] = round($cart[$existingIndex]['unit_price'] * $cart[$existingIndex]['quantity'] * $taxRate / 100, 2);
            $cart[$existingIndex]['line_total'] = round($cart[$existingIndex]['unit_price'] * $cart[$existingIndex]['quantity'] + $cart[$existingIndex]['tax_amount'], 2);
        } else {
            $unitPrice = (float) $item->pre_tax_price;
            $taxAmount = round($unitPrice * $taxRate / 100, 2);
            $lineTotal = round($unitPrice + $taxAmount, 2);

            $cart[] = [
                'item_id' => $item->id,
                'item_name' => $item->name,
                'item_code' => $item->code,
                'quantity' => 1,
                'unit_price' => $unitPrice,
                'tax_amount' => $taxAmount,
                'line_total' => $lineTotal,
            ];
        }

        session(['pos_cart' => $cart]);

        return back()->withInput();
    }

    public function addToCart(Request $request, int $itemId)
    {
        $item = Item::active()->find($itemId);
        if (!$item) {
            return back()->withErrors(['item' => 'Item not found']);
        }

        $cart = session('pos_cart', []);

        // Check stock
        $currentQtyInCart = 0;
        foreach ($cart as $cartItem) {
            if ($cartItem['item_id'] === $itemId) {
                $currentQtyInCart = $cartItem['quantity'];
                break;
            }
        }

        if ($currentQtyInCart >= $item->quantity) {
            return back()->withErrors(['stock' => "{$item->name} has only {$item->quantity} units available."]);
        }

        $taxRate = (float) (Setting::getValue('tax_rate') ?? 16);
        $existingIndex = null;

        foreach ($cart as $index => $cartItem) {
            if ($cartItem['item_id'] === $itemId) {
                $existingIndex = $index;
                break;
            }
        }

        if ($existingIndex !== null) {
            $cart[$existingIndex]['quantity']++;
            $cart[$existingIndex]['tax_amount'] = round($cart[$existingIndex]['unit_price'] * $cart[$existingIndex]['quantity'] * $taxRate / 100, 2);
            $cart[$existingIndex]['line_total'] = round($cart[$existingIndex]['unit_price'] * $cart[$existingIndex]['quantity'] + $cart[$existingIndex]['tax_amount'], 2);
        } else {
            $unitPrice = (float) $item->pre_tax_price;
            $taxAmount = round($unitPrice * $taxRate / 100, 2);
            $lineTotal = round($unitPrice + $taxAmount, 2);

            $cart[] = [
                'item_id' => $item->id,
                'item_name' => $item->name,
                'item_code' => $item->code,
                'quantity' => 1,
                'unit_price' => $unitPrice,
                'tax_amount' => $taxAmount,
                'line_total' => $lineTotal,
            ];
        }

        session(['pos_cart' => $cart]);

        return back()->withInput();
    }

    public function updateQuantity(Request $request, int $index)
    {
        $quantity = (int) $request->input('quantity', 1);
        $cart = session('pos_cart', []);

        if (!isset($cart[$index])) {
            return back();
        }

        if ($quantity <= 0) {
            unset($cart[$index]);
            $cart = array_values($cart);
        } else {
            $cart[$index]['quantity'] = $quantity;
            $taxRate = (float) (Setting::getValue('tax_rate') ?? 16);
            $cart[$index]['tax_amount'] = round($cart[$index]['unit_price'] * $quantity * $taxRate / 100, 2);
            $cart[$index]['line_total'] = round($cart[$index]['unit_price'] * $quantity + $cart[$index]['tax_amount'], 2);
        }

        session(['pos_cart' => $cart]);

        return back();
    }

    public function removeFromCart(int $index)
    {
        $cart = session('pos_cart', []);

        if (isset($cart[$index])) {
            unset($cart[$index]);
            $cart = array_values($cart);
        }

        session(['pos_cart' => $cart]);

        return back();
    }

    public function clearCart()
    {
        session(['pos_cart' => []]);
        session(['pos_customer_name' => 'Walk-in Customer']);

        return back();
    }

    public function updateCustomer(Request $request)
    {
        session(['pos_customer_name' => $request->input('customer_name', 'Walk-in Customer')]);

        return back();
    }

    public function createInvoice(Request $request)
    {
        $cart = session('pos_cart', []);
        $customerName = session('pos_customer_name', 'Walk-in Customer');

        if (empty($cart)) {
            return back()->withErrors(['cart' => 'Cart is empty']);
        }

        $paymentMethod = $request->input('payment_method', 'cash');
        $amountPaid = (float) $request->input('amount_paid', 0);
        $cardAmount = (float) $request->input('card_amount', 0);
        $discountType = $request->input('discount_type', 'none');
        $discountValue = (float) $request->input('discount_value', 0);

        $taxRate = (float) (Setting::getValue('tax_rate') ?? 16);
        $subtotal = array_reduce($cart, fn ($carry, $item) => $carry + ($item['unit_price'] * $item['quantity']), 0);
        $taxTotal = array_reduce($cart, fn ($carry, $item) => $carry + $item['tax_amount'], 0);
        $grandTotalBeforeDiscount = array_reduce($cart, fn ($carry, $item) => $carry + $item['line_total'], 0);

        $discountAmount = 0;
        if ($discountType === 'percentage' && $discountValue > 0) {
            $discountAmount = round($grandTotalBeforeDiscount * $discountValue / 100, 2);
        } elseif ($discountType === 'fixed' && $discountValue > 0) {
            $discountAmount = min($discountValue, $grandTotalBeforeDiscount);
        }

        $grandTotal = max(0, $grandTotalBeforeDiscount - $discountAmount);
        $change = max(0, $amountPaid - $grandTotal);

        if ($paymentMethod === 'cash' && $amountPaid < $grandTotal) {
            return back()->withErrors(['payment' => 'Amount paid is less than grand total']);
        }

        try {
            $invoice = DB::transaction(function () use ($cart, $customerName, $paymentMethod, $amountPaid, $cardAmount, $discountType, $discountValue, $discountAmount, $grandTotal, $subtotal, $taxTotal, $change) {
                $invoice = Invoice::create([
                    'invoice_number' => Invoice::generateInvoiceNumber(),
                    'customer_name' => $customerName,
                    'customer_contact' => null,
                    'subtotal' => $subtotal,
                    'tax_total' => $taxTotal,
                    'discount_value' => $discountValue,
                    'discount_type' => $discountType,
                    'discount_amount' => $discountAmount,
                    'grand_total' => $grandTotal,
                    'status' => 'paid',
                    'payment_method' => $paymentMethod,
                    'amount_paid' => $amountPaid ?: $grandTotal,
                    'card_amount' => $cardAmount,
                    'other_amount' => 0,
                    'other_payment_method' => null,
                    'change_amount' => $change,
                    'created_by' => auth()->id(),
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

            session(['pos_cart' => []]);
            session(['pos_customer_name' => 'Walk-in Customer']);

            return redirect()->route('pos.index')->with('success', "Invoice {$invoice->invoice_number} created successfully!");
        } catch (\Exception $e) {
            report($e);
            return back()->withErrors(['invoice' => 'Failed to create invoice. Please try again.']);
        }
    }
}
