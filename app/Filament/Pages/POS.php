<?php

namespace App\Filament\Pages;

use App\Models\Invoice;
use App\Models\Item;
use App\Models\Setting;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;

class POS extends Page
{
    protected static ?int $navigationSort = 0;

    protected string $view = 'filament.pages.pos';

    protected static ?string $title = 'Point of Sale';

    public ?array $cart = [];

    public ?string $customer_name = '';

    public ?string $customer_contact = '';

    public ?string $payment_method = 'cash';

    public ?float $amount_paid = null;

    public ?float $card_amount = 0;

    public ?float $other_amount = 0;

    public ?string $other_payment_method = '';

    public ?string $discount_type = 'none'; // none, percentage, fixed

    public ?float $discount_value = 0;

    public ?string $search = '';

    public string $filterField = 'all';

    public array $searchResults = [];

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-shopping-cart';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'POS';
    }

    public function mount(): void
    {
        $this->searchResults = Item::active()->limit(20)->get()->toArray();
    }

    public function updatedSearch(): void
    {
        $this->performSearch();
    }

    public function updatedFilterField(): void
    {
        $this->performSearch();
    }

    public function updatedCart(): void
    {
        if ($this->payment_method === 'cash' && empty($this->amount_paid)) {
            $this->amount_paid = $this->grand_total;
        }
    }

    public function updatedPaymentMethod(): void
    {
        if ($this->payment_method === 'cash') {
            $this->amount_paid = $this->grand_total;
        }
    }

    public function setFilterField(string $field): void
    {
        $this->filterField = $field;
        $this->performSearch();
    }

    /**
     * Called when Enter is pressed in the search input (barcode scanner sends Enter).
     * If the scanned code matches exactly one product by barcode, add it to cart immediately.
     * Otherwise fall back to the normal search.
     */
    public function scanBarcode(): void
    {
        $code = trim($this->search ?? '');
        if ($code === '') {
            return;
        }

        // Try exact barcode match first
        $item = Item::active()->where('barcode', $code)->first();
        if ($item) {
            $this->addItemToCart($item->id);
            $this->search = '';
            $this->searchResults = Item::active()->limit(20)->get()->toArray();
            return;
        }

        // Try exact code match
        $item = Item::active()->where('code', $code)->first();
        if ($item) {
            $this->addItemToCart($item->id);
            $this->search = '';
            $this->searchResults = Item::active()->limit(20)->get()->toArray();
            return;
        }

        // If only one search result, add it directly (fast Enter-key workflow)
        if (count($this->searchResults) === 1) {
            $this->addItemToCart($this->searchResults[0]['id']);
            $this->search = '';
            $this->searchResults = Item::active()->limit(20)->get()->toArray();
            return;
        }

        // Fallback: normal search
        $this->performSearch();
    }

    protected function performSearch(): void
    {
        $query = Item::active();
        $search = $this->search ?? '';

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                match ($this->filterField) {
                    'name' => $q->where('name', 'like', "%{$search}%"),
                    'code' => $q->where('code', 'like', "%{$search}%"),
                    'barcode' => $q->where('barcode', 'like', "%{$search}%"),
                    default => $q->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%")
                        ->orWhere('barcode', 'like', "%{$search}%"),
                };
            });
        }

        $this->searchResults = $query->limit(20)->get()->toArray();
    }

    public function addItemToCart(int $itemId): void
    {
        $item = Item::active()->find($itemId);

        if (! $item) {
            return;
        }

        // Check stock availability
        $currentQtyInCart = 0;
        foreach ($this->cart as $cartItem) {
            if ($cartItem['item_id'] === $itemId) {
                $currentQtyInCart = $cartItem['quantity'];
                break;
            }
        }
        if ($currentQtyInCart >= $item->quantity) {
            Notification::make()
                ->title('Out of stock!')
                ->body("{$item->name} has only {$item->quantity} units available.")
                ->warning()
                ->send();
            return;
        }

        $taxRate = (float) (Setting::getValue('tax_rate') ?? 16);
        $existingIndex = null;

        foreach ($this->cart as $index => $cartItem) {
            if ($cartItem['item_id'] === $itemId) {
                $existingIndex = $index;
                break;
            }
        }

        if ($existingIndex !== null) {
            $this->cart[$existingIndex]['quantity']++;
            $this->recalculateLine($existingIndex, $taxRate);
        } else {
            $unitPrice = (float) $item->pre_tax_price;
            $taxAmount = round($unitPrice * $taxRate / 100, 2);
            $lineTotal = round($unitPrice + $taxAmount, 2);

            $this->cart[] = [
                'item_id' => $item->id,
                'item_name' => $item->name,
                'item_code' => $item->code,
                'quantity' => 1,
                'unit_price' => $unitPrice,
                'tax_amount' => $taxAmount,
                'line_total' => $lineTotal,
            ];
        }

        $this->search = '';
        $this->searchResults = Item::active()->limit(20)->get()->toArray();
    }

    public function updateQuantity(int $index, int $quantity): void
    {
        if (! isset($this->cart[$index])) {
            return;
        }

        if ($quantity <= 0) {
            unset($this->cart[$index]);
            $this->cart = array_values($this->cart);
            return;
        }

        $this->cart[$index]['quantity'] = $quantity;
        $taxRate = (float) (Setting::getValue('tax_rate') ?? 16);
        $this->recalculateLine($index, $taxRate);
    }

    public function removeCartItem(int $index): void
    {
        if (isset($this->cart[$index])) {
            unset($this->cart[$index]);
            $this->cart = array_values($this->cart);
        }
    }

    public function clearCart(): void
    {
        $this->cart = [];
        $this->customer_name = '';
        $this->customer_contact = '';
        $this->payment_method = 'cash';
        $this->amount_paid = null;
        $this->card_amount = 0;
        $this->other_amount = 0;
        $this->other_payment_method = '';
        $this->discount_type = 'none';
        $this->discount_value = 0;
    }

    protected function recalculateLine(int $index, float $taxRate): void
    {
        $item = $this->cart[$index];
        $taxAmount = round($item['unit_price'] * $item['quantity'] * $taxRate / 100, 2);
        $lineTotal = round($item['unit_price'] * $item['quantity'] + $taxAmount, 2);

        $this->cart[$index]['tax_amount'] = $taxAmount;
        $this->cart[$index]['line_total'] = $lineTotal;
    }

    #[Computed]
    public function taxRate(): float
    {
        return (float) (Setting::getValue('tax_rate') ?? 16);
    }

    #[Computed]
    public function subtotal(): float
    {
        return (float) array_reduce($this->cart, fn ($carry, $item) => $carry + ($item['unit_price'] * $item['quantity']), 0);
    }

    #[Computed]
    public function tax_total(): float
    {
        return (float) array_reduce($this->cart, fn ($carry, $item) => $carry + $item['tax_amount'], 0);
    }

    #[Computed]
    public function grand_total_before_discount(): float
    {
        return (float) array_reduce($this->cart, fn ($carry, $item) => $carry + $item['line_total'], 0);
    }

    #[Computed]
    public function discount_amount(): float
    {
        $total = $this->grand_total_before_discount();
        if ($this->discount_type === 'percentage' && $this->discount_value > 0) {
            return round($total * $this->discount_value / 100, 2);
        } elseif ($this->discount_type === 'fixed' && $this->discount_value > 0) {
            return min($this->discount_value, $total);
        }
        return 0;
    }

    #[Computed]
    public function grand_total(): float
    {
        return max(0, $this->grand_total_before_discount() - $this->discount_amount());
    }

    #[Computed]
    public function change(): float
    {
        $amountPaid = (float) ($this->amount_paid ?? 0);

        return max(0, $amountPaid - $this->grand_total);
    }

    public function createInvoice(): void
    {
        if (empty($this->cart)) {
            Notification::make()
                ->title('Cart is empty')
                ->warning()
                ->send();
            return;
        }

        if (empty($this->customer_name)) {
            Notification::make()
                ->title('Customer name is required')
                ->warning()
                ->send();
            return;
        }

        if ($this->payment_method === 'cash' && (($this->amount_paid ?? 0) < $this->grand_total)) {
            Notification::make()
                ->title('Amount paid is less than grand total')
                ->warning()
                ->send();
            return;
        }

        try {
            $invoice = DB::transaction(function () {
                $invoice = Invoice::create([
                    'invoice_number' => Invoice::generateInvoiceNumber(),
                    'customer_name' => $this->customer_name,
                    'customer_contact' => $this->customer_contact ?: null,
                    'subtotal' => $this->subtotal(),
                    'tax_total' => $this->tax_total(),
                    'discount_value' => $this->discount_value ?? 0,
                    'discount_type' => $this->discount_type ?? 'none',
                    'discount_amount' => $this->discount_amount(),
                    'grand_total' => $this->grand_total(),
                    'status' => 'paid',
                    'payment_method' => $this->payment_method,
                    'amount_paid' => $this->amount_paid ?? $this->grand_total(),
                    'card_amount' => $this->card_amount ?? 0,
                    'other_amount' => $this->other_amount ?? 0,
                    'other_payment_method' => $this->other_payment_method ?: null,
                    'change_amount' => $this->change(),
                    'created_by' => auth()->id(),
                ]);

                $lowStockItems = [];

                foreach ($this->cart as $cartItem) {
                    $invoice->items()->create([
                        'item_id' => $cartItem['item_id'],
                        'item_name' => $cartItem['item_name'],
                        'item_code' => $cartItem['item_code'],
                        'quantity' => $cartItem['quantity'],
                        'unit_price' => $cartItem['unit_price'],
                        'tax_amount' => $cartItem['tax_amount'],
                        'line_total' => $cartItem['line_total'],
                    ]);

                    // Auto-decrease stock
                    $item = Item::find($cartItem['item_id']);
                    if ($item) {
                        $item->decrement('quantity', $cartItem['quantity']);
                        if ($item->fresh()->isLowStock()) {
                            $lowStockItems[] = $item->name;
                        }
                    }
                }

                // Notify about low stock items
                if (!empty($lowStockItems)) {
                    Notification::make()
                        ->title('Low Stock Alert!')
                        ->body('Items running low: ' . implode(', ', $lowStockItems))
                        ->warning()
                        ->send();
                }

                // Activity Log
                \App\Models\ActivityLog::log('create', \App\Models\Invoice::class, $invoice->id, "Invoice {$invoice->invoice_number} created - Total: JD {$invoice->grand_total}");

                return $invoice;
            });

            Notification::make()
                ->title("Invoice {$invoice->invoice_number} created successfully!")
                ->success()
                ->send();

            $this->redirect(route('filament.admin.resources.invoices.view', $invoice));

        } catch (\Exception $e) {
            report($e);
            Notification::make()
                ->title('Failed to create invoice')
                ->body('An unexpected error occurred. Please try again.')
                ->danger()
                ->send();
        }
    }
}
