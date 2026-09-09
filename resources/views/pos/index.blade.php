@extends('layouts.app')
@section('page-title', 'Point of Sale')
@section('content')
<div class="pos-page-title">
    <h1>Point of Sale</h1>
    <p>{{ now()->format('l, F j, Y') }}</p>
</div>
<div class="pos-layout">
    <div class="pos-items-panel">
        <div class="pos-panel-header">
            <div class="pos-filter-tabs">
                <button class="pos-filter-tab active" data-filter="all">All</button>
                <button class="pos-filter-tab" data-filter="name">Name</button>
                <button class="pos-filter-tab" data-filter="code">Code</button>
                <button class="pos-filter-tab" data-filter="barcode">Barcode</button>
            </div>
            <div class="pos-search-box">
                <svg class="pos-search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="text" class="pos-search" id="posSearch" placeholder="Search products..." autocomplete="off" autocapitalize="off" autocorrect="off">
                <div class="pos-search-spinner" id="searchSpinner" style="display:none;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="animate-spin"><path d="M21 12a9 9 0 11-6.219-8.56"/></svg>
                </div>
            </div>
        </div>
        <div class="pos-items-body">
            <div class="pos-results-bar">
                <span class="pos-results-title">Products</span>
                <span class="ui-badge-accent" id="resultCount">0 items</span>
            </div>
            <div class="pos-items-grid" id="productsGrid"></div>
            <div class="pos-empty" id="emptyState" style="display:none;">
                <div class="pos-empty-icon">📦</div>
                <div class="pos-empty-text">No products found</div>
                <div class="pos-empty-hint">Try a different search term</div>
            </div>
        </div>
    </div>

    <div class="pos-cart-panel">
        <div class="pos-cart-header">
            <div class="pos-cart-title">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/></svg>
                Cart
                <span class="pos-cart-count" id="cartCount">0</span>
            </div>
            <button class="ui-btn-ghost ui-btn-sm" id="clearCartBtn" style="font-size:0.75rem;">Clear all</button>
        </div>

        <div class="pos-customer-section">
            <label class="pos-customer-label">Customer Name</label>
            <input type="text" class="pos-customer-input" id="customerName" placeholder="Walk-in Customer" value="Walk-in Customer" onfocus="if(this.value==='Walk-in Customer')this.value='';" onblur="if(!this.value.trim())this.value='Walk-in Customer';">
        </div>

        <div class="pos-cart-body" id="cartBody">
            <div class="pos-empty" id="cartEmpty">
                <div class="pos-empty-icon">🛒</div>
                <div class="pos-empty-text" style="font-size:0.875rem;">Cart is empty</div>
                <div class="pos-empty-hint">Click on products to add them</div>
            </div>
        </div>

        <div class="pos-summary-section">
            <div class="pos-summary-row">
                <span>Subtotal</span>
                <span id="subtotalDisplay">{{ $settings['primary_symbol'] }} 0.00</span>
            </div>
            <div class="pos-summary-row">
                <span>Tax ({{ $settings['tax_rate'] }}%)</span>
                <span id="taxDisplay">{{ $settings['primary_symbol'] }} 0.00</span>
            </div>
            <div class="pos-summary-total">
                <span>Total</span>
                <span id="totalDisplay">{{ $settings['primary_symbol'] }} 0.00</span>
            </div>
        </div>

        <div class="pos-checkout-section">
            <div style="margin-bottom:0.75rem;">
                <label class="pos-customer-label">Payment Method</label>
                <select class="ui-select" id="paymentMethod" style="font-size:0.8125rem;">
                    <option value="cash">Cash</option>
                    <option value="card">Card</option>
                    <option value="split">Split</option>
                </select>
            </div>
            <div id="cashSection" style="margin-bottom:0.75rem;">
                <label class="pos-customer-label">Amount Paid</label>
                <div style="display:flex;gap:0.5rem;">
                    <input type="number" class="ui-input" id="amountPaid" placeholder="0.00" step="0.01" min="0" inputmode="decimal" style="font-size:0.8125rem;" onfocus="if(this.value==='0.00'||this.value==='0')this.value='';" onblur="if(!this.value.trim())this.value='0';">
                </div>
            </div>
            <div id="cardSection" style="display:none;margin-bottom:0.75rem;">
                <label class="pos-customer-label">Card Amount</label>
                <input type="number" class="ui-input" id="cardAmount" placeholder="0.00" step="0.01" min="0" inputmode="decimal" style="font-size:0.8125rem;" onfocus="if(this.value==='0.00'||this.value==='0')this.value='';" onblur="if(!this.value.trim())this.value='0';">
            </div>
            <div style="display:flex;justify-content:space-between;font-size:0.8125rem;color:var(--text-secondary);margin-bottom:0.75rem;">
                <span>Change</span>
                <span id="changeDisplay" style="font-weight:600;color:var(--success);">{{ $settings['primary_symbol'] }} 0.00</span>
            </div>
            <button class="pos-checkout" id="checkoutBtn" disabled>Checkout</button>
        </div>
    </div>
