@extends('layouts.app')
@section('page-title', 'Subscriptions')

@section('content')
<div style="padding: 1.5rem; max-width: 1400px; margin: 0 auto">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem">
        <div>
            <h1 style="font-size: 1.5rem; font-weight: 700; color: #000080; margin: 0">Subscriptions</h1>
            <p style="font-size: 0.875rem; color: #555555; margin: 0.25rem 0 0">Manage recurring billing and subscriptions</p>
        </div>
        <button onclick="openCreateModal()" style="background: linear-gradient(135deg, #000080, #000060); color: #fff; border: none; padding: 0.5rem 1.25rem; border-radius: 8px; font-size: 0.8125rem; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            + New Subscription
        </button>
    </div>

    <div style="display: flex; gap: 1rem; margin-bottom: 1rem; align-items: center; flex-wrap: wrap">
        <input type="text" id="searchInput" placeholder="Search subscriptions..." style="border: 1.5px solid #e0e0e0; border-radius: 8px; padding: 0.5rem 0.75rem; font-size: 0.8125rem; color: #000080; background: #fff; outline: none; max-width: 360px; width: 100%">
        <select id="statusFilter" style="border: 1.5px solid #e0e0e0; border-radius: 8px; padding: 0.5rem 0.75rem; font-size: 0.8125rem; color: #000080; background: #fff; outline: none">
            <option value="">All Status</option>
            <option value="active">Active</option>
            <option value="paused">Paused</option>
            <option value="cancelled">Cancelled</option>
            <option value="expired">Expired</option>
        </select>
    </div>

    <div style="background: #fff; border: 1px solid #e0e0e0; border-radius: 12px; overflow: auto">
        <table style="width: 100%; border-collapse: collapse">
            <thead>
                <tr>
                    <th style="padding: 0.625rem 1rem; font-size: 0.6875rem; font-weight: 700; color: #888888; text-transform: uppercase; letter-spacing: 0.06em; border-bottom: 1px solid #e0e0e0; text-align: left">Sub #</th>
                    <th style="padding: 0.625rem 1rem; font-size: 0.6875rem; font-weight: 700; color: #888888; text-transform: uppercase; letter-spacing: 0.06em; border-bottom: 1px solid #e0e0e0; text-align: left">Customer</th>
                    <th style="padding: 0.625rem 1rem; font-size: 0.6875rem; font-weight: 700; color: #888888; text-transform: uppercase; letter-spacing: 0.06em; border-bottom: 1px solid #e0e0e0; text-align: left">Plan</th>
                    <th style="padding: 0.625rem 1rem; font-size: 0.6875rem; font-weight: 700; color: #888888; text-transform: uppercase; letter-spacing: 0.06em; border-bottom: 1px solid #e0e0e0; text-align: left">Cycle</th>
                    <th style="padding: 0.625rem 1rem; font-size: 0.6875rem; font-weight: 700; color: #888888; text-transform: uppercase; letter-spacing: 0.06em; border-bottom: 1px solid #e0e0e0; text-align: right">Amount</th>
                    <th style="padding: 0.625rem 1rem; font-size: 0.6875rem; font-weight: 700; color: #888888; text-transform: uppercase; letter-spacing: 0.06em; border-bottom: 1px solid #e0e0e0; text-align: left">Next Billing</th>
                    <th style="padding: 0.625rem 1rem; font-size: 0.6875rem; font-weight: 700; color: #888888; text-transform: uppercase; letter-spacing: 0.06em; border-bottom: 1px solid #e0e0e0; text-align: left">Status</th>
                    <th style="padding: 0.625rem 1rem; font-size: 0.6875rem; font-weight: 700; color: #888888; text-transform: uppercase; letter-spacing: 0.06em; border-bottom: 1px solid #e0e0e0; text-align: right">Actions</th>
                </tr>
            </thead>
            <tbody id="subsTableBody"></tbody>
        </table>
    </div>

    <div id="pagination" style="display: flex; align-items: center; justify-content: space-between; margin-top: 1rem; font-size: 0.8125rem; color: #555555"></div>
</div>

