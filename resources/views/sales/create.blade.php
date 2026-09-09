@extends('layouts.app')
@section('page-title', 'Add Sale')
@section('content')
<style>
    .sale-wrap { max-width: 1000px; margin: 0 auto; display: flex; flex-direction: column; gap: 1rem; }
    .sale-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.5rem; }
    .sale-header h1 { font-size: 1.5rem; font-weight: 700; color: #000080; margin: 0; }
    .sale-header p { font-size: 0.8125rem; color: #888888; margin: 0.25rem 0 0; }
    .card { background: #ffffff; border: 1px solid #e0e0e0; border-radius: 16px; overflow: hidden; }
    .card-header { display: flex; align-items: center; justify-content: space-between; padding: 0.875rem 1.25rem; border-bottom: 1px solid #e0e0e0; }
    .card-header h3 { font-size: 0.875rem; font-weight: 700; color: #000080; margin: 0; }
    .card-body { padding: 1.25rem; }
    .form-label { display: block; font-size: 0.75rem; font-weight: 600; color: #888888; margin-bottom: 0.3rem; }
    .form-input { width: 100%; padding: 0.5rem 0.75rem; border: 1.5px solid #e0e0e0; border-radius: 8px; font-size: 0.8125rem; color: #000080; background: #ffffff; outline: none; transition: border-color 0.15s; }
    .form-input:focus { border-color: #000080; box-shadow: 0 0 0 3px rgba(0,0,128,0.08); }
    .form-select { width: 100%; padding: 0.5rem 0.75rem; border: 1.5px solid #e0e0e0; border-radius: 8px; font-size: 0.8125rem; color: #000080; background: #ffffff; outline: none; }
    .customer-search-wrap { position: relative; }
    .customer-dropdown { display: none; position: absolute; top: 100%; left: 0; right: 0; background: #ffffff; border: 1px solid #e0e0e0; border-radius: 10px; box-shadow: 0 10px 40px rgba(0,0,0,0.12); z-index: 50; max-height: 240px; overflow-y: auto; }
    .customer-dropdown-item { padding: 0.625rem 0.875rem; cursor: pointer; font-size: 0.8125rem; border-bottom: 1px solid #f5f5f5; transition: background 0.1s; }
    .customer-dropdown-item:last-child { border-bottom: none; }
    .customer-dropdown-item:hover { background: #f5f5f5; }
    .customer-dropdown-name { font-weight: 600; color: #000080; }
    .customer-dropdown-info { font-size: 0.75rem; color: #555555; }
    .line-item-row { display: grid; grid-template-columns: 2fr 0.7fr 1fr 0.7fr 0.7fr auto; gap: 0.5rem; align-items: center; padding: 0.75rem; background: #f5f5f5; border-radius: 8px; border: 1px solid #e0e0e0; }
    .line-item-row .field { display: flex; flex-direction: column; }
    .line-item-row .field label { font-size: 0.6875rem; font-weight: 600; color: #888888; margin-bottom: 0.25rem; text-transform: uppercase; letter-spacing: 0.03em; }
    .line-item-row .field input { padding: 0.5rem 0.625rem; border: 1px solid #e0e0e0; border-radius: 6px; font-size: 0.8125rem; color: #000080; background: #ffffff; outline: none; }
    .line-item-row .field input:focus { border-color: #000080; }
    .remove-btn { padding: 0.5rem 0.75rem; background: #fef2f2; color: #dc2626; border: none; border-radius: 6px; font-size: 0.8125rem; font-weight: 600; cursor: pointer; transition: background 0.15s; }
    .remove-btn:hover { background: #fee2e2; }
    .add-item-btn { display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.5rem 1rem; background: #ecfdf5; color: #000080; border: 1px dashed #000080; border-radius: 8px; font-size: 0.8125rem; font-weight: 600; cursor: pointer; transition: all 0.15s; }
    .add-item-btn:hover { background: #d1fae5; }
    .payment-summary-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    .summary-box { display: flex; flex-direction: column; gap: 0.5rem; }
    .summary-row { display: flex; justify-content: space-between; align-items: center; font-size: 0.8125rem; }
    .summary-row .label { color: #888888; }
    .summary-row .value { font-weight: 500; color: #000080; }
    .summary-total { display: flex; justify-content: space-between; align-items: center; padding-top: 0.75rem; border-top: 2px solid #e0e0e0; font-weight: 800; font-size: 1.125rem; color: #000080; }
    .action-bar { display: flex; justify-content: flex-end; gap: 0.75rem; }
    .btn-cancel { padding: 0.625rem 1.5rem; background: transparent; color: #888888; border: 1px solid #e0e0e0; border-radius: 10px; font-size: 0.875rem; font-weight: 500; cursor: pointer; text-decoration: none; transition: all 0.15s; }
    .btn-cancel:hover { background: #f5f5f5; color: #000080; }
    .btn-create { padding: 0.625rem 2rem; background: #000080; color: #ffffff; border: none; border-radius: 10px; font-size: 0.9375rem; font-weight: 700; cursor: pointer; transition: background 0.15s; }
    .btn-create:hover { background: #000080; }
    .empty-lines { padding: 2rem; text-align: center; color: #555555; font-size: 0.8125rem; }
    @media (max-width: 768px) { .payment-summary-grid { grid-template-columns: 1fr; } .line-item-row { grid-template-columns: 1fr; } }
</style>

<div class="sale-wrap">
    <div class="sale-header">
        <div>
            <h1>Add Sale</h1>
            <p>Create a new invoice manually</p>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>Customer Information</h3>
        </div>
        <div class="card-body">
            <div class="customer-search-wrap">
                <label class="form-label">Customer Name *</label>
                <input type="text" class="form-input" id="customerInput" placeholder="Type to search customers..." autocomplete="off">
                <div class="customer-dropdown" id="customerDropdown"></div>
                <input type="hidden" id="customerId">
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>Line Items</h3>
            <button class="add-item-btn" onclick="addLine()">+ Add Item</button>
        </div>
        <div class="card-body">
            <div id="lineItems" style="display: flex; flex-direction: column; gap: 0.75rem;"></div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="payment-summary-grid">
                <div>
                    <h3 style="font-size:0.875rem;font-weight:700;color:#000080;margin:0 0 0.75rem;">Payment</h3>
                    <div style="display:flex;flex-direction:column;gap:0.75rem;">
                        <div>
                            <label class="form-label">Payment Method</label>
                            <select class="form-select" id="paymentMethod">
                                <option value="cash">Cash</option>
                                <option value="card">Card</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Payment Status</label>
                            <select class="form-select" id="statusSelect">
                                <option value="paid">Paid</option>
                                <option value="partial">Partial</option>
                                <option value="due">Due</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Amount Paid</label>
                            <input type="number" class="form-input" id="amountPaid" step="0.01" min="0" placeholder="0.00" inputmode="decimal">
                        </div>
                        <div>
                            <label class="form-label">Due Date</label>
                            <input type="date" class="form-input" id="dueDate">
                        </div>
                    </div>
                </div>
                <div>
                    <h3 style="font-size:0.875rem;font-weight:700;color:#000080;margin:0 0 0.75rem;">Summary</h3>
                    <div class="summary-box">
                        <div class="summary-row">
                            <span class="label">Subtotal</span>
                            <span class="value" id="subtotalDisplay">{{ $data['currency_symbol'] ?? '$' }}0.00</span>
                        </div>
                        <div class="summary-row">
                            <span class="label">Tax</span>
                            <span class="value" id="taxDisplay">{{ $data['currency_symbol'] ?? '$' }}0.00</span>
                        </div>
                        <div class="summary-row">
                            <span class="label" style="color:#dc2626;">Discount</span>
                            <input type="number" class="form-input" id="discountInput" step="0.01" min="0" value="0" style="width: 120px; text-align: right; padding: 0.375rem 0.625rem; border-color: #fecaca;" inputmode="decimal">
                        </div>
                        <div class="summary-total">
                            <span>Total</span>
                            <span id="totalDisplay">{{ $data['currency_symbol'] ?? '$' }}0.00</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="action-bar">
        <a href="{{ url('/sales') }}" class="btn-cancel">Cancel</a>
        <button class="btn-create" onclick="submitInvoice()">Create Invoice</button>
    </div>
</div>

@push('scripts')
<script>
(function() {
    const currencySymbol = '{{ $data["currency_symbol"] ?? "$" }}';
    let lines = [];
    let customerSearchTimeout;

    document.getElementById('customerInput').addEventListener('input', function() {
        clearTimeout(customerSearchTimeout);
        const val = this.value.trim();
        if (val.length < 2) { hideDropdown(); return; }
        customerSearchTimeout = setTimeout(() => searchCustomers(val), 300);
    });

    document.getElementById('customerInput').addEventListener('blur', () => setTimeout(hideDropdown, 200));

    async function searchCustomers(q) {
        try {
            const res = await apiFetch(`{{ url('/api/customers') }}?q=${encodeURIComponent(q)}&per_page=10`);
            const data = await res.json();
            const dd = document.getElementById('customerDropdown');
            if (!data.data || data.data.length === 0) {
                dd.innerHTML = '<div style="padding:0.75rem;color:#9ca3af;font-size:0.8125rem;">No customers found</div>';
                dd.style.display = 'block';
                return;
            }
            dd.innerHTML = data.data.map(c => `
                <div class="customer-dropdown-item" onmousedown="selectCustomer(${c.id}, '${c.name.replace(/'/g, "\\'")}')">
                    <div class="customer-dropdown-name">${c.name}</div>
                    <div class="customer-dropdown-info">${c.email || ''} ${c.phone || ''}</div>
                </div>
            `).join('');
            dd.style.display = 'block';
        } catch (e) { console.error(e); }
    }

    window.selectCustomer = function(id, name) {
        document.getElementById('customerId').value = id;
        document.getElementById('customerInput').value = name;
        hideDropdown();
    };

    function hideDropdown() { document.getElementById('customerDropdown').style.display = 'none'; }

    window.addLine = function() {
        lines.push({ name: '', qty: 1, price: 0, tax: 0, discount: 0 });
        renderLines();
    };

    window.removeLine = function(i) {
        lines.splice(i, 1);
        renderLines();
        updateTotals();
    };

    window.updateLine = function(i, field, val) {
        if (['qty', 'price', 'tax', 'discount'].includes(field)) {
            lines[i][field] = parseFloat(val) || 0;
        } else {
            lines[i][field] = val;
        }
        updateTotals();
    };

    function renderLines() {
        const container = document.getElementById('lineItems');
        if (lines.length === 0) {
            container.innerHTML = '<div class="empty-lines">No items added. Click "+ Add Item" to start.</div>';
            return;
        }
        container.innerHTML = lines.map((line, i) => `
            <div class="line-item-row">
                <div class="field field-name">
                    <label>Item Name</label>
                    <input type="text" placeholder="Item name" value="${line.name}" oninput="updateLine(${i},'name',this.value)">
                </div>
                <div class="field field-qty">
                    <label>Qty</label>
                    <input type="number" placeholder="1" value="${line.qty}" min="1" inputmode="numeric" oninput="updateLine(${i},'qty',this.value)">
                </div>
                <div class="field field-price">
                    <label>Unit Price</label>
                    <input type="number" placeholder="0.00" value="${line.price}" step="0.01" min="0" inputmode="decimal" oninput="updateLine(${i},'price',this.value)">
                </div>
                <div class="field field-tax">
                    <label>Tax %</label>
                    <input type="number" placeholder="0" value="${line.tax}" step="0.01" min="0" inputmode="decimal" oninput="updateLine(${i},'tax',this.value)">
                </div>
                <div class="field field-discount">
                    <label>Discount</label>
                    <input type="number" placeholder="0" value="${line.discount}" step="0.01" min="0" inputmode="decimal" oninput="updateLine(${i},'discount',this.value)">
                </div>
                <button class="remove-btn" onclick="removeLine(${i})">×</button>
            </div>
        `).join('');
    }

    document.getElementById('discountInput').addEventListener('input', updateTotals);

    function updateTotals() {
        const subtotal = lines.reduce((s, l) => s + (l.price * l.qty), 0);
        const tax = lines.reduce((s, l) => s + ((l.price * l.qty * l.tax) / 100), 0);
        const lineDiscount = lines.reduce((s, l) => s + l.discount, 0);
        const discount = parseFloat(document.getElementById('discountInput').value) || 0;
        const total = subtotal + tax - lineDiscount - discount;

        document.getElementById('subtotalDisplay').textContent = `${currencySymbol}${subtotal.toFixed(2)}`;
        document.getElementById('taxDisplay').textContent = `${currencySymbol}${tax.toFixed(2)}`;
        document.getElementById('totalDisplay').textContent = `${currencySymbol}${total.toFixed(2)}`;
    }

    window.submitInvoice = async function() {
        const customerName = document.getElementById('customerInput').value.trim();
        if (!customerName) { alert('Please enter a customer name'); return; }
        if (lines.length === 0) { alert('Please add at least one item'); return; }

        const validLines = lines.filter(l => l.name && l.qty > 0 && l.price >= 0);
        if (validLines.length === 0) { alert('Please fill in item details'); return; }

        const subtotal = validLines.reduce((s, l) => s + (l.price * l.qty), 0);
        const tax = validLines.reduce((s, l) => s + ((l.price * l.qty * l.tax) / 100), 0);
        const lineDiscount = validLines.reduce((s, l) => s + l.discount, 0);
        const discount = parseFloat(document.getElementById('discountInput').value) || 0;
        const total = subtotal + tax - lineDiscount - discount;
        const paid = parseFloat(document.getElementById('amountPaid').value) || total;

        const payload = {
            customer_name: customerName,
            payment_method: document.getElementById('paymentMethod').value,
            payment_status: document.getElementById('statusSelect').value,
            amount_paid: paid,
            due_date: document.getElementById('dueDate').value || null,
            cart: validLines.map(l => ({
                item_id: null,
                item_name: l.name,
                item_code: '',
                quantity: l.qty,
                unit_price: l.price,
                tax_amount: ((l.price * l.qty * l.tax) / 100),
                discount: l.discount,
            })),
        };

        try {
            const res = await apiFetch('{{ url("/api/pos/invoice") }}', {
                method: 'POST',
                body: JSON.stringify(payload),
            });
            const data = await res.json();
            if (data.success) {
                alert(`Invoice ${data.invoice.invoice_number} created!`);
                window.location.href = '{{ url("/invoices") }}/' + data.invoice.id;
            } else {
                alert(data.error || 'Failed to create invoice');
            }
        } catch (e) { alert('Error creating invoice'); }
    };

    addLine();
})();
</script>
@endpush
@endsection
