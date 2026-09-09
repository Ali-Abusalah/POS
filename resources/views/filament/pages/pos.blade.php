@push('styles')
<style>
/* =============================================
   POS — Modern E-Commerce Style
   ============================================= */

/* Layout */
.pos-layout {
    display: flex; flex-direction: column; gap: 1.5rem;
    font-family: var(--font-sans);
}
@media (min-width: 1024px) {
    .pos-layout { flex-direction: row; gap: 1.75rem; }
}

/* === Items Panel === */
.pos-items-panel {
    flex: 1; display: flex; flex-direction: column;
    min-width: 0;
    border-radius: 16px; background: #ffffff;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
    border: 1px solid #e5e7eb;
}

/* Header Section */
.pos-panel-header {
    padding: 1rem 1.25rem;
    background: #ffffff;
    border-bottom: 1px solid #e5e7eb;
    border-radius: 16px 16px 0 0;
    display: flex !important;
    flex-direction: column !important;
    gap: 0.75rem;
}

/* Filter Tabs */
.pos-filter-tabs {
    display: flex; gap: 0.375rem;
    padding-left: 0;
}
.pos-filter-tab {
    padding: 0.375rem 0.75rem; border-radius: 6px;
    font-size: 0.6875rem; font-weight: 600; cursor: pointer;
    border: 1px solid #e2e8f0; background: #ffffff;
    color: #64748b; transition: all 0.15s ease;
    font-family: var(--font-sans); letter-spacing: 0.02em;
    white-space: nowrap;
}
.pos-filter-tab:hover { background: #f1f5f9; color: #1e293b; border-color: #cbd5e1; }
.pos-filter-tab.active {
    background: #3b82f6;
    color: #ffffff;
    border-color: #3b82f6;
}

/* Search */
.pos-search-box {
    position: relative; display: block; width: 100%;
}
.pos-search {
    width: 100%; border: 1.5px solid #e2e8f0;
    border-radius: 8px; padding: 0.5rem 0.75rem 0.5rem 2.25rem;
    font-size: 0.8125rem; font-family: var(--font-sans);
    color: #1e293b; background: #f8fafc; outline: none;
    transition: all 0.2s ease;
}
.pos-search::placeholder { color: #94a3b8; }
.pos-search:focus {
    border-color: #3b82f6; background: #ffffff;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.08);
}
.pos-search-icon {
    position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%);
    color: #94a3b8; pointer-events: none; width: 14px; height: 14px;
}
.pos-search-spinner {
    position: absolute; right: 0.625rem; top: 50%; transform: translateY(-50%);
    color: #3b82f6; display: flex; align-items: center;
}

/* Items Body */
.pos-items-body {
    padding: 1rem 1.25rem; flex: 1;
    overflow-y: auto; background: #f8fafc;
}
.pos-results-bar {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 0.75rem;
}
.pos-results-title {
    font-size: 0.6875rem; font-weight: 700; color: #94a3b8;
    text-transform: uppercase; letter-spacing: 0.08em;
}
.ui-badge-accent {
    background: #eff6ff; color: #3b82f6;
    padding: 0.2rem 0.625rem; border-radius: 9999px;
    font-size: 0.6875rem; font-weight: 600;
}

/* Item Cards */
.pos-items-grid {
    display: grid; grid-template-columns: repeat(auto-fill, minmax(185px, 1fr));
    gap: 0.875rem;
}
.pos-item {
    border: 1.5px solid #e5e7eb; border-radius: 12px;
    padding: 0; cursor: pointer; background: #ffffff;
    transition: all 0.2s ease; text-align: start;
    display: flex; flex-direction: column;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
    overflow: hidden;
}
.pos-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(59, 130, 246, 0.15), 0 2px 8px rgba(0, 0, 0, 0.06);
    border-color: #3b82f6;
}
.pos-item:active { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(59, 130, 246, 0.12); }

