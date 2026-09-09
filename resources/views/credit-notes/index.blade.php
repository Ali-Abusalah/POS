@extends('layouts.app')
@section('page-title', 'Credit Notes')

@section('content')
<div style="padding: 1.5rem; max-width: 1400px; margin: 0 auto">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem">
        <div>
            <h1 style="font-size: 1.5rem; font-weight: 700; color: #000080; margin: 0">Credit Notes</h1>
            <p style="font-size: 0.875rem; color: #555555; margin: 0.25rem 0 0">Issue and manage credit notes linked to invoices</p>
        </div>
        <button onclick="openCreateModal()" style="background: linear-gradient(135deg, #000080, #000060); color: #fff; border: none; padding: 0.5rem 1.25rem; border-radius: 8px; font-size: 0.8125rem; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            + New Credit Note
        </button>
    </div>

    <div style="display: flex; gap: 1rem; margin-bottom: 1rem; align-items: center; flex-wrap: wrap">
        <input type="text" id="searchInput" placeholder="Search credit notes..." style="border: 1.5px solid #e0e0e0; border-radius: 8px; padding: 0.5rem 0.75rem; font-size: 0.8125rem; color: #000080; background: #fff; outline: none; max-width: 360px; width: 100%">
        <select id="statusFilter" style="border: 1.5px solid #e0e0e0; border-radius: 8px; padding: 0.5rem 0.75rem; font-size: 0.8125rem; color: #000080; background: #fff; outline: none">
            <option value="">All Status</option>
            <option value="issued">Issued</option>
            <option value="applied">Applied</option>
            <option value="voided">Voided</option>
        </select>
    </div>

    <div style="background: #fff; border: 1px solid #e0e0e0; border-radius: 12px; overflow: auto">
        <table style="width: 100%; border-collapse: collapse">
            <thead>
                <tr>
                    <th style="padding: 0.625rem 1rem; font-size: 0.6875rem; font-weight: 700; color: #888888; text-transform: uppercase; letter-spacing: 0.06em; border-bottom: 1px solid #e0e0e0; text-align: left">CN #</th>
                    <th style="padding: 0.625rem 1rem; font-size: 0.6875rem; font-weight: 700; color: #888888; text-transform: uppercase; letter-spacing: 0.06em; border-bottom: 1px solid #e0e0e0; text-align: left">Customer</th>
                    <th style="padding: 0.625rem 1rem; font-size: 0.6875rem; font-weight: 700; color: #888888; text-transform: uppercase; letter-spacing: 0.06em; border-bottom: 1px solid #e0e0e0; text-align: left">Date</th>
                    <th style="padding: 0.625rem 1rem; font-size: 0.6875rem; font-weight: 700; color: #888888; text-transform: uppercase; letter-spacing: 0.06em; border-bottom: 1px solid #e0e0e0; text-align: left">Reason</th>
                    <th style="padding: 0.625rem 1rem; font-size: 0.6875rem; font-weight: 700; color: #888888; text-transform: uppercase; letter-spacing: 0.06em; border-bottom: 1px solid #e0e0e0; text-align: right">Amount</th>
                    <th style="padding: 0.625rem 1rem; font-size: 0.6875rem; font-weight: 700; color: #888888; text-transform: uppercase; letter-spacing: 0.06em; border-bottom: 1px solid #e0e0e0; text-align: left">Status</th>
                    <th style="padding: 0.625rem 1rem; font-size: 0.6875rem; font-weight: 700; color: #888888; text-transform: uppercase; letter-spacing: 0.06em; border-bottom: 1px solid #e0e0e0; text-align: right">Actions</th>
                </tr>
            </thead>
            <tbody id="cnTableBody"></tbody>
        </table>
    </div>

    <div id="pagination" style="display: flex; align-items: center; justify-content: space-between; margin-top: 1rem; font-size: 0.8125rem; color: #555555"></div>
</div>