</div>

<style>
    .pos-page-title h1 { font-size: 1.5rem; font-weight: 700; color: #000080; margin: 0 0 0.25rem; }
    .pos-page-title p { font-size: 0.875rem; color: #555555; margin: 0 0 1rem; }
    .main-content { padding: 1rem !important; }
    .pos-layout{display:flex;flex-direction:column;gap:1.5rem;font-family:var(--font-sans);}
    @media(min-width:1024px){.pos-layout{flex-direction:row;gap:0;height:calc(100vh - 120px);}}
    .pos-items-panel{flex:1;display:flex;flex-direction:column;min-width:0;border-radius:16px;background:#ffffff;box-shadow:0 1px 3px rgba(0,0,0,0.06);border:1px solid #e0e0e0;overflow:hidden;}
    .pos-panel-header{padding:1rem 1.25rem;background:#ffffff;border-bottom:1px solid #e0e0e0;flex-shrink:0;}
    .pos-filter-tabs{display:flex;gap:0.375rem;margin-bottom:0.75rem;padding-left:0;}
    .pos-filter-tab{padding:0.375rem 0.75rem;border-radius:6px;font-size:0.6875rem;font-weight:600;cursor:pointer;border:1px solid #e0e0e0;background:#ffffff;color:#555555;transition:all 0.15s ease;font-family:var(--font-sans);letter-spacing:0.02em;white-space:nowrap;}
    .pos-filter-tab:hover{background:#f5f5f5;color:#000080;border-color:#cbd5e1;}
    .pos-filter-tab.active{background:#000080;color:#ffffff;border-color:#000080;}
    .pos-search-box{position:relative;display:flex;align-items:center;}
    .pos-search{width:100%;border:1.5px solid #e0e0e0;border-radius:8px;padding:0.5rem 0.75rem 0.5rem 2.25rem;font-size:0.8125rem;font-family:var(--font-sans);color:#000080;background:#f5f5f5;outline:none;transition:all 0.2s ease;}
    .pos-search::placeholder{color:#555555;}
    .pos-search:focus{border-color:#000080;background:#ffffff;box-shadow:0 0 0 3px rgba(0,0,128,0.15);}
    .pos-search-icon{position:absolute;left:0.75rem;top:50%;transform:translateY(-50%);color:#555555;pointer-events:none;width:14px;height:14px;}
    .pos-search-spinner{position:absolute;right:0.625rem;top:50%;transform:translateY(-50%);color:#000080;display:flex;align-items:center;}
    .pos-items-body{padding:1rem 1.25rem;flex:1;overflow-y:auto;background:#f5f5f5;}
    .pos-results-bar{display:flex;align-items:center;justify-content:space-between;margin-bottom:0.75rem;}
    .pos-results-title{font-size:0.6875rem;font-weight:700;color:#555555;text-transform:uppercase;letter-spacing:0.08em;}
    .pos-items-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:0.875rem;}
    .pos-item{border:1.5px solid #e0e0e0;border-radius:12px;padding:0;cursor:pointer;background:#ffffff;transition:all 0.2s ease;text-align:start;display:flex;flex-direction:column;box-shadow:0 1px 3px rgba(0,0,0,0.04);overflow:hidden;height:100%;}
    .pos-item:hover{transform:translateY(-3px);box-shadow:0 8px 25px rgba(0,0,128,0.2),0 2px 8px rgba(0,0,0,0.06);border-color:#000080;}
    .pos-item:active{transform:translateY(-1px);box-shadow:0 4px 12px rgba(0,0,128,0.12);}
    .pos-item-img{width:100%;height:110px;overflow:hidden;background:linear-gradient(135deg,#f5f5f5,#ffffff);display:flex;align-items:center;justify-content:center;}
    .pos-item-img img{width:100%;height:100%;object-fit:cover;}
    .pos-item-img-placeholder{width:100%;height:110px;background:linear-gradient(135deg,#f5f5f5,#ffffff);display:flex;align-items:center;justify-content:center;color:#c0c0c0;}
    .pos-item-body{padding:0.75rem 0.875rem 0.875rem;display:flex;flex-direction:column;gap:0.375rem;flex:1;}
    .pos-item-name{font-weight:700;font-size:0.8125rem;color:#000080;line-height:1.3;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
    .pos-item-meta{font-size:0.7rem;color:#555555;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
    .pos-item-footer{display:flex;justify-content:space-between;align-items:flex-end;margin-top:auto;padding-top:0.5rem;border-top:1px solid #e0e0e0;}
    .pos-item-price{font-weight:800;font-size:1rem;color:#000080;line-height:1;}
    .pos-item-stock{font-size:0.65rem;line-height:1;}
    .pos-stock-available{color:#555555;}
    .pos-stock-low{color:#f59e0b;font-weight:600;}
    .pos-stock-out{color:#dc2626;font-weight:700;}
    .pos-empty{display:flex;flex-direction:column;align-items:center;justify-content:center;padding:4rem 2rem;color:#555555;text-align:center;}
    .pos-empty-icon{font-size:3rem;margin-bottom:1rem;opacity:0.5;}
    .pos-empty-text{font-size:1rem;line-height:1.6;font-weight:500;}
    .pos-empty-hint{font-size:0.875rem;color:#555555;margin-top:0.5rem;}
    .pos-cart-panel{width:100%;display:flex;flex-direction:column;overflow:hidden;border-radius:16px;background:#ffffff;box-shadow:0 1px 3px rgba(0,0,0,0.08),0 1px 2px rgba(0,0,0,0.06);border:1px solid #e0e0e0;height:fit-content;}
    @media(min-width:1024px){.pos-cart-panel{width:360px;flex-shrink:0;height:calc(100vh - 120px);overflow-y:auto;position:sticky;top:0;}}
    .pos-cart-header{padding:1.25rem 1.5rem;border-bottom:1px solid #e0e0e0;display:flex;align-items:center;justify-content:space-between;background:linear-gradient(135deg,#000060 0%,#000080 100%);border-radius:16px 16px 0 0;}
    .pos-cart-title{font-weight:700;font-size:1rem;color:#ffffff;display:flex;align-items:center;gap:0.625rem;}
    .pos-cart-count{display:inline-flex;align-items:center;justify-content:center;background:#ffffff;color:#000080;font-size:0.75rem;font-weight:700;border-radius:9999px;min-width:1.5rem;height:1.5rem;padding:0 8px;box-shadow:0 2px 4px rgba(0,0,0,0.2);}
    .pos-customer-section{padding:1rem 1.5rem;border-bottom:1px solid #e0e0e0;background:#ffffff;}
    .pos-customer-input{width:100%;border:1.5px solid #e0e0e0;border-radius:8px;padding:0.625rem 0.75rem;font-size:0.875rem;font-family:var(--font-sans);color:#000080;background:#f5f5f5;outline:none;transition:all 0.2s ease;}
    .pos-customer-input:focus{border-color:#000080;background:#ffffff;box-shadow:0 0 0 3px rgba(0,0,128,0.15);}
    .pos-customer-input::placeholder{color:#555555;}
    .pos-customer-label{font-size:0.75rem;font-weight:600;color:#555555;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.375rem;display:block;}
    .pos-cart-body{overflow-y:auto;max-height:280px;background:#f5f5f5;}
    .pos-cart-body::-webkit-scrollbar{width:4px;}
    .pos-cart-body::-webkit-scrollbar-thumb{background:#cbd5e1;border-radius:4px;}
    .pos-cart-body::-webkit-scrollbar-track{background:transparent;}
    .pos-cart-row{display:flex;align-items:center;gap:0.75rem;padding:0.875rem 1.5rem;border-bottom:1px solid #e0e0e0;transition:background 0.15s ease;background:#ffffff;}
    .pos-cart-row:hover{background:#f5f5f5;}
    .pos-cart-row:last-child{border-bottom:none;}
    .pos-cart-row-info{flex:1;min-width:0;}
    .pos-cart-row-name{font-size:0.8125rem;font-weight:600;color:#000080;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
    .pos-cart-row-sub{font-size:0.8125rem;color:#555555;margin-top:2px;}
    .pos-stepper{display:flex;align-items:center;border:2px solid #e0e0e0;border-radius:8px;overflow:hidden;flex-shrink:0;background:#ffffff;}
    .pos-stepper-btn{width:2rem;height:2rem;display:flex;align-items:center;justify-content:center;background:#f5f5f5;border:none;cursor:pointer;font-size:1rem;font-weight:600;color:#555555;transition:all 0.15s ease;user-select:none;line-height:1;}
    .pos-stepper-btn:hover{background:#e0e0e0;color:#000080;}
    .pos-stepper-val{width:2.25rem;text-align:center;font-size:0.9375rem;font-weight:700;color:#000080;border-inline:2px solid #e0e0e0;padding:4px 0;}
    .pos-cart-row-total{font-weight:700;font-size:0.9375rem;color:#000080;min-width:4rem;text-align:end;flex-shrink:0;}
    .pos-remove-btn{width:1.75rem;height:1.75rem;border-radius:8px;display:flex;align-items:center;justify-content:center;border:none;background:transparent;cursor:pointer;color:#555555;font-size:1.125rem;transition:all 0.15s ease;flex-shrink:0;line-height:1;}
    .pos-remove-btn:hover{background:#fee2e2;color:#dc2626;}
    .pos-summary-section{padding:1rem 1.5rem;border-top:1px solid #e0e0e0;background:linear-gradient(135deg,#000060 0%,#000080 100%);}
    .pos-summary-row{display:flex;justify-content:space-between;font-size:0.875rem;color:#ffffff;padding:4px 0;}
    .pos-summary-total{display:flex;justify-content:space-between;padding-top:0.75rem;margin-top:0.625rem;border-top:2px solid rgba(255,255,255,0.3);font-size:1.125rem;font-weight:700;color:#ffffff;}
    .pos-checkout-section{padding:1rem 1.5rem 1.5rem;border-top:1px solid #e0e0e0;background:#ffffff;border-radius:0 0 16px 16px;}
    .pos-checkout{width:100%;border:none;border-radius:12px;padding:1rem;font-size:1rem;font-weight:700;font-family:var(--font-sans);cursor:pointer;transition:all 0.2s ease;background:linear-gradient(135deg,#000080 0%,#000060 100%);color:#ffffff;box-shadow:0 4px 14px rgba(0,0,128,0.4);}
    .pos-checkout:hover:not(:disabled){transform:translateY(-2px);box-shadow:0 6px 20px rgba(0,0,128,0.5);}
    .pos-checkout:active:not(:disabled){transform:translateY(0);}
    .pos-checkout:disabled{opacity:0.5;cursor:not-allowed;transform:none;box-shadow:none;}
    .ui-btn-ghost{background:transparent;border:1px solid #e0e0e0;color:#555555;padding:0.375rem 0.75rem;border-radius:8px;font-size:0.8125rem;font-weight:500;cursor:pointer;transition:all 0.15s ease;}
    .ui-btn-ghost:hover{background:#f5f5f5;color:#000080;border-color:#c0c0c0;}
    @keyframes spin{from{transform:rotate(0deg);}to{transform:rotate(360deg);}}
    .animate-spin{animation:spin 1s linear infinite;}
</style>
@endsection

@push('scripts')
<script>
function apiFetch(url, options = {}) {
    const token = document.querySelector('meta[name="csrf-token"]').content;
    const headers = options.headers || {};
    headers['X-CSRF-TOKEN'] = token;
    headers['Accept'] = 'application/json';
    if (options.body && typeof options.body === 'string') {
        headers['Content-Type'] = 'application/json';
    }
    return fetch(url, { ...options, headers, credentials: 'same-origin' });
}
(function() {
    const symbol = @json($settings['primary_symbol']);
    const taxRate = {{ $settings['tax_rate'] }};
    let cart = [];
    let allProducts = @json($products);
    let currentFilter = 'all';
    let searchTimeout = null;

    const searchInput = document.getElementById('posSearch');
    const spinner = document.getElementById('searchSpinner');
    const grid = document.getElementById('productsGrid');
    const emptyState = document.getElementById('emptyState');
    const resultCount = document.getElementById('resultCount');
    const cartBody = document.getElementById('cartBody');
    const cartEmpty = document.getElementById('cartEmpty');
    const cartCount = document.getElementById('cartCount');
    const subtotalDisplay = document.getElementById('subtotalDisplay');
    const taxDisplay = document.getElementById('taxDisplay');
    const totalDisplay = document.getElementById('totalDisplay');
    const changeDisplay = document.getElementById('changeDisplay');
    const amountPaid = document.getElementById('amountPaid');
    const cardAmount = document.getElementById('cardAmount');
    const paymentMethod = document.getElementById('paymentMethod');
    const checkoutBtn = document.getElementById('checkoutBtn');
    const clearCartBtn = document.getElementById('clearCartBtn');

    renderProducts();

    document.querySelectorAll('.pos-filter-tab').forEach(tab => {
        tab.addEventListener('click', () => {
            document.querySelectorAll('.pos-filter-tab').forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
            currentFilter = tab.dataset.filter;
            filterProducts();
        });
    });

    searchInput.addEventListener('input', () => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(filterProducts, 300);
    });

    paymentMethod.addEventListener('change', () => {
        document.getElementById('cashSection').style.display = paymentMethod.value === 'card' ? 'none' : 'block';
        document.getElementById('cardSection').style.display = paymentMethod.value === 'card' ? 'block' : 'none';
        updateTotals();
    });

    amountPaid.addEventListener('input', updateTotals);
    cardAmount.addEventListener('input', updateTotals);
    clearCartBtn.addEventListener('click', () => { cart = []; renderCart(); });

    checkoutBtn.addEventListener('click', checkout);

    function filterProducts() {
        const search = searchInput.value.toLowerCase().trim();
        let filtered = allProducts;
        if (search) {
            filtered = allProducts.filter(p => {
                if (currentFilter === 'name') return (p.name || '').toLowerCase().includes(search);
                if (currentFilter === 'code') return (p.code || '').toLowerCase().includes(search);
                if (currentFilter === 'barcode') return (p.barcode || '').toLowerCase().includes(search);
                return (p.name || '').toLowerCase().includes(search) || (p.code || '').toLowerCase().includes(search) || (p.barcode || '').toLowerCase().includes(search);
            });
        }
        renderProducts(filtered);
    }

    function renderProducts(products) {
        products = products || allProducts;
        resultCount.textContent = products.length + ' items';
        if (products.length === 0) {
            grid.innerHTML = '';
            emptyState.style.display = 'flex';
            return;
        }
        emptyState.style.display = 'none';
        grid.innerHTML = products.map(p => {
            const stockClass = p.quantity <= 0 ? 'pos-stock-out' : (p.quantity <= 5 ? 'pos-stock-low' : 'pos-stock-available');
            const stockText = p.quantity <= 0 ? 'Out of stock' : p.quantity + ' in stock';
            return '<div class="pos-item" onclick="addToCart(' + p.id + ')"' + (p.quantity <= 0 ? ' style="opacity:0.5;pointer-events:none;"' : '') + '>' +
                (p.image_url
                    ? '<div class="pos-item-img"><img src="' + p.image_url + '" alt="' + p.name + '"></div>'
                    : '<div class="pos-item-img-placeholder"><svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg></div>') +
                '<div class="pos-item-body">' +
                    '<div class="pos-item-name">' + p.name + '</div>' +
                    '<div class="pos-item-meta">' + p.code + (p.barcode ? ' | ' + p.barcode : '') + '</div>' +
                    '<div class="pos-item-footer">' +
                        '<div class="pos-item-price">' + symbol + ' ' + (p.pre_tax_price * (1 + taxRate / 100)).toFixed(2) + '</div>' +
                        '<div class="pos-item-stock ' + stockClass + '">' + stockText + '</div>' +
                    '</div>' +
                '</div></div>';
        }).join('');
    }

    window.addToCart = function(productId) {
        const product = allProducts.find(p => p.id === productId);
        if (!product || product.quantity <= 0) return;
        const existing = cart.find(c => c.id === productId);
        if (existing) {
            if (existing.quantity < product.quantity) existing.quantity++;
        } else {
            cart.push({ ...product, quantity: 1 });
        }
        renderCart();
    };

    function renderCart() {
        if (cart.length === 0) {
            cartBody.innerHTML = '<div class="pos-empty"><div class="pos-empty-icon">🛒</div><div class="pos-empty-text" style="font-size:0.875rem;">Cart is empty</div><div class="pos-empty-hint">Click on products to add them</div></div>';
            cartCount.textContent = '0';
            checkoutBtn.disabled = true;
            updateTotals();
            return;
        }
        checkoutBtn.disabled = false;
        cartCount.textContent = cart.reduce((s, c) => s + c.quantity, 0);
        cartBody.innerHTML = cart.map((item, i) =>
            '<div class="pos-cart-row">' +
                '<div class="pos-cart-row-info">' +
                    '<div class="pos-cart-row-name">' + item.name + '</div>' +
                    '<div class="pos-cart-row-sub">' + symbol + ' ' + (item.pre_tax_price * (1 + taxRate / 100)).toFixed(2) + ' each</div>' +
                '</div>' +
                '<div class="pos-stepper">' +
                    '<button class="pos-stepper-btn" onclick="updateQty(' + i + ', -1)">-</button>' +
                    '<div class="pos-stepper-val">' + item.quantity + '</div>' +
                    '<button class="pos-stepper-btn" onclick="updateQty(' + i + ', 1)">+</button>' +
                '</div>' +
                '<div class="pos-cart-row-total">' + symbol + ' ' + ((item.pre_tax_price * (1 + taxRate / 100)) * item.quantity).toFixed(2) + '</div>' +
                '<button class="pos-remove-btn" onclick="removeItem(' + i + ')">×</button>' +
            '</div>'
        ).join('');
        updateTotals();
    }

    window.updateQty = function(index, delta) {
        const item = cart[index];
        const newQty = item.quantity + delta;
        if (newQty <= 0) {
            cart.splice(index, 1);
        } else {
            item.quantity = newQty;
        }
        renderCart();
    };

    window.removeItem = function(index) {
        cart.splice(index, 1);
        renderCart();
    };

    function updateTotals() {
        const subtotal = cart.reduce((sum, item) => sum + (item.pre_tax_price * item.quantity), 0);
        const tax = subtotal * taxRate / 100;
        const total = subtotal + tax;
        const paid = parseFloat(amountPaid.value) || 0;
        const card = paymentMethod.value === 'card' ? (parseFloat(cardAmount.value) || 0) : 0;
        const totalPaid = paymentMethod.value === 'card' ? card : paid;
        const change = Math.max(0, totalPaid - total);

        subtotalDisplay.textContent = symbol + ' ' + subtotal.toFixed(2);
        taxDisplay.textContent = symbol + ' ' + tax.toFixed(2);
        totalDisplay.textContent = symbol + ' ' + total.toFixed(2);
        changeDisplay.textContent = symbol + ' ' + change.toFixed(2);
    }

    async function checkout() {
        if (cart.length === 0) return;
        checkoutBtn.disabled = true;
        checkoutBtn.textContent = 'Processing...';

        const payload = {
            customer_name: document.getElementById('customerName').value || 'Walk-in Customer',
            payment_method: paymentMethod.value,
            amount_paid: parseFloat(amountPaid.value) || 0,
            card_amount: parseFloat(cardAmount.value) || 0,
            cart: cart.map(item => ({ item_id: item.id, quantity: item.quantity })),
        };

        try {
            const res = await apiFetch('{{ url("/api/pos/invoice") }}', {
                method: 'POST',
                body: JSON.stringify(payload),
            });
            const data = await res.json();
            if (data.success) {
                alert('Invoice ' + data.invoice.invoice_number + ' created!');
                cart = [];
                amountPaid.value = '';
                cardAmount.value = '';
                renderCart();
                location.reload();
            } else {
                alert(data.error || 'Failed to create invoice');
            }
        } catch (e) {
            alert('Error creating invoice');
        } finally {
            checkoutBtn.disabled = false;
            checkoutBtn.textContent = 'Checkout';
        }
    }
})();
</script>
@endpush
