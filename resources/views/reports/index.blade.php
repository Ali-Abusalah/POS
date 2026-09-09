@extends('layouts.app')
@section('page-title', 'Reports')

@section('content')
<div style="padding: 1.5rem; max-width: 1400px; margin: 0 auto">
    <h1 style="font-size: 1.5rem; font-weight: 700; color: #000080; margin: 0; margin-bottom: 1.25rem">Sales Reports</h1>

    <div style="display: flex; gap: 0.5rem; margin-bottom: 1.5rem; align-items: center; flex-wrap: wrap">
        <input type="date" id="dateFrom" style="border: 1.5px solid #e0e0e0; border-radius: 8px; padding: 0.5rem 0.75rem; font-size: 0.8125rem; color: #000080; background: #ffffff; outline: none">
        <span style="font-size: 1rem; color: #888888">&rarr;</span>
        <input type="date" id="dateTo" style="border: 1.5px solid #e0e0e0; border-radius: 8px; padding: 0.5rem 0.75rem; font-size: 0.8125rem; color: #000080; background: #ffffff; outline: none">
        <button onclick="loadReports()" style="background: linear-gradient(135deg, #000080, #000060); color: #ffffff; border: none; padding: 0.5rem 1.25rem; border-radius: 8px; font-size: 0.8125rem; font-weight: 600; cursor: pointer; width: auto">Run Report</button>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 0.875rem; margin-bottom: 1.5rem">
        <div style="background: #ffffff; border: 1px solid #e0e0e0; border-radius: 12px; padding: 1rem 1.25rem">
            <div style="font-size: 0.6875rem; font-weight: 600; color: #555555; text-transform: uppercase; letter-spacing: 0.05em">Paid Invoices</div>
            <div style="font-size: 1.5rem; font-weight: 800; color: #000080; margin-top: 0.25rem" id="statInvoices">0</div>
        </div>
        <div style="background: #ffffff; border: 1px solid #e0e0e0; border-radius: 12px; padding: 1rem 1.25rem">
            <div style="font-size: 0.6875rem; font-weight: 600; color: #555555; text-transform: uppercase; letter-spacing: 0.05em">Revenue</div>
            <div style="font-size: 1.5rem; font-weight: 800; color: #000080; margin-top: 0.25rem" id="statRevenue">JD 0.00</div>
        </div>
        <div style="background: #ffffff; border: 1px solid #e0e0e0; border-radius: 12px; padding: 1rem 1.25rem">
            <div style="font-size: 0.6875rem; font-weight: 600; color: #555555; text-transform: uppercase; letter-spacing: 0.05em">Tax Collected</div>
            <div style="font-size: 1.5rem; font-weight: 800; color: #8b5cf6; margin-top: 0.25rem" id="statTax">JD 0.00</div>
        </div>
        <div style="background: #ffffff; border: 1px solid #e0e0e0; border-radius: 12px; padding: 1rem 1.25rem">
            <div style="font-size: 0.6875rem; font-weight: 600; color: #555555; text-transform: uppercase; letter-spacing: 0.05em">Voided</div>
            <div style="font-size: 1.5rem; font-weight: 800; color: #dc2626; margin-top: 0.25rem" id="statVoided">0</div>
        </div>
    </div>

    <div style="display: flex; gap: 1.25rem">
        <div style="flex: 1; min-width: 0">
            <div style="background: #ffffff; border: 1px solid #e0e0e0; border-radius: 12px; overflow: hidden; margin-bottom: 1.25rem">
                <div style="padding: 0.875rem 1.25rem; border-bottom: 1px solid #e0e0e0; font-weight: 700; font-size: 0.875rem; color: #000080">Daily Sales</div>
                <div style="padding: 1.25rem">
                    <div id="dailyChart" style="display: flex; align-items: flex-end; gap: 3px; height: 200px; padding-top: 1rem"></div>
                    <div id="dailyEmpty" style="text-align: center; color: #555555; font-size: 0.875rem; padding: 2rem; display: none">No sales data for this period</div>
                </div>
            </div>

            <div style="background: #ffffff; border: 1px solid #e0e0e0; border-radius: 12px; overflow: hidden">
                <div style="padding: 0.875rem 1.25rem; border-bottom: 1px solid #e0e0e0; font-weight: 700; font-size: 0.875rem; color: #000080">Top Customers</div>
                <div style="overflow-x: auto">
                    <table style="width: 100%; border-collapse: collapse">
                        <thead>
                            <tr>
                                <th style="padding: 0.625rem 1rem; font-size: 0.6875rem; font-weight: 700; color: #555555; text-transform: uppercase; letter-spacing: 0.06em; border-bottom: 1px solid #e0e0e0; text-align: left">Customer</th>
                                <th style="padding: 0.625rem 1rem; font-size: 0.6875rem; font-weight: 700; color: #555555; text-transform: uppercase; letter-spacing: 0.06em; border-bottom: 1px solid #e0e0e0; text-align: right">Invoices</th>
                                <th style="padding: 0.625rem 1rem; font-size: 0.6875rem; font-weight: 700; color: #555555; text-transform: uppercase; letter-spacing: 0.06em; border-bottom: 1px solid #e0e0e0; text-align: right">Total Spent</th>
                            </tr>
                        </thead>
                        <tbody id="topCustomersBody">
                            <tr><td colspan="3" style="padding: 2rem 1rem; text-align: center; color: #555555; font-size: 0.875rem">No data</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div style="width: 360px; flex-shrink: 0">
            <div style="background: #ffffff; border: 1px solid #e0e0e0; border-radius: 12px; overflow: hidden">
                <div style="padding: 0.875rem 1.25rem; border-bottom: 1px solid #e0e0e0; font-weight: 700; font-size: 0.875rem; color: #000080">Top Selling Items</div>
                <div style="overflow-x: auto">
                    <table style="width: 100%; border-collapse: collapse">
                        <thead>
                            <tr>
                                <th style="padding: 0.625rem 1rem; font-size: 0.6875rem; font-weight: 700; color: #555555; text-transform: uppercase; letter-spacing: 0.06em; border-bottom: 1px solid #e0e0e0; text-align: left">Item</th>
                                <th style="padding: 0.625rem 1rem; font-size: 0.6875rem; font-weight: 700; color: #555555; text-transform: uppercase; letter-spacing: 0.06em; border-bottom: 1px solid #e0e0e0; text-align: right">Qty Sold</th>
                                <th style="padding: 0.625rem 1rem; font-size: 0.6875rem; font-weight: 700; color: #555555; text-transform: uppercase; letter-spacing: 0.06em; border-bottom: 1px solid #e0e0e0; text-align: right">Revenue</th>
                            </tr>
                        </thead>
                        <tbody id="topItemsBody">
                            <tr><td colspan="3" style="padding: 2rem 1rem; text-align: center; color: #555555; font-size: 0.875rem">No data</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
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

    const today = new Date();
    const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
    document.getElementById('dateFrom').value = firstDay.toISOString().split('T')[0];
    document.getElementById('dateTo').value = today.toISOString().split('T')[0];

    window.loadReports = async function() {
        const from = document.getElementById('dateFrom').value;
        const to = document.getElementById('dateTo').value;
        try {
            const res = await apiFetch('{{ url("/api/reports/sales") }}?date_from=' + from + '&date_to=' + to);
            const data = await res.json();
            document.getElementById('statInvoices').textContent = data.summary.total_invoices;
            document.getElementById('statRevenue').textContent = 'JD ' + parseFloat(data.summary.total_revenue).toFixed(2);
            document.getElementById('statTax').textContent = 'JD ' + parseFloat(data.summary.total_tax).toFixed(2);
            document.getElementById('statVoided').textContent = data.summary.void_count;
            renderDailyChart(data.daily_sales);
            renderTopItems(data.top_items);
            renderTopCustomers(data.top_customers);
        } catch (e) { console.error(e); }
    };

    function renderDailyChart(daily) {
        const container = document.getElementById('dailyChart');
        const empty = document.getElementById('dailyEmpty');
        if (!daily || daily.length === 0) { container.innerHTML = ''; empty.style.display = 'block'; return; }
        empty.style.display = 'none';
        const maxVal = Math.max(...daily.map(d => parseFloat(d.total)), 1);
        container.innerHTML = daily.map(d => {
            const h = Math.max(2, (parseFloat(d.total) / maxVal) * 170);
            const date = new Date(d.date);
            const label = (date.getMonth()+1) + '/' + date.getDate();
            return `
            <div style="flex: 1; display: flex; flex-direction: column; align-items: center; gap: 4px; min-width: 0">
                <span style="font-size: 0.5625rem; color: #888888; white-space: nowrap">${parseFloat(d.total).toFixed(0)}</span>
                <div style="width: 100%; border-radius: 4px 4px 0 0; background: linear-gradient(to top, #000080, #000080); height: ${h}px" title="${d.date}: ${parseFloat(d.total).toFixed(2)} JD"></div>
                <span style="font-size: 0.5625rem; color: #555555; white-space: nowrap">${label}</span>
            </div>`;
        }).join('');
    }

    function renderTopItems(items) {
        const tbody = document.getElementById('topItemsBody');
        if (!items || items.length === 0) { tbody.innerHTML = '<tr><td colspan="3" style="padding: 2rem 1rem; text-align: center; color: #555555; font-size: 0.875rem">No data</td></tr>'; return; }
        tbody.innerHTML = items.map(i => `
            <tr>
                <td style="padding: 0.625rem 1rem; font-size: 0.8125rem; color: #888888; border-bottom: 1px solid #e0e0e0; font-weight: 600">${i.item_name}</td>
                <td style="padding: 0.625rem 1rem; font-size: 0.8125rem; color: #888888; border-bottom: 1px solid #e0e0e0; text-align: right">${i.total_qty}</td>
                <td style="padding: 0.625rem 1rem; font-size: 0.8125rem; color: #888888; border-bottom: 1px solid #e0e0e0; text-align: right; font-weight: 600">JD ${parseFloat(i.total_revenue).toFixed(2)}</td>
            </tr>
        `).join('');
    }

    function renderTopCustomers(customers) {
        const tbody = document.getElementById('topCustomersBody');
        if (!customers || customers.length === 0) { tbody.innerHTML = '<tr><td colspan="3" style="padding: 2rem 1rem; text-align: center; color: #555555; font-size: 0.875rem">No data</td></tr>'; return; }
        tbody.innerHTML = customers.map(c => `
            <tr>
                <td style="padding: 0.625rem 1rem; font-size: 0.8125rem; color: #888888; border-bottom: 1px solid #e0e0e0; font-weight: 600">${c.customer_name}</td>
                <td style="padding: 0.625rem 1rem; font-size: 0.8125rem; color: #888888; border-bottom: 1px solid #e0e0e0; text-align: right">${c.invoice_count}</td>
                <td style="padding: 0.625rem 1rem; font-size: 0.8125rem; color: #888888; border-bottom: 1px solid #e0e0e0; text-align: right; font-weight: 600">JD ${parseFloat(c.total_spent).toFixed(2)}</td>
            </tr>
        `).join('');
    }

    loadReports();
})();
</script>
@endpush
