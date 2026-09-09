@extends('layouts.app')
@section('page-title', 'Quotes')

@section('content')
<div style="padding: 1.5rem; max-width: 1400px; margin: 0 auto">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem">
        <div>
            <h1 style="font-size: 1.5rem; font-weight: 700; color: #000080; margin: 0">Quotes</h1>
            <p style="font-size: 0.875rem; color: #555555; margin: 0.25rem 0 0">Create and manage quotes for customers</p>
        </div>
        <button onclick="openCreateModal()" style="background: linear-gradient(135deg, #000080, #000060); color: #fff; border: none; padding: 0.5rem 1.25rem; border-radius: 8px; font-size: 0.8125rem; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            + New Quote
        </button>
    </div>

    <div style="display: flex; gap: 1rem; margin-bottom: 1rem; align-items: center; flex-wrap: wrap">
        <input type="text" id="searchInput" placeholder="Search quotes..." style="border: 1.5px solid #e0e0e0; border-radius: 8px; padding: 0.5rem 0.75rem; font-size: 0.8125rem; color: #000080; background: #fff; outline: none; max-width: 360px; width: 100%">
        <select id="statusFilter" style="border: 1.5px solid #e0e0e0; border-radius: 8px; padding: 0.5rem 0.75rem; font-size: 0.8125rem; color: #000080; background: #fff; outline: none">
            <option value="">All Status</option>
            <option value="draft">Draft</option>
            <option value="sent">Sent</option>
            <option value="accepted">Accepted</option>
            <option value="rejected">Rejected</option>
            <option value="expired">Expired</option>
        </select>
    </div>

    <div style="background: #fff; border: 1px solid #e0e0e0; border-radius: 12px; overflow: auto">
        <table style="width: 100%; border-collapse: collapse">
            <thead>
                <tr>
                    <th style="padding: 0.625rem 1rem; font-size: 0.6875rem; font-weight: 700; color: #888888; text-transform: uppercase; letter-spacing: 0.06em; border-bottom: 1px solid #e0e0e0; text-align: left">Quote #</th>
                    <th style="padding: 0.625rem 1rem; font-size: 0.6875rem; font-weight: 700; color: #888888; text-transform: uppercase; letter-spacing: 0.06em; border-bottom: 1px solid #e0e0e0; text-align: left">Customer</th>
                    <th style="padding: 0.625rem 1rem; font-size: 0.6875rem; font-weight: 700; color: #888888; text-transform: uppercase; letter-spacing: 0.06em; border-bottom: 1px solid #e0e0e0; text-align: left">Date</th>
                    <th style="padding: 0.625rem 1rem; font-size: 0.6875rem; font-weight: 700; color: #888888; text-transform: uppercase; letter-spacing: 0.06em; border-bottom: 1px solid #e0e0e0; text-align: left">Valid Until</th>
                    <th style="padding: 0.625rem 1rem; font-size: 0.6875rem; font-weight: 700; color: #888888; text-transform: uppercase; letter-spacing: 0.06em; border-bottom: 1px solid #e0e0e0; text-align: right">Total</th>
                    <th style="padding: 0.625rem 1rem; font-size: 0.6875rem; font-weight: 700; color: #888888; text-transform: uppercase; letter-spacing: 0.06em; border-bottom: 1px solid #e0e0e0; text-align: left">Status</th>
                    <th style="padding: 0.625rem 1rem; font-size: 0.6875rem; font-weight: 700; color: #888888; text-transform: uppercase; letter-spacing: 0.06em; border-bottom: 1px solid #e0e0e0; text-align: right">Actions</th>
                </tr>
            </thead>
            <tbody id="quotesTableBody"></tbody>
        </table>
    </div>

    <div id="pagination" style="display: flex; align-items: center; justify-content: space-between; margin-top: 1rem; font-size: 0.8125rem; color: #555555"></div>
</div>