<div id="createModal" style="position: fixed; inset: 0; background: rgba(15,23,42,0.4); z-index: 100; display: none; align-items: center; justify-content: center; padding: 1rem">
    <div style="background: #fff; border-radius: 12px; padding: 1.5rem; max-height: 90vh; overflow-y: auto; width: 100%; max-width: 550px">
        <h3 style="font-size: 1.125rem; font-weight: 700; color: #000080; margin: 0 0 1.25rem">New Subscription</h3>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem">
            <div>
                <label style="display: block; font-size: 0.75rem; font-weight: 600; color: #555555; margin-bottom: 0.3rem">Customer Name *</label>
                <input type="text" id="sCustomerName" required style="width: 100%; border: 1.5px solid #e0e0e0; border-radius: 8px; padding: 0.5rem 0.75rem; font-size: 0.8125rem; color: #000080; background: #fff; outline: none; box-sizing: border-box">
            </div>
            <div>
                <label style="display: block; font-size: 0.75rem; font-weight: 600; color: #555555; margin-bottom: 0.3rem">Contact</label>
                <input type="text" id="sContact" style="width: 100%; border: 1.5px solid #e0e0e0; border-radius: 8px; padding: 0.5rem 0.75rem; font-size: 0.8125rem; color: #000080; background: #fff; outline: none; box-sizing: border-box">
            </div>
        </div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem">
            <div>
                <label style="display: block; font-size: 0.75rem; font-weight: 600; color: #555555; margin-bottom: 0.3rem">Plan Name *</label>
                <input type="text" id="sPlanName" required style="width: 100%; border: 1.5px solid #e0e0e0; border-radius: 8px; padding: 0.5rem 0.75rem; font-size: 0.8125rem; color: #000080; background: #fff; outline: none; box-sizing: border-box">
            </div>
            <div>
                <label style="display: block; font-size: 0.75rem; font-weight: 600; color: #555555; margin-bottom: 0.3rem">Amount *</label>
                <input type="number" id="sAmount" step="0.01" min="0" required inputmode="decimal" style="width: 100%; border: 1.5px solid #e0e0e0; border-radius: 8px; padding: 0.5rem 0.75rem; font-size: 0.8125rem; color: #000080; background: #fff; outline: none; box-sizing: border-box">
            </div>
        </div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem">
            <div>
                <label style="display: block; font-size: 0.75rem; font-weight: 600; color: #555555; margin-bottom: 0.3rem">Billing Cycle *</label>
                <select id="sCycle" style="width: 100%; border: 1.5px solid #e0e0e0; border-radius: 8px; padding: 0.5rem 0.75rem; font-size: 0.8125rem; color: #000080; background: #fff; outline: none; box-sizing: border-box">
                    <option value="monthly">Monthly</option>
                    <option value="weekly">Weekly</option>
                    <option value="daily">Daily</option>
                    <option value="quarterly">Quarterly</option>
                    <option value="yearly">Yearly</option>
                </select>
            </div>
            <div>
                <label style="display: block; font-size: 0.75rem; font-weight: 600; color: #555555; margin-bottom: 0.3rem">Start Date *</label>
                <input type="date" id="sStartDate" required style="width: 100%; border: 1.5px solid #e0e0e0; border-radius: 8px; padding: 0.5rem 0.75rem; font-size: 0.8125rem; color: #000080; background: #fff; outline: none; box-sizing: border-box">
            </div>
        </div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem">
            <div>
                <label style="display: block; font-size: 0.75rem; font-weight: 600; color: #555555; margin-bottom: 0.3rem">Next Billing Date</label>
                <input type="date" id="sNextBilling" style="width: 100%; border: 1.5px solid #e0e0e0; border-radius: 8px; padding: 0.5rem 0.75rem; font-size: 0.8125rem; color: #000080; background: #fff; outline: none; box-sizing: border-box">
            </div>
            <div>
                <label style="display: block; font-size: 0.75rem; font-weight: 600; color: #555555; margin-bottom: 0.3rem">Notes</label>
                <input type="text" id="sNotes" style="width: 100%; border: 1.5px solid #e0e0e0; border-radius: 8px; padding: 0.5rem 0.75rem; font-size: 0.8125rem; color: #000080; background: #fff; outline: none; box-sizing: border-box">
            </div>
        </div>
        <div style="display: flex; justify-content: flex-end; gap: 0.75rem; padding-top: 1rem; border-top: 1px solid #f5f5f5">
            <button onclick="closeCreateModal()" style="background: transparent; border: 1px solid #e0e0e0; color: #555555; padding: 0.375rem 0.75rem; border-radius: 8px; font-size: 0.8125rem; font-weight: 500; cursor: pointer">Cancel</button>
            <button onclick="saveSubscription()" style="background: linear-gradient(135deg, #000080, #000060); color: #fff; border: none; padding: 0.5rem 1.25rem; border-radius: 8px; font-size: 0.8125rem; font-weight: 600; cursor: pointer">Create</button>
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
    const tbody = document.getElementById('subsTableBody');
    const pagination = document.getElementById('pagination');
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');

    let debounce;
    searchInput.addEventListener('input', () => { clearTimeout(debounce); debounce = setTimeout(() => { currentPage = 1; loadSubs(); }, 300); });
    statusFilter.addEventListener('change', () => { currentPage = 1; loadSubs(); });

    const badgeColors = { active: '#000060', paused: '#d97706', cancelled: '#dc2626', expired: '#555555' };
    const badgeBg = { active: '#d1fae5', paused: '#fef3c7', cancelled: '#fee2e2', expired: '#f5f5f5' };

    window.openCreateModal = function() {
        document.getElementById('sCustomerName').value = '';
        document.getElementById('sContact').value = '';
        document.getElementById('sPlanName').value = '';
        document.getElementById('sAmount').value = '';
        document.getElementById('sStartDate').value = new Date().toISOString().split('T')[0];
        document.getElementById('sNextBilling').value = '';
        document.getElementById('sNotes').value = '';
        document.getElementById('createModal').style.display = 'flex';
    };

    window.closeCreateModal = function() { document.getElementById('createModal').style.display = 'none'; };

    window.saveSubscription = async function() {
        const payload = {
            customer_name: document.getElementById('sCustomerName').value,
            customer_contact: document.getElementById('sContact').value || null,
            plan_name: document.getElementById('sPlanName').value,
            amount: parseFloat(document.getElementById('sAmount').value),
            billing_cycle: document.getElementById('sCycle').value,
            start_date: document.getElementById('sStartDate').value,
            next_billing_date: document.getElementById('sNextBilling').value || null,
            notes: document.getElementById('sNotes').value || null,
        };
        if (!payload.customer_name || !payload.plan_name || !payload.amount) { alert('Fill required fields'); return; }
        try {
            const res = await apiFetch('{{ url("/api/subscriptions") }}', { method: 'POST', body: JSON.stringify(payload) });
            const data = await res.json();
            if (data.success) { closeCreateModal(); loadSubs(); } else { alert(data.error || 'Error'); }
        } catch (e) { alert('Error'); }
    };

    window.pauseSub = async function(id) {
        if (!confirm('Pause this subscription?')) return;
        try {
            const res = await apiFetch('{{ url("/api/subscriptions") }}/' + id + '/pause', { method: 'POST' });
            const data = await res.json();
            if (data.success) loadSubs(); else alert(data.error || 'Error');
        } catch (e) { alert('Error'); }
    };

    window.resumeSub = async function(id) {
        try {
            const res = await apiFetch('{{ url("/api/subscriptions") }}/' + id + '/resume', { method: 'POST' });
            const data = await res.json();
            if (data.success) loadSubs(); else alert(data.error || 'Error');
        } catch (e) { alert('Error'); }
    };

    window.cancelSub = async function(id) {
        if (!confirm('Cancel this subscription?')) return;
        try {
            const res = await apiFetch('{{ url("/api/subscriptions") }}/' + id + '/cancel', { method: 'POST' });
            const data = await res.json();
            if (data.success) loadSubs(); else alert(data.error || 'Error');
        } catch (e) { alert('Error'); }
    };

    window.deleteSub = async function(id) {
        if (!confirm('Delete this subscription?')) return;
        try {
            const res = await apiFetch('{{ url("/api/subscriptions") }}/' + id, { method: 'DELETE' });
            const data = await res.json();
            if (data.success) loadSubs();
        } catch (e) { alert('Error'); }
    };

    async function loadSubs() {
        const params = new URLSearchParams({ q: searchInput.value, status: statusFilter.value, page: currentPage, per_page: 15 });
        try {
            const res = await apiFetch('{{ url("/api/subscriptions") }}?' + params);
            const data = await res.json();
            renderTable(data.data);
            renderPagination(data);
        } catch (e) { console.error(e); }
    }

    function renderTable(subs) {
        if (!subs || subs.length === 0) {
            tbody.innerHTML = '<tr><td colspan="8" style="padding: 2rem 1rem; text-align: center; color: #888888; font-size: 0.875rem">No subscriptions found</td></tr>';
            return;
        }
        tbody.innerHTML = subs.map(s => {
            const actions = [];
            if (s.status === 'active') actions.push(`<button onclick="pauseSub(${s.id})" style="background: transparent; border: 1px solid #e0e0e0; color: #555555; padding: 0.375rem 0.75rem; border-radius: 8px; font-size: 0.8125rem; font-weight: 500; cursor: pointer">Pause</button>`);
            if (s.status === 'paused') actions.push(`<button onclick="resumeSub(${s.id})" style="background: transparent; border: 1px solid #e0e0e0; color: #555555; padding: 0.375rem 0.75rem; border-radius: 8px; font-size: 0.8125rem; font-weight: 500; cursor: pointer">Resume</button>`);
            if (!['cancelled', 'expired'].includes(s.status)) actions.push(`<button onclick="cancelSub(${s.id})" style="background: transparent; border: 1px solid #e0e0e0; color: #555555; padding: 0.375rem 0.75rem; border-radius: 8px; font-size: 0.8125rem; font-weight: 500; cursor: pointer">Cancel</button>`);
            actions.push(`<button onclick="deleteSub(${s.id})" style="background: transparent; border: 1px solid #e0e0e0; color: #555555; padding: 0.375rem 0.75rem; border-radius: 8px; font-size: 0.8125rem; font-weight: 500; cursor: pointer">Delete</button>`);
            return `
            <tr>
                <td style="padding: 0.625rem 1rem; font-size: 0.8125rem; color: #555555; border-bottom: 1px solid #f5f5f5; font-weight: 600">${s.subscription_number}</td>
                <td style="padding: 0.625rem 1rem; font-size: 0.8125rem; color: #555555; border-bottom: 1px solid #f5f5f5">${s.customer_name}</td>
                <td style="padding: 0.625rem 1rem; font-size: 0.8125rem; color: #555555; border-bottom: 1px solid #f5f5f5">${s.plan_name}</td>
                <td style="padding: 0.625rem 1rem; font-size: 0.8125rem; color: #555555; border-bottom: 1px solid #f5f5f5; text-transform: capitalize">${s.billing_cycle}</td>
                <td style="padding: 0.625rem 1rem; font-size: 0.8125rem; color: #555555; border-bottom: 1px solid #f5f5f5; text-align: right; font-weight: 700">JD ${parseFloat(s.amount).toFixed(2)}</td>
                <td style="padding: 0.625rem 1rem; font-size: 0.8125rem; color: #555555; border-bottom: 1px solid #f5f5f5">${s.next_billing_date || '-'}</td>
                <td style="padding: 0.625rem 1rem; font-size: 0.8125rem; color: #555555; border-bottom: 1px solid #f5f5f5"><span style="display: inline-flex; align-items: center; padding: 0.25rem 0.625rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; white-space: nowrap; background: ${badgeBg[s.status]}; color: ${badgeColors[s.status]}">${s.status.charAt(0).toUpperCase() + s.status.slice(1)}</span></td>
                <td style="padding: 0.625rem 1rem; font-size: 0.8125rem; color: #555555; border-bottom: 1px solid #f5f5f5; text-align: right"><div style="display: flex; gap: 0.375rem; justify-content: flex-end; flex-wrap: wrap">${actions.join('')}</div></td>
            </tr>`;
        }).join('');
    }

    function renderPagination(data) {
        if (!data.last_page || data.last_page <= 1) { pagination.innerHTML = ''; return; }
        pagination.innerHTML = `<span>Page ${data.current_page} of ${data.last_page}</span><div style="display:flex;gap:0.5rem"><a href="#" onclick="event.preventDefault();goPage(${data.current_page - 1})" style="background: transparent; border: 1px solid #e0e0e0; color: #555555; padding: 0.375rem 0.75rem; border-radius: 8px; font-size: 0.8125rem; font-weight: 500; cursor: pointer; text-decoration: none; ${data.current_page === 1 ? 'opacity:0.4;pointer-events:none' : ''}">Prev</a><a href="#" onclick="event.preventDefault();goPage(${data.current_page + 1})" style="background: transparent; border: 1px solid #e0e0e0; color: #555555; padding: 0.375rem 0.75rem; border-radius: 8px; font-size: 0.8125rem; font-weight: 500; cursor: pointer; text-decoration: none; ${data.current_page === data.last_page ? 'opacity:0.4;pointer-events:none' : ''}">Next</a></div>`;
    }

    window.goPage = function(page) { currentPage = page; loadSubs(); };

    document.getElementById('createModal').addEventListener('click', function(e) { if (e.target === this) closeCreateModal(); });

    loadSubs();
})();
</script>
@endpush