<div id="createModal" style="position: fixed; inset: 0; background: rgba(15,23,42,0.4); z-index: 100; display: none; align-items: center; justify-content: center; padding: 1rem">
    <div style="background: #fff; border-radius: 12px; padding: 1.5rem; max-height: 90vh; overflow-y: auto; width: 100%; max-width: 550px">
        <h3 style="font-size: 1.125rem; font-weight: 700; color: #000080; margin: 0 0 1.25rem">New Credit Note</h3>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem">
            <div>
                <label style="display: block; font-size: 0.75rem; font-weight: 600; color: #555555; margin-bottom: 0.3rem">Customer Name *</label>
                <input type="text" id="cnCustomerName" required style="width: 100%; border: 1.5px solid #e0e0e0; border-radius: 8px; padding: 0.5rem 0.75rem; font-size: 0.8125rem; color: #000080; background: #fff; outline: none; box-sizing: border-box">
            </div>
            <div>
                <label style="display: block; font-size: 0.75rem; font-weight: 600; color: #555555; margin-bottom: 0.3rem">Contact</label>
                <input type="text" id="cnContact" style="width: 100%; border: 1.5px solid #e0e0e0; border-radius: 8px; padding: 0.5rem 0.75rem; font-size: 0.8125rem; color: #000080; background: #fff; outline: none; box-sizing: border-box">
            </div>
        </div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem">
            <div>
                <label style="display: block; font-size: 0.75rem; font-weight: 600; color: #555555; margin-bottom: 0.3rem">Amount *</label>
                <input type="number" id="cnAmount" step="0.01" min="0.01" required inputmode="decimal" style="width: 100%; border: 1.5px solid #e0e0e0; border-radius: 8px; padding: 0.5rem 0.75rem; font-size: 0.8125rem; color: #000080; background: #fff; outline: none; box-sizing: border-box">
            </div>
            <div>
                <label style="display: block; font-size: 0.75rem; font-weight: 600; color: #555555; margin-bottom: 0.3rem">Reason</label>
                <input type="text" id="cnReason" style="width: 100%; border: 1.5px solid #e0e0e0; border-radius: 8px; padding: 0.5rem 0.75rem; font-size: 0.8125rem; color: #000080; background: #fff; outline: none; box-sizing: border-box">
            </div>
        </div>
        <div style="display: flex; justify-content: flex-end; gap: 0.75rem; padding-top: 1rem; border-top: 1px solid #f5f5f5">
            <button onclick="closeCreateModal()" style="background: transparent; border: 1px solid #e0e0e0; color: #555555; padding: 0.375rem 0.75rem; border-radius: 8px; font-size: 0.8125rem; font-weight: 500; cursor: pointer">Cancel</button>
            <button onclick="saveCreditNote()" style="background: linear-gradient(135deg, #000080, #000060); color: #fff; border: none; padding: 0.5rem 1.25rem; border-radius: 8px; font-size: 0.8125rem; font-weight: 600; cursor: pointer">Create</button>
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
    const tbody = document.getElementById('cnTableBody');
    const pagination = document.getElementById('pagination');
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');

    let debounce;
    searchInput.addEventListener('input', () => { clearTimeout(debounce); debounce = setTimeout(() => { currentPage = 1; loadCNs(); }, 300); });
    statusFilter.addEventListener('change', () => { currentPage = 1; loadCNs(); });

    const badgeColors = { issued: '#000060', applied: '#000080', voided: '#dc2626' };
    const badgeBg = { issued: '#d1fae5', applied: '#dbeafe', voided: '#fee2e2' };

    window.openCreateModal = function() {
        document.getElementById('cnCustomerName').value = '';
        document.getElementById('cnContact').value = '';
        document.getElementById('cnAmount').value = '';
        document.getElementById('cnReason').value = '';
        document.getElementById('createModal').style.display = 'flex';
    };

    window.closeCreateModal = function() { document.getElementById('createModal').style.display = 'none'; };

    window.saveCreditNote = async function() {
        const payload = {
            customer_name: document.getElementById('cnCustomerName').value,
            customer_contact: document.getElementById('cnContact').value || null,
            amount: parseFloat(document.getElementById('cnAmount').value),
            reason: document.getElementById('cnReason').value || null,
        };
        if (!payload.customer_name || !payload.amount) { alert('Fill required fields'); return; }
        try {
            const res = await apiFetch('{{ url("/api/credit-notes") }}', { method: 'POST', body: JSON.stringify(payload) });
            const data = await res.json();
            if (data.success) { closeCreateModal(); loadCNs(); } else { alert(data.error || 'Error'); }
        } catch (e) { alert('Error'); }
    };

    window.applyCN = async function(id) {
        if (!confirm('Apply this credit note?')) return;
        try {
            const res = await apiFetch('{{ url("/api/credit-notes") }}/' + id + '/apply', { method: 'POST' });
            const data = await res.json();
            if (data.success) loadCNs(); else alert(data.error || 'Error');
        } catch (e) { alert('Error'); }
    };

    window.deleteCN = async function(id) {
        if (!confirm('Delete this credit note?')) return;
        try {
            const res = await apiFetch('{{ url("/api/credit-notes") }}/' + id, { method: 'DELETE' });
            const data = await res.json();
            if (data.success) loadCNs();
        } catch (e) { alert('Error'); }
    };

    async function loadCNs() {
        const params = new URLSearchParams({ q: searchInput.value, status: statusFilter.value, page: currentPage, per_page: 15 });
        try {
            const res = await apiFetch('{{ url("/api/credit-notes") }}?' + params);
            const data = await res.json();
            renderTable(data.data);
            renderPagination(data);
        } catch (e) { console.error(e); }
    }

    function renderTable(cns) {
        if (!cns || cns.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7" style="padding: 2rem 1rem; text-align: center; color: #888888; font-size: 0.875rem">No credit notes found</td></tr>';
            return;
        }
        tbody.innerHTML = cns.map(cn => {
            const actions = [];
            if (cn.status === 'issued') actions.push(`<button onclick="applyCN(${cn.id})" style="background: transparent; border: 1px solid #e0e0e0; color: #555555; padding: 0.375rem 0.75rem; border-radius: 8px; font-size: 0.8125rem; font-weight: 500; cursor: pointer">Apply</button>`);
            actions.push(`<button onclick="deleteCN(${cn.id})" style="background: transparent; border: 1px solid #e0e0e0; color: #555555; padding: 0.375rem 0.75rem; border-radius: 8px; font-size: 0.8125rem; font-weight: 500; cursor: pointer">Delete</button>`);
            return `
            <tr>
                <td style="padding: 0.625rem 1rem; font-size: 0.8125rem; color: #555555; border-bottom: 1px solid #f5f5f5; font-weight: 600">${cn.credit_note_number}</td>
                <td style="padding: 0.625rem 1rem; font-size: 0.8125rem; color: #555555; border-bottom: 1px solid #f5f5f5">${cn.customer_name}</td>
                <td style="padding: 0.625rem 1rem; font-size: 0.8125rem; color: #555555; border-bottom: 1px solid #f5f5f5">${new Date(cn.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</td>
                <td style="padding: 0.625rem 1rem; font-size: 0.8125rem; color: #555555; border-bottom: 1px solid #f5f5f5; max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap">${cn.reason || '-'}</td>
                <td style="padding: 0.625rem 1rem; font-size: 0.8125rem; color: #555555; border-bottom: 1px solid #f5f5f5; text-align: right; font-weight: 700">JD ${parseFloat(cn.amount).toFixed(2)}</td>
                <td style="padding: 0.625rem 1rem; font-size: 0.8125rem; color: #555555; border-bottom: 1px solid #f5f5f5"><span style="display: inline-flex; align-items: center; padding: 0.25rem 0.625rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; white-space: nowrap; background: ${badgeBg[cn.status]}; color: ${badgeColors[cn.status]}">${cn.status.charAt(0).toUpperCase() + cn.status.slice(1)}</span></td>
                <td style="padding: 0.625rem 1rem; font-size: 0.8125rem; color: #555555; border-bottom: 1px solid #f5f5f5; text-align: right"><div style="display: flex; gap: 0.375rem; justify-content: flex-end; flex-wrap: wrap">${actions.join('')}</div></td>
            </tr>`;
        }).join('');
    }

    function renderPagination(data) {
        if (!data.last_page || data.last_page <= 1) { pagination.innerHTML = ''; return; }
        pagination.innerHTML = `<span>Page ${data.current_page} of ${data.last_page}</span><div style="display:flex;gap:0.5rem"><a href="#" onclick="event.preventDefault();goPage(${data.current_page - 1})" style="background: transparent; border: 1px solid #e0e0e0; color: #555555; padding: 0.375rem 0.75rem; border-radius: 8px; font-size: 0.8125rem; font-weight: 500; cursor: pointer; text-decoration: none; ${data.current_page === 1 ? 'opacity:0.4;pointer-events:none' : ''}">Prev</a><a href="#" onclick="event.preventDefault();goPage(${data.current_page + 1})" style="background: transparent; border: 1px solid #e0e0e0; color: #555555; padding: 0.375rem 0.75rem; border-radius: 8px; font-size: 0.8125rem; font-weight: 500; cursor: pointer; text-decoration: none; ${data.current_page === data.last_page ? 'opacity:0.4;pointer-events:none' : ''}">Next</a></div>`;
    }

    window.goPage = function(page) { currentPage = page; loadCNs(); };

    document.getElementById('createModal').addEventListener('click', function(e) { if (e.target === this) closeCreateModal(); });

    loadCNs();
})();
</script>
@endpush