.pos-item-img {
    width: 100%; height: 110px; overflow: hidden;
    background: linear-gradient(135deg, #f8fafc, #f1f5f9); display: flex; align-items: center; justify-content: center;
}
.pos-item-img img { width: 100%; height: 100%; object-fit: cover; }
.pos-item-img-placeholder {
    width: 100%; height: 110px;
    background: linear-gradient(135deg, #f8fafc, #f1f5f9); display: flex; align-items: center; justify-content: center;
    color: #cbd5e1;
}

.pos-item-body {
    padding: 0.75rem 0.875rem 0.875rem;
    display: flex; flex-direction: column; gap: 0.375rem;
    flex: 1;
}

.pos-item-name {
    font-weight: 700; font-size: 0.8125rem; color: #1e293b;
    line-height: 1.3; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.pos-item-meta {
    font-size: 0.7rem; color: #94a3b8; white-space: nowrap;
    overflow: hidden; text-overflow: ellipsis;
}

.pos-category-badge {
    display: inline-flex; align-items: center; padding: 0.15rem 0.5rem;
    border-radius: 9999px; font-size: 0.625rem; font-weight: 600;
    text-transform: uppercase; letter-spacing: 0.04em;
    width: fit-content;
}
.pos-cat-electronics { background: #eff6ff; color: #2563eb; }
.pos-cat-stationery { background: #f0fdf4; color: #16a34a; }
.pos-cat-furniture { background: #fef3c7; color: #d97706; }
.pos-cat-groceries { background: #fef2f2; color: #dc2626; }
.pos-cat-clothing { background: #faf5ff; color: #9333ea; }
.pos-cat-general { background: #f1f5f9; color: #64748b; }

.pos-item-footer {
    display: flex; justify-content: space-between; align-items: flex-end;
    margin-top: auto; padding-top: 0.5rem;
    border-top: 1px solid #f1f5f9;
}
.pos-item-price {
    font-weight: 800; font-size: 1rem; color: #059669;
    line-height: 1;
}
.pos-item-stock { font-size: 0.65rem; line-height: 1; }
.pos-stock-available { color: #94a3b8; }
.pos-stock-low { color: #f59e0b; font-weight: 600; }
.pos-stock-out { color: #dc2626; font-weight: 700; }

/* Empty State */
.pos-empty {
    display: flex; flex-direction: column; align-items: center;
    justify-content: center; padding: 4rem 2rem;
    color: #64748b; text-align: center;
}
.pos-empty-icon { font-size: 3rem; margin-bottom: 1rem; opacity: 0.5; }
.pos-empty-text { font-size: 1rem; line-height: 1.6; font-weight: 500; }
.pos-empty-hint { font-size: 0.875rem; color: #94a3b8; margin-top: 0.5rem; }

/* === Cart Panel === */
.pos-cart-panel {
    width: 100%; display: flex; flex-direction: column;
    overflow: hidden; border-radius: 16px;
    background: #ffffff;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08), 0 1px 2px rgba(0, 0, 0, 0.06);
    border: 1px solid #e5e7eb;
    height: fit-content;
}
@media (min-width: 1024px) { .pos-cart-panel { width: 360px; flex-shrink: 0; } }

.pos-cart-header {
    padding: 1.25rem 1.5rem; border-bottom: 1px solid #e5e7eb;
    display: flex; align-items: center; justify-content: space-between;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-radius: 16px 16px 0 0;
}
.pos-cart-title {
    font-weight: 700; font-size: 1rem; color: #1e293b;
    display: flex; align-items: center; gap: 0.625rem;
}
.pos-cart-count {
    display: inline-flex; align-items: center; justify-content: center;
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: #ffffff; font-size: 0.75rem; font-weight: 700;
    border-radius: 9999px; min-width: 1.5rem; height: 1.5rem; padding: 0 8px;
    box-shadow: 0 2px 4px rgba(59, 130, 246, 0.3);
}

/* Customer Section */
.pos-customer-section {
    padding: 1rem 1.5rem; border-bottom: 1px solid #e5e7eb;
    background: #ffffff;
}
.pos-customer-input {
    width: 100%; border: 1.5px solid #e2e8f0;
    border-radius: 8px; padding: 0.625rem 0.75rem;
    font-size: 0.875rem; font-family: var(--font-sans);
    color: #1e293b; background: #f8fafc; outline: none;
    transition: all 0.2s ease;
}
.pos-customer-input:focus {
    border-color: #3b82f6; background: #ffffff;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}
.pos-customer-input::placeholder { color: #94a3b8; }
.pos-customer-label {
    font-size: 0.75rem; font-weight: 600; color: #64748b;
    text-transform: uppercase; letter-spacing: 0.05em;
    margin-bottom: 0.375rem; display: block;
}

/* Cart Body */
.pos-cart-body {
    overflow-y: auto; max-height: 280px;
    background: #f8fafc;
}
.pos-cart-body::-webkit-scrollbar { width: 4px; }
.pos-cart-body::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
.pos-cart-body::-webkit-scrollbar-track { background: transparent; }
.pos-cart-row {
    display: flex; align-items: center; gap: 0.75rem;
    padding: 0.875rem 1.5rem; border-bottom: 1px solid #e5e7eb;
    transition: background 0.15s ease; background: #ffffff;
}
.pos-cart-row:hover { background: #f1f5f9; }
.pos-cart-row:last-child { border-bottom: none; }
.pos-cart-row-info { flex: 1; min-width: 0; }
.pos-cart-row-name {
    font-size: 0.8125rem; font-weight: 600; color: #1e293b;
    overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
}
.pos-cart-row-sub { font-size: 0.8125rem; color: #64748b; margin-top: 2px; }

/* Stepper */
.pos-stepper {
    display: flex; align-items: center; border: 2px solid #e2e8f0;
    border-radius: 8px; overflow: hidden; flex-shrink: 0;
    background: #ffffff;
}
.pos-stepper-btn {
    width: 2rem; height: 2rem; display: flex; align-items: center;
    justify-content: center; background: #f8fafc; border: none;
    cursor: pointer; font-size: 1rem; font-weight: 600;
    color: #475569; transition: all 0.15s ease;
    user-select: none; line-height: 1;
}
.pos-stepper-btn:hover { background: #e2e8f0; color: #1e293b; }
.pos-stepper-val {
    width: 2.25rem; text-align: center; font-size: 0.9375rem; font-weight: 700;
    color: #1e293b; border-inline: 2px solid #e2e8f0; padding: 4px 0;
}
.pos-cart-row-total {
    font-weight: 700; font-size: 0.9375rem; color: #1e293b;
    min-width: 4rem; text-align: end; flex-shrink: 0;
}
.pos-remove-btn {
    width: 1.75rem; height: 1.75rem; border-radius: 8px; display: flex;
    align-items: center; justify-content: center; border: none;
    background: transparent; cursor: pointer; color: #94a3b8;
    font-size: 1.125rem; transition: all 0.15s ease; flex-shrink: 0; line-height: 1;
}
.pos-remove-btn:hover { background: #fee2e2; color: #dc2626; }

/* Summary */
.pos-summary-section {
    padding: 1rem 1.5rem; border-top: 1px solid #e5e7eb;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
}
.pos-summary-row {
    display: flex; justify-content: space-between; font-size: 0.875rem;
    color: #64748b; padding: 4px 0;
}
.pos-summary-total {
    display: flex; justify-content: space-between; padding-top: 0.75rem;
    margin-top: 0.625rem; border-top: 2px solid #e2e8f0;
    font-size: 1.125rem; font-weight: 700; color: #1e293b;
}
.pos-summary-paid {
    font-size: 0.875rem; color: #64748b;
    display: flex; justify-content: space-between; padding: 3px 0;
}
.pos-summary-change {
    font-size: 0.9375rem; font-weight: 700; color: #059669;
    display: flex; justify-content: space-between; padding: 3px 0;
}

/* Checkout */
.pos-checkout-section {
    padding: 1rem 1.5rem 1.5rem; border-top: 1px solid #e5e7eb;
    background: #ffffff; border-radius: 0 0 16px 16px;
}
.pos-checkout {
    width: 100%; border: none; border-radius: 12px;
    padding: 1rem; font-size: 1rem; font-weight: 700;
    font-family: var(--font-sans); cursor: pointer;
    transition: all 0.2s ease;
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: #ffffff;
    box-shadow: 0 4px 14px rgba(59, 130, 246, 0.4);
}
.pos-checkout:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(59, 130, 246, 0.5);
}
.pos-checkout:active:not(:disabled) { transform: translateY(0); }
.pos-checkout:disabled { opacity: 0.5; cursor: not-allowed; transform: none; box-shadow: none; }

/* Ghost Button */
.ui-btn-ghost {
    background: transparent; border: 1px solid #e2e8f0;
    color: #64748b; padding: 0.375rem 0.75rem; border-radius: 8px;
    font-size: 0.8125rem; font-weight: 500; cursor: pointer;
    transition: all 0.15s ease;
}
.ui-btn-ghost:hover { background: #f1f5f9; color: #1e293b; border-color: #cbd5e1; }

/* Animations */
@keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
.animate-spin { animation: spin 1s linear infinite; }
</style>
@endpush

<x-ui.layout title="Point of Sale" :subtitle="now()->format('l, M j, Y')">
    <div class="pos-layout">
        {{-- Left: Search + Items --}}
        <div class="pos-items-panel">
            <div class="pos-panel-header">
                <div class="pos-filter-tabs">
                    <button wire:click="setFilterField('all')" class="pos-filter-tab {{ $filterField === 'all' ? 'active' : '' }}">All</button>
                    <button wire:click="setFilterField('name')" class="pos-filter-tab {{ $filterField === 'name' ? 'active' : '' }}">Name</button>
                    <button wire:click="setFilterField('code')" class="pos-filter-tab {{ $filterField === 'code' ? 'active' : '' }}">Code</button>
                    <button wire:click="setFilterField('barcode')" class="pos-filter-tab {{ $filterField === 'barcode' ? 'active' : '' }}">Barcode</button>
                </div>
                <div class="pos-search-box">
                    <svg class="pos-search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    <input
                        type="text"
                        id="pos-search-input"
                        placeholder="Scan barcode or search products..."
                        class="pos-search"
                        autofocus
                        wire:model.live.debounce.300ms="search"
                        wire:keydown.enter="scanBarcode()"
                    />
                    <div wire:loading wire:target="search" class="pos-search-spinner">
                        <svg class="animate-spin" width="18" height="18" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" opacity="0.25"/><path d="M4 12a8 8 0 018-8" stroke="currentColor" stroke-width="3" stroke-linecap="round"/></svg>
                    </div>
                </div>
            </div>

            <div class="pos-items-body">
                @if (!empty($searchResults))
                    <div class="pos-results-bar">
                        <span class="pos-results-title">{{ !empty($search) ? 'Search Results' : 'All Products' }}</span>
                        <span class="ui-badge-accent">{{ count($searchResults) }} found</span>
                    </div>
                    <div class="pos-items-grid">
                        @foreach ($searchResults as $result)
                            @php
                                $cat = $result['category'] ?? 'general';
                                $catClass = match($cat) {
                                    'electronics' => 'pos-cat-electronics',
                                    'stationery' => 'pos-cat-stationery',
                                    'furniture' => 'pos-cat-furniture',
                                    'groceries' => 'pos-cat-groceries',
                                    'clothing' => 'pos-cat-clothing',
                                    default => 'pos-cat-general',
                                };
                                $qty = $result['quantity'] ?? 0;
                                $imgUrl = $result['image_url'] ?? null;
                            @endphp
                            <button wire:click="addItemToCart({{ $result['id'] }})" class="pos-item">
                                @if($imgUrl)
                                    <div class="pos-item-img">
                                        <img src="{{ $imgUrl }}" alt="{{ $result['name'] }}" loading="lazy" />
                                    </div>
                                @else
                                    <div class="pos-item-img-placeholder">
                                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" opacity="0.3"><rect x="3" y="3" width="18" height="18" rx="3"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
                                    </div>
                                @endif
                                <div class="pos-item-body">
                                    <div class="pos-item-name" title="{{ $result['name'] }}">{{ $result['name'] }}</div>
                                    <div class="pos-item-meta">{{ $result['code'] }}</div>
                                    <span class="pos-category-badge {{ $catClass }}">{{ ucfirst($cat) }}</span>
                                    <div class="pos-item-footer">
                                        <div class="pos-item-price">JD {{ number_format($result['pre_tax_price'], 2) }}</div>
                                        <div class="pos-item-stock">
                                            @if($qty > 5)
                                                <span class="pos-stock-available">In stock: {{ $qty }}</span>
                                            @elseif($qty > 0)
                                                <span class="pos-stock-low">Low: {{ $qty }}</span>
                                            @else
                                                <span class="pos-stock-out">Out</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </button>
                        @endforeach
                    </div>
                @else
                    <div class="pos-empty">
                        <div class="pos-empty-icon">📦</div>
                        <div class="pos-empty-text">
                            @if (empty($search))
                                Start typing to search for products
                            @else
                                No products found matching "{{ $search }}"
                            @endif
                        </div>
                        <div class="pos-empty-hint">Search by name, code, or barcode</div>
                    </div>
                @endif

                <div wire:loading wire:target="search" style="text-align:center; padding:2rem; color:#64748b;">
                    <svg class="animate-spin" width="24" height="24" viewBox="0 0 24 24" fill="none" style="margin:0 auto 0.5rem;"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" opacity="0.25"/><path d="M4 12a8 8 0 018-8" stroke="currentColor" stroke-width="3" stroke-linecap="round"/></svg>
                    <div style="font-size:0.875rem;">Searching...</div>
                </div>
            </div>
        </div>

        {{-- Right: Cart --}}
        <div class="pos-cart-panel">
            <div class="pos-cart-header">
                <h3 class="pos-cart-title">
                    Shopping Cart
                    @if (count($cart) > 0)
                        <span class="pos-cart-count">{{ count($cart) }}</span>
                    @endif
                </h3>
                @if (!empty($cart))
                    <button wire:click="clearCart" class="ui-btn-ghost">Clear all</button>
                @endif
            </div>

            {{-- Customer Field (always visible) --}}
            <div class="pos-customer-section">
                <label class="pos-customer-label">Customer</label>
                <input
                    type="text"
                    wire:model.live.debounce.500ms="customer_name"
                    placeholder="Walk-in Customer"
                    class="pos-customer-input"
                />
            </div>

            <div class="pos-cart-body">
                @if (empty($cart))
                    <div class="pos-empty" style="min-height:100px; padding:1.5rem 1rem;">
                        <div class="pos-empty-icon" style="font-size:2rem; margin-bottom:0.5rem;">🛒</div>
                        <div class="pos-empty-text" style="font-size:0.875rem;">Cart is empty</div>
                        <div class="pos-empty-hint" style="font-size:0.75rem;">Click a product to add it</div>
                    </div>
                @else
                    @foreach ($cart as $index => $item)
                        <div class="pos-cart-row">
                            <div class="pos-cart-row-info">
                                <div class="pos-cart-row-name" title="{{ $item['item_name'] }}">{{ $item['item_name'] }}</div>
                                <div class="pos-cart-row-sub">JD {{ number_format($item['unit_price'], 2) }} × {{ $item['quantity'] }}</div>
                            </div>
                            <div class="pos-stepper">
                                <button wire:click="updateQuantity({{ $index }}, {{ $item['quantity'] - 1 }})" class="pos-stepper-btn">&minus;</button>
                                <span class="pos-stepper-val">{{ $item['quantity'] }}</span>
                                <button wire:click="updateQuantity({{ $index }}, {{ $item['quantity'] + 1 }})" class="pos-stepper-btn">+</button>
                            </div>
                            <div class="pos-cart-row-total">JD {{ number_format($item['line_total'], 2) }}</div>
                            <button wire:click="removeCartItem({{ $index }})" class="pos-remove-btn">&times;</button>
                        </div>
                    @endforeach
                @endif
            </div>

            @if (!empty($cart))
                {{-- Payment Section --}}
                <div style="padding: 0.75rem 1.25rem; border-top: 1px solid #e5e7eb; background: #ffffff;">
                    <label class="pos-customer-label">Payment Method</label>
                    <select wire:model.live="payment_method" style="width:100%; border: 1.5px solid #e2e8f0; border-radius: 8px; padding: 0.4rem 0.625rem; font-size: 0.8125rem; font-family: var(--font-sans); color: #1e293b; background: #f8fafc; outline: none;">
                        <option value="cash">Cash</option>
                        <option value="card">Card</option>
                        <option value="split">Split Payment</option>
                    </select>
                    @if ($payment_method === 'cash')
                        <label class="pos-customer-label" style="margin-top: 0.5rem;">Amount Paid</label>
                        <input
                            type="number"
                            wire:model.live="amount_paid"
                            min="0"
                            step="0.01"
                            placeholder="0.00"
                            style="width:100%; border: 1.5px solid #e2e8f0; border-radius: 8px; padding: 0.4rem 0.625rem; font-size: 0.8125rem; font-family: var(--font-sans); color: #1e293b; background: #f8fafc; outline: none;"
                        />
                    @endif
                    @if ($payment_method === 'split')
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.5rem; margin-top:0.5rem;">
                            <div>
                                <label class="pos-customer-label">Cash</label>
                                <input type="number" wire:model.live="amount_paid" min="0" step="0.01" placeholder="0.00" style="width:100%; border: 1.5px solid #e2e8f0; border-radius: 8px; padding: 0.4rem 0.625rem; font-size: 0.8125rem; font-family: var(--font-sans); color: #1e293b; background: #f8fafc; outline: none;" />
                            </div>
                            <div>
                                <label class="pos-customer-label">Card</label>
                                <input type="number" wire:model.live="card_amount" min="0" step="0.01" placeholder="0.00" style="width:100%; border: 1.5px solid #e2e8f0; border-radius: 8px; padding: 0.4rem 0.625rem; font-size: 0.8125rem; font-family: var(--font-sans); color: #1e293b; background: #f8fafc; outline: none;" />
                            </div>
                        </div>
                    @endif
                </div>

                <div class="pos-summary-section" style="padding: 0.75rem 1.25rem;">
                    <div class="pos-summary-row">
                        <span>Subtotal</span>
                        <span>JD {{ number_format($this->subtotal(), 2) }}</span>
                    </div>
                    <div class="pos-summary-row">
                        <span>Tax ({{ $this->taxRate() }}%)</span>
                        <span>JD {{ number_format($this->tax_total(), 2) }}</span>
                    </div>
                    @if ($this->discount_amount() > 0)
                        <div class="pos-summary-row" style="color: #dc2626;">
                            <span>Discount</span>
                            <span>- JD {{ number_format($this->discount_amount(), 2) }}</span>
                        </div>
                    @endif
                    <div class="pos-summary-total">
                        <span style="font-weight:800; font-size:1rem;">Grand Total</span>
                        <span style="font-weight:800; font-size:1.05rem; color:#1e293b;">JD {{ number_format($this->grand_total(), 2) }}</span>
                    </div>
                    @if ($payment_method === 'cash' && ($amount_paid ?? 0) > 0)
                        <div class="pos-summary-paid">
                            <span>Paid</span>
                            <span>JD {{ number_format($amount_paid, 2) }}</span>
                        </div>
                        @if ($this->change() > 0)
                            <div class="pos-summary-change">
                                <span>Change Due</span>
                                <span>JD {{ number_format($this->change(), 2) }}</span>
                            </div>
                        @endif
                    @endif
                </div>

                <div class="pos-checkout-section" style="padding: 0.75rem 1.25rem 1.25rem;">
                    <button
                        wire:click="createInvoice"
                        @disabled(empty($cart))
                        class="pos-checkout"
                    >
                        Create Invoice
                    </button>
                </div>
            @endif
        </div>
    </div>
</x-ui.layout>