<div id="createModal" style="position: fixed; inset: 0; background: rgba(15,23,42,0.4); z-index: 100; display: none; align-items: center; justify-content: center; padding: 1rem">
    <div style="background: #fff; border-radius: 12px; padding: 1.5rem; max-height: 90vh; overflow-y: auto; width: 100%; max-width: 650px">
        <h3 style="font-size: 1.125rem; font-weight: 700; color: #000080; margin: 0 0 1.25rem">New Quote</h3>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem">
            <div>
                <label style="display: block; font-size: 0.75rem; font-weight: 600; color: #555555; margin-bottom: 0.3rem">Customer Name *</label>
                <input type="text" id="qCustomerName" required style="width: 100%; border: 1.5px solid #e0e0e0; border-radius: 8px; padding: 0.5rem 0.75rem; font-size: 0.8125rem; color: #000080; background: #fff; outline: none; box-sizing: border-box">
            </div>
            <div>
                <label style="display: block; font-size: 0.75rem; font-weight: 600; color: #555555; margin-bottom: 0.3rem">Contact</label>
                <input type="text" id="qContact" style="width: 100%; border: 1.5px solid #e0e0e0; border-radius: 8px; padding: 0.5rem 0.75rem; font-size: 0.8125rem; color: #000080; background: #fff; outline: none; box-sizing: border-box">
            </div>
        </div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem">
            <div>
                <label style="display: block; font-size: 0.75rem; font-weight: 600; color: #555555; margin-bottom: 0.3rem">Valid Until</label>
                <input type="date" id="qValidUntil" style="width: 100%; border: 1.5px solid #e0e0e0; border-radius: 8px; padding: 0.5rem 0.75rem; font-size: 0.8125rem; color: #000080; background: #fff; outline: none; box-sizing: border-box">
            </div>
            <div>
                <label style="display: block; font-size: 0.75rem; font-weight: 600; color: #555555; margin-bottom: 0.3rem">Notes</label>
                <input type="text" id="qNotes" style="width: 100%; border: 1.5px solid #e0e0e0; border-radius: 8px; padding: 0.5rem 0.75rem; font-size: 0.8125rem; color: #000080; background: #fff; outline: none; box-sizing: border-box">
            </div>
        </div>
        <div style="margin-bottom: 1rem">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.5rem">
                <span style="font-weight: 700; font-size: 0.875rem; color: #000080">Line Items</span>
                <button onclick="addQuoteLine()" style="background: transparent; border: 1px solid #e0e0e0; color: #555555; padding: 0.375rem 0.75rem; border-radius: 8px; font-size: 0.8125rem; font-weight: 500; cursor: pointer">+ Add Line</button>
            </div>
            <div id="quoteLines" style="display: flex; flex-direction: column; gap: 0.5rem"></div>
        </div>
        <div style="text-align: right; font-weight: 700; font-size: 1rem; color: #000080; margin-bottom: 1.25rem">
            Total: JD <span id="quoteTotal">0.00</span>
        </div>
        <div style="display: flex; justify-content: flex-end; gap: 0.75rem; padding-top: 1rem; border-top: 1px solid #f5f5f5">
            <button onclick="closeCreateModal()" style="background: transparent; border: 1px solid #e0e0e0; color: #555555; padding: 0.375rem 0.75rem; border-radius: 8px; font-size: 0.8125rem; font-weight: 500; cursor: pointer">Cancel</button>
            <button onclick="saveQuote()" style="background: linear-gradient(135deg, #000080, #000060); color: #fff; border: none; padding: 0.5rem 1.25rem; border-radius: 8px; font-size: 0.8125rem; font-weight: 600; cursor: pointer">Create Quote</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function() {
    const token = document.querySelector('meta[name="csrf-token"]').content;
    function apiFetch(url, opts = {}) {
        const h = opts.headers || {};
        h['X-CSRF-TOKEN'] = token;
        h['Accept'] = 'application/json';
        if (opts.body && typeof opts.body === 'string') h['Content-Type'] = 'application/json';
        return fetch(url, { ...opts, headers: h, credentials: 'same-origin' });
    }

    let currentPage = 1;
    let quoteLines = [];
    const tbody = document.getElementById('quotesTableBody');
    const pagination = document.getElementById('pagination');
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');

    let debounce;
    searchInput.addEventListener('input', () => { clearTimeout(debounce); debounce = setTimeout(() => { currentPage = 1; loadQuotes(); }, 300); });
    statusFilter.addEventListener('change', () => { currentPage = 1; loadQuotes(); });

    const badgeColors = { draft: '#555555', sent: '#000080', accepted: '#000060', rejected: '#dc2626', expired: '#d97706' };
    const badgeBg = { draft: '#f5f5f5', sent: '#dbeafe', accepted: '#d1fae5', rejected: '#fee2e2', expired: '#fef3c7' };

    window.openCreateModal = function() {
        document.getElementById('qCustomerName').value = '';
        document.getElementById('qContact').value = '';
        document.getElementById('qValidUntil').value = '';
        document.getElementById('qNotes').value = '';
        quoteLines = [{ name: '', code: '', qty: 1, price: 0, tax: 0 }];
        renderQuoteLines();
        document.getElementById('createModal').style.display = 'flex';
    };

    window.closeCreateModal = function() { document.getElementById('createModal').style.display = 'none'; };

    window.addQuoteLine = function() {
        quoteLines.push({ name: '', code: '', qty: 1, price: 0, tax: 0 });
        renderQuoteLines();
    };

    window.removeQuoteLine = function(i) {
        quoteLines.splice(i, 1);
        renderQuoteLines();
    };

    window.updateQuoteLine = function(i, field, val) {
        if (['qty', 'price', 'tax'].includes(field)) {
            quoteLines[i][field] = parseFloat(val) || 0;
        } else {
            quoteLines[i][field] = val;
        }
        updateQuoteTotal();
    };

    function renderQuoteLines() {
        const container = document.getElementById('quoteLines');
        container.innerHTML = quoteLines.map((l, i) => `
            <div style="display: flex; gap: 0.5rem; align-items: flex-end; flex-wrap: wrap">
                <input type="text" placeholder="Item name *" value="${l.name}" oninput="updateQuoteLine(${i},'name',this.value)" style="flex: 2; min-width: 120px; border: 1.5px solid #e0e0e0; border-radius: 8px; padding: 0.5rem 0.75rem; font-size: 0.8125rem; color: #000080; background: #fff; outline: none">
                <input type="text" placeholder="Code" value="${l.code}" oninput="updateQuoteLine(${i},'code',this.value)" style="flex: 1; min-width: 70px; border: 1.5px solid #e0e0e0; border-radius: 8px; padding: 0.5rem 0.75rem; font-size: 0.8125rem; color: #000080; background: #fff; outline: none">
                <input type="number" placeholder="Qty" value="${l.qty}" min="1" inputmode="numeric" oninput="updateQuoteLine(${i},'qty',this.value)" style="flex: 0.5; min-width: 60px; border: 1.5px solid #e0e0e0; border-radius: 8px; padding: 0.5rem 0.75rem; font-size: 0.8125rem; color: #000080; background: #fff; outline: none">
                <input type="number" placeholder="Price" value="${l.price}" step="0.01" min="0" inputmode="decimal" oninput="updateQuoteLine(${i},'price',this.value)" style="flex: 1; min-width: 80px; border: 1.5px solid #e0e0e0; border-radius: 8px; padding: 0.5rem 0.75rem; font-size: 0.8125rem; color: #000080; background: #fff; outline: none">
                <input type="number" placeholder="Tax" value="${l.tax}" step="0.01" min="0" inputmode="decimal" oninput="updateQuoteLine(${i},'tax',this.value)" style="flex: 0.5; min-width: 70px; border: 1.5px solid #e0e0e0; border-radius: 8px; padding: 0.5rem 0.75rem; font-size: 0.8125rem; color: #000080; background: #fff; outline: none">
                <button onclick="removeQuoteLine(${i})" style="background: transparent; border: 1px solid #e0e0e0; color: #dc2626; padding: 0.375rem 0.625rem; border-radius: 8px; font-size: 0.8125rem; font-weight: 500; cursor: pointer; flex-shrink: 0">&times;</button>
            </div>
        `).join('');
        updateQuoteTotal();
    }

    function updateQuoteTotal() {
        const total = quoteLines.reduce((s, l) => s + (l.price * l.qty) + l.tax, 0);
        document.getElementById('quoteTotal').textContent = total.toFixed(2);
    }

    window.saveQuote = async function() {
        const customerName = document.getElementById('qCustomerName').value.trim();
        if (!customerName) { alert('Enter customer name'); return; }
        const valid = quoteLines.filter(l => l.name && l.qty > 0);
        if (valid.length === 0) { alert('Add at least one item'); return; }
        const payload = {
            customer_name: customerName,
            customer_contact: document.getElementById('qContact').value || null,
            valid_until: document.getElementById('qValidUntil').value || null,
            notes: document.getElementById('qNotes').value || null,
            items: valid.map(l => ({ item_name: l.name, item_code: l.code, quantity: l.qty, unit_price: l.price, tax_amount: l.tax })),
        };
        try {
            const res = await apiFetch('{{ url("/api/quotes") }}', { method: 'POST', body: JSON.stringify(payload) });
            const data = await res.json();
            if (data.success) { closeCreateModal(); loadQuotes(); } else { alert(data.error || 'Error'); }
        } catch (e) { alert('Error creating quote'); }
    };

    window.updateQuoteStatus = async function(id, status) {
        try {
            const res = await apiFetch('{{ url("/api/quotes") }}/' + id, { method: 'POST', body: JSON.stringify({ status }) });
            const data = await res.json();
            if (data.success) loadQuotes(); else alert(data.error || 'Error');
        } catch (e) { alert('Error'); }
    };

    window.deleteQuote = async function(id) {
        if (!confirm('Delete this quote?')) return;
        try {
            const res = await apiFetch('{{ url("/api/quotes") }}/' + id, { method: 'DELETE' });
            const data = await res.json();
            if (data.success) loadQuotes();
        } catch (e) { alert('Error'); }
    };

    window.convertQuote = async function(id) {
        if (!confirm('Convert this quote to an invoice?')) return;
        try {
            const res = await apiFetch('{{ url("/api/quotes") }}/' + id + '/convert', { method: 'POST' });
            const data = await res.json();
            if (data.success) { alert('Invoice created!'); window.location.href = '{{ url("/invoices") }}/' + data.invoice.id; }
            else alert(data.error || 'Error');
        } catch (e) { alert('Error'); }
    };

    async function loadQuotes() {
        const params = new URLSearchParams({ q: searchInput.value, status: statusFilter.value, page: currentPage, per_page: 15 });
        try {
            const res = await apiFetch('{{ url("/api/quotes") }}?' + params);
            const data = await res.json();
            renderTable(data.data);
            renderPagination(data);
        } catch (e) { console.error(e); }
    }

    function renderTable(quotes) {
        if (!quotes || quotes.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7" style="padding: 2rem 1rem; text-align: center; color: #888888; font-size: 0.875rem">No quotes found</td></tr>';
            return;
        }
        tbody.innerHTML = quotes.map(q => {
            const actions = [];
            if (q.status === 'draft') actions.push(`<button onclick="updateQuoteStatus(${q.id},'sent')" style="background: transparent; border: 1px solid #e0e0e0; color: #555555; padding: 0.375rem 0.75rem; border-radius: 8px; font-size: 0.8125rem; font-weight: 500; cursor: pointer">Send</button>`);
            if (q.status === 'sent') {
                actions.push(`<button onclick="updateQuoteStatus(${q.id},'accepted')" style="background: transparent; border: 1px solid #e0e0e0; color: #555555; padding: 0.375rem 0.75rem; border-radius: 8px; font-size: 0.8125rem; font-weight: 500; cursor: pointer">Accept</button>`);
                actions.push(`<button onclick="updateQuoteStatus(${q.id},'rejected')" style="background: transparent; border: 1px solid #e0e0e0; color: #555555; padding: 0.375rem 0.75rem; border-radius: 8px; font-size: 0.8125rem; font-weight: 500; cursor: pointer">Reject</button>`);
            }
            if (q.status === 'accepted') actions.push(`<button onclick="convertQuote(${q.id})" style="background: transparent; border: 1px solid #e0e0e0; color: #555555; padding: 0.375rem 0.75rem; border-radius: 8px; font-size: 0.8125rem; font-weight: 500; cursor: pointer">Convert</button>`);
            actions.push(`<button onclick="deleteQuote(${q.id})" style="background: transparent; border: 1px solid #e0e0e0; color: #555555; padding: 0.375rem 0.75rem; border-radius: 8px; font-size: 0.8125rem; font-weight: 500; cursor: pointer">Delete</button>`);
            return `
            <tr>
                <td style="padding: 0.625rem 1rem; font-size: 0.8125rem; color: #555555; border-bottom: 1px solid #f5f5f5; font-weight: 600">${q.quote_number}</td>
                <td style="padding: 0.625rem 1rem; font-size: 0.8125rem; color: #555555; border-bottom: 1px solid #f5f5f5">${q.customer_name}</td>
                <td style="padding: 0.625rem 1rem; font-size: 0.8125rem; color: #555555; border-bottom: 1px solid #f5f5f5">${new Date(q.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</td>
                <td style="padding: 0.625rem 1rem; font-size: 0.8125rem; color: #555555; border-bottom: 1px solid #f5f5f5">${q.valid_until || '-'}</td>
                <td style="padding: 0.625rem 1rem; font-size: 0.8125rem; color: #555555; border-bottom: 1px solid #f5f5f5; text-align: right; font-weight: 700">JD ${parseFloat(q.grand_total).toFixed(2)}</td>
                <td style="padding: 0.625rem 1rem; font-size: 0.8125rem; color: #555555; border-bottom: 1px solid #f5f5f5"><span style="display: inline-flex; align-items: center; padding: 0.25rem 0.625rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; white-space: nowrap; background: ${badgeBg[q.status]}; color: ${badgeColors[q.status]}">${q.status.charAt(0).toUpperCase() + q.status.slice(1)}</span></td>
                <td style="padding: 0.625rem 1rem; font-size: 0.8125rem; color: #555555; border-bottom: 1px solid #f5f5f5; text-align: right"><div style="display: flex; gap: 0.375rem; justify-content: flex-end; flex-wrap: wrap">${actions.join('')}</div></td>
            </tr>`;
        }).join('');
    }

    function renderPagination(data) {
        if (!data.last_page || data.last_page <= 1) { pagination.innerHTML = ''; return; }
        pagination.innerHTML = `<span>Page ${data.current_page} of ${data.last_page}</span><div style="display:flex;gap:0.5rem"><a href="#" onclick="event.preventDefault();goPage(${data.current_page - 1})" style="background: transparent; border: 1px solid #e0e0e0; color: #555555; padding: 0.375rem 0.75rem; border-radius: 8px; font-size: 0.8125rem; font-weight: 500; cursor: pointer; text-decoration: none; ${data.current_page === 1 ? 'opacity:0.4;pointer-events:none' : ''}">Prev</a><a href="#" onclick="event.preventDefault();goPage(${data.current_page + 1})" style="background: transparent; border: 1px solid #e0e0e0; color: #555555; padding: 0.375rem 0.75rem; border-radius: 8px; font-size: 0.8125rem; font-weight: 500; cursor: pointer; text-decoration: none; ${data.current_page === data.last_page ? 'opacity:0.4;pointer-events:none' : ''}">Next</a></div>`;
    }

    window.goPage = function(page) { currentPage = page; loadQuotes(); };

    document.getElementById('createModal').addEventListener('click', function(e) { if (e.target === this) closeCreateModal(); });

    loadQuotes();
})();
</script>
@endpush
