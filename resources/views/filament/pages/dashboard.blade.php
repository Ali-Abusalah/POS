@php
    $todayInvoices = $this->getTodayInvoices();
    $monthInvoices = $this->getMonthInvoices();
    $todaySales = $this->getTodaySales();
    $monthSales = $this->getMonthSales();
    $chartLabels = $this->getChartLabels();
    $chartValues = $this->getChartData();
    $recentBuyers = $this->getRecentBuyers();
    $recentInvoices = $this->getRecentInvoices();
@endphp

<x-filament-panels::page>
    <style>
        [class*="filament-page"] > div, .fi-fi-page-body > div { flex-direction: row !important; flex-wrap: wrap !important; }
    </style>
    <style>
        .db-row { display: flex !important; flex-direction: row !important; gap: 1rem; width: 100% !important; margin-bottom: 1.25rem; flex-wrap: nowrap !important; }
        .db-card { flex: 1; min-width: 0; border-radius: 12px; padding: 1.25rem 1.5rem; color: #fff; display: flex; align-items: center; gap: 1rem; }
        .db-card-icon { background: rgba(255,255,255,0.25); border-radius: 12px; width: 52px; height: 52px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .db-card-icon svg { width: 26px; height: 26px; }
        .db-card-label { font-size: 0.85rem; opacity: 0.9; margin-bottom: 2px; }
        .db-card-value { font-size: 1.4rem; font-weight: 700; }
        .db-white { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.25rem 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
        .db-section-title { font-size: 0.7rem; font-weight: 600; letter-spacing: 0.12em; color: #64748b; text-transform: uppercase; margin: 0; }
        .db-green { background: linear-gradient(135deg, #43b581, #3ca374); }
        .db-teal { background: linear-gradient(135deg, #2ec4b6, #20a39e); }
        .db-coral { background: linear-gradient(135deg, #f093a4, #e87d6b); }
        .db-pink { background: linear-gradient(135deg, #f78fb3, #c44569); }
        .db-buyer { display: flex; align-items: center; justify-content: space-between; padding: 0.7rem 0; border-bottom: 1px solid #f1f5f9; }
        .db-buyer-left { display: flex; align-items: center; gap: 0.75rem; }
        .db-buyer-avatar { width: 42px; height: 42px; border-radius: 50%; background: #e2e8f0; display: flex; align-items: center; justify-content: center; color: #64748b; font-weight: 600; font-size: 0.875rem; flex-shrink: 0; position: relative; }
        .db-buyer-dot { position: absolute; bottom: 0; right: 0; width: 10px; height: 10px; border-radius: 50%; background: #22c55e; border: 2px solid #fff; }
        .db-buyer-name { font-size: 0.875rem; font-weight: 500; color: #1e293b; }
        .db-buyer-badge { display: inline-block; padding: 0.1rem 0.5rem; border-radius: 9999px; font-size: 0.6rem; font-weight: 700; color: #fff; background: #ef4444; margin-top: 2px; }
        .db-buyer-badge.paid { background: #22c55e; }
        .db-buyer-amount { font-size: 0.95rem; font-weight: 700; color: #1e293b; white-space: nowrap; }
        .db-summary { flex: 1; text-align: center; padding: 0.875rem; }
        .db-summary-label { font-size: 0.7rem; color: #64748b; margin-bottom: 0.25rem; text-transform: uppercase; letter-spacing: 0.05em; }
        .db-summary-value { font-size: 1.5rem; font-weight: 700; color: #1e293b; }
        .db-summary-value.green { color: #22c55e; }
        .db-summary-value.red { color: #ef4444; }
        .db-table th { text-align: start; padding: 0.65rem 0.75rem; font-size: 0.7rem; font-weight: 600; color: #64748b; text-transform: uppercase; }
        .db-table td { padding: 0.65rem 0.75rem; font-size: 0.85rem; }
        .db-table tr { border-bottom: 1px solid #f1f5f9; }
        .db-table thead tr { border-bottom: 2px solid #e2e8f0; }
        .db-link { color: #14b8a6; font-weight: 600; text-decoration: none; }
        .db-btn { color: #fff; padding: 0.4rem 0.9rem; border-radius: 8px; font-size: 0.8rem; font-weight: 600; text-decoration: none; }
        .db-btn-green { background: #22c55e; }
        .db-btn-blue { background: #3b82f6; }
        .db-badge-paid { display: inline-block; padding: 0.15rem 0.6rem; border-radius: 9999px; font-size: 0.65rem; font-weight: 600; color: #fff; background: #22c55e; }
        .db-badge-void { display: inline-block; padding: 0.15rem 0.6rem; border-radius: 9999px; font-size: 0.65rem; font-weight: 600; color: #fff; background: #ef4444; }
        .db-badge-other { display: inline-block; padding: 0.15rem 0.6rem; border-radius: 9999px; font-size: 0.65rem; font-weight: 600; color: #fff; background: #f59e0b; }
    </style>

    {{-- STAT CARDS --}}
    <div class="db-row" style="display:flex !important;flex-direction:row !important;flex-wrap:nowrap !important;gap:1rem;width:100% !important;margin-bottom:1.25rem !important;">
        <div class="db-card db-green">
            <div class="db-card-icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></div>
            <div><div class="db-card-label">Today Invoices</div><div class="db-card-value">+ {{ number_format($todayInvoices) }}</div></div>
        </div>
        <div class="db-card db-teal">
            <div class="db-card-icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></div>
            <div><div class="db-card-label">This Month Invoices</div><div class="db-card-value">↑ {{ number_format($monthInvoices) }}</div></div>
        </div>
        <div class="db-card db-coral">
            <div class="db-card-icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg></div>
            <div><div class="db-card-label">Today Sales</div><div class="db-card-value">↑ JD {{ number_format($todaySales, 2) }}</div></div>
        </div>
        <div class="db-card db-pink">
            <div class="db-card-icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg></div>
            <div><div class="db-card-label">This Month Sales</div><div class="db-card-value">↑ JD {{ number_format($monthSales, 2) }}</div></div>
        </div>
    </div>

    {{-- MONTHLY FINANCIAL SUMMARY --}}
    @php
        $monthIncome = (float) \App\Models\Invoice::where('status', 'paid')
            ->whereMonth('created_at', \Carbon\Carbon::now()->month)
            ->whereYear('created_at', \Carbon\Carbon::now()->year)
            ->sum('grand_total');
        $monthExpenses = 0;
        $monthNetIncome = $monthIncome - $monthExpenses;
        $targetIncome = (float)(\App\Models\Setting::getValue('target_income') ?? 999999);
        $targetExpenses = (float)(\App\Models\Setting::getValue('target_expenses') ?? 999999);
        $targetSales = (float)(\App\Models\Setting::getValue('target_sales') ?? 999999);
        $targetNetIncome = (float)(\App\Models\Setting::getValue('target_net_income') ?? 999999);
        $monthName = \Carbon\Carbon::now()->format('F');
        $pctIncome = $targetIncome > 0 ? round(($monthIncome / $targetIncome) * 100) : 0;
        $pctExpenses = $targetExpenses > 0 ? round(($monthExpenses / $targetExpenses) * 100) : 0;
        $pctSales = $targetSales > 0 ? round(($monthSales / $targetSales) * 100) : 0;
        $pctNetIncome = $targetNetIncome > 0 ? round(($monthNetIncome / $targetNetIncome) * 100) : 0;
    @endphp
    <div class="db-row" style="display:flex !important;flex-direction:row !important;flex-wrap:nowrap !important;gap:0 !important;width:100% !important;margin-bottom:1.25rem !important;">
        <div class="db-white" style="flex:1;display:flex;border-radius:12px;overflow:hidden;padding:0;">
            {{-- Income --}}
            <div style="flex:1;padding:1.25rem 1.5rem;border-inline-start:1px solid #e2e8f0;position:relative;">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;">
                    <div style="font-size:1.75rem;font-weight:700;color:#14b8a6;">{{ $pctIncome }}%</div>
                    <div style="width:36px;height:36px;border-radius:8px;background:#e0f7f5;display:flex;align-items:center;justify-content:center;">
                        <svg style="width:18px;height:18px;color:#14b8a6;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <div style="font-size:0.8rem;color:#64748b;margin:0.375rem 0 0.25rem;">{{ $monthName }} Income</div>
                <div style="font-size:0.8rem;color:#94a3b8;">JD {{ number_format($monthIncome, 2) }} / JD {{ number_format($targetIncome, 2) }}</div>
                <div style="margin-top:0.75rem;height:4px;background:#e2e8f0;border-radius:2px;overflow:hidden;">
                    <div style="height:100%;width:{{ min($pctIncome, 100) }}%;background:#14b8a6;border-radius:2px;transition:width 0.5s;"></div>
                </div>
            </div>
            {{-- Expenses --}}
            <div style="flex:1;padding:1.25rem 1.5rem;border-inline-start:1px solid #e2e8f0;position:relative;">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;">
                    <div style="font-size:1.75rem;font-weight:700;color:#ef4444;">{{ $pctExpenses }}%</div>
                    <div style="width:36px;height:36px;border-radius:8px;background:#fef2f2;display:flex;align-items:center;justify-content:center;">
                        <svg style="width:18px;height:18px;color:#ef4444;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </div>
                </div>
                <div style="font-size:0.8rem;color:#64748b;margin:0.375rem 0 0.25rem;">{{ $monthName }} Expenses</div>
                <div style="font-size:0.8rem;color:#94a3b8;">JD {{ number_format($monthExpenses, 2) }} / JD {{ number_format($targetExpenses, 2) }}</div>
                <div style="margin-top:0.75rem;height:4px;background:#e2e8f0;border-radius:2px;overflow:hidden;">
                    <div style="height:100%;width:{{ min($pctExpenses, 100) }}%;background:#ef4444;border-radius:2px;transition:width 0.5s;"></div>
                </div>
            </div>
            {{-- Sales --}}
            <div style="flex:1;padding:1.25rem 1.5rem;border-inline-start:1px solid #e2e8f0;position:relative;">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;">
                    <div style="font-size:1.75rem;font-weight:700;color:#3b82f6;">{{ $pctSales }}%</div>
                    <div style="width:36px;height:36px;border-radius:8px;background:#eff6ff;display:flex;align-items:center;justify-content:center;">
                        <svg style="width:18px;height:18px;color:#3b82f6;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/></svg>
                    </div>
                </div>
                <div style="font-size:0.8rem;color:#64748b;margin:0.375rem 0 0.25rem;">{{ $monthName }} Sales</div>
                <div style="font-size:0.8rem;color:#94a3b8;">JD {{ number_format($monthSales, 2) }} / JD {{ number_format($targetSales, 2) }}</div>
                <div style="margin-top:0.75rem;height:4px;background:#e2e8f0;border-radius:2px;overflow:hidden;">
                    <div style="height:100%;width:{{ min($pctSales, 100) }}%;background:#3b82f6;border-radius:2px;transition:width 0.5s;"></div>
                </div>
            </div>
            {{-- Net Income --}}
            <div style="flex:1;padding:1.25rem 1.5rem;position:relative;">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;">
                    <div style="font-size:1.75rem;font-weight:700;color:#8b5cf6;">{{ $pctNetIncome }}%</div>
                    <div style="width:36px;height:36px;border-radius:8px;background:#f5f3ff;display:flex;align-items:center;justify-content:center;">
                        <svg style="width:18px;height:18px;color:#8b5cf6;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                </div>
                <div style="font-size:0.8rem;color:#64748b;margin:0.375rem 0 0.25rem;">{{ $monthName }} Net Income</div>
                <div style="font-size:0.8rem;color:#94a3b8;">JD {{ number_format($monthNetIncome, 2) }} / JD {{ number_format($targetNetIncome, 2) }}</div>
                <div style="margin-top:0.75rem;height:4px;background:#e2e8f0;border-radius:2px;overflow:hidden;">
                    <div style="height:100%;width:{{ min($pctNetIncome, 100) }}%;background:#8b5cf6;border-radius:2px;transition:width 0.5s;"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- CHART + RECENT BUYERS --}}
    <div class="db-row" style="display:flex !important;flex-direction:row !important;flex-wrap:nowrap !important;gap:1rem;width:100% !important;margin-bottom:1.25rem !important;">
        <div class="db-white" style="flex: 2;">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
                <h3 class="db-section-title">Graphical presentation of invoices and sales done in last 30 days.</h3>
            </div>
            <div style="height:280px;"><canvas id="salesChart"></canvas></div>
        </div>
        <div class="db-white" style="flex: 1;">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
                <h3 class="db-section-title">Recent Buyers</h3>
            </div>
            <div style="max-height:300px;overflow-y:auto;">
                @forelse($recentBuyers as $buyer)
                    <div class="db-buyer">
                        <div class="db-buyer-left">
                            <div class="db-buyer-avatar">
                                {{ strtoupper(mb_substr($buyer['customer_name'], 0, 1)) }}
                                <div class="db-buyer-dot"></div>
                            </div>
                            <div>
                                <div class="db-buyer-name">{{ $buyer['customer_name'] }}</div>
                                <span class="db-buyer-badge">Due</span>
                            </div>
                        </div>
                        <div class="db-buyer-amount">JD {{ number_format($buyer['total_spent'], 2) }}</div>
                    </div>
                @empty
                    <div style="text-align:center;padding:2rem;color:#94a3b8;">No recent buyers</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- SUMMARY CARDS --}}
    <div class="db-row" style="display:flex !important;flex-direction:row !important;flex-wrap:nowrap !important;gap:1rem;width:100% !important;margin-bottom:1.25rem !important;">
        <div class="db-white db-summary">
            <div class="db-summary-label">Total Invoices (Paid)</div>
            <div class="db-summary-value">{{ number_format(\App\Models\Invoice::where('status','paid')->count()) }}</div>
        </div>
        <div class="db-white db-summary">
            <div class="db-summary-label">Total Revenue</div>
            <div class="db-summary-value green">JD {{ number_format(\App\Models\Invoice::where('status','paid')->sum('grand_total'), 2) }}</div>
        </div>
        <div class="db-white db-summary">
            <div class="db-summary-label">Total Tax Collected</div>
            <div class="db-summary-value green">JD {{ number_format(\App\Models\Invoice::where('status','paid')->sum('tax_total'), 2) }}</div>
        </div>
        <div class="db-white db-summary">
            <div class="db-summary-label">Total Discount</div>
            <div class="db-summary-value red">JD {{ number_format(\App\Models\Invoice::where('status','paid')->sum('discount_amount'), 2) }}</div>
        </div>
    </div>

    {{-- RECENT INVOICES --}}
    <div class="db-white">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
            <h3 class="db-section-title">Recent Invoices</h3>
            <div style="display:flex;gap:0.5rem;">
                <a href="{{ url('/admin/p-o-s') }}" class="db-btn db-btn-green">Add Sale</a>
                <a href="{{ url('/admin/invoices') }}" class="db-btn db-btn-blue">Manage Invoices</a>
                <a href="{{ url('/admin/p-o-s') }}" class="db-btn db-btn-blue">POS</a>
            </div>
        </div>
        <table class="db-table" style="width:100%;border-collapse:collapse;">
            <thead>
                <tr>
                    <th>Invoices#</th>
                    <th>Customer</th>
                    <th>Status</th>
                    <th>Due</th>
                    <th style="text-align:right;">Amount</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentInvoices as $invoice)
                    <tr>
                        <td><a href="{{ url('/admin/invoices/' . $invoice['id']) }}" class="db-link">#{{ $invoice['invoice_number'] }}</a></td>
                        <td>{{ $invoice['customer_name'] }}</td>
                        <td>
                            @if($invoice['status'] === 'paid')
                                <span class="db-badge-paid">Paid</span>
                            @elseif($invoice['status'] === 'void')
                                <span class="db-badge-void">Void</span>
                            @else
                                <span class="db-badge-other">{{ ucfirst($invoice['status']) }}</span>
                            @endif
                        </td>
                        <td style="color:#64748b;">{{ \Carbon\Carbon::parse($invoice['created_at'])->format('d-m-Y') }}</td>
                        <td style="text-align:right;font-weight:600;">JD {{ number_format($invoice['grand_total'], 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" style="text-align:center;padding:2rem;color:#94a3b8;">No invoices yet</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- CASHFLOW CHART --}}
    @php
        $cashflowLabels = $this->getCashflowLabels();
        $cashflowIncome = $this->getCashflowIncome();
        $cashflowExpenses = $this->getCashflowExpenses();
    @endphp
    <div class="db-row" style="display:flex !important;flex-direction:row !important;flex-wrap:nowrap !important;gap:1rem;width:100% !important;margin-bottom:1.25rem !important;">
        <div class="db-white" style="flex:1;">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
                <h3 class="db-section-title">Cashflow — Graphical Presentation of income and expenses have done in the last 30 days.</h3>
            </div>
            <div style="display:flex;gap:1.5rem;margin-bottom:1rem;">
                <div style="display:flex;align-items:center;gap:0.4rem;">
                    <div style="width:12px;height:12px;border-radius:3px;background:#22c55e;"></div>
                    <span style="font-size:0.8rem;color:#64748b;">Income</span>
                </div>
                <div style="display:flex;align-items:center;gap:0.4rem;">
                    <div style="width:12px;height:12px;border-radius:3px;background:#ef4444;"></div>
                    <span style="font-size:0.8rem;color:#64748b;">Expenses</span>
                </div>
            </div>
            <div style="height:250px;"><canvas id="cashflowChart"></canvas></div>
        </div>
    </div>

    {{-- TASK MANAGER + RECENT TRANSACTIONS + STOCK ALERT --}}
    <div class="db-row" style="display:flex !important;flex-direction:row !important;flex-wrap:nowrap !important;gap:1rem;width:100% !important;margin-bottom:1.25rem !important;">
        {{-- Task Manager --}}
        <div class="db-white" style="flex:1;">
            <h3 class="db-section-title" style="margin-bottom:1rem;">Task Manager</h3>
            <table class="db-table" style="width:100%;border-collapse:collapse;">
                <thead><tr><th>Tasks</th><th>Status</th></tr></thead>
                <tbody>
                    <tr><td>Review pending invoices</td><td><span class="db-badge-other">Pending</span></td></tr>
                    <tr><td>Update product prices</td><td><span class="db-badge-paid">Done</span></td></tr>
                    <tr><td>Restock low items</td><td><span class="db-badge-other">Pending</span></td></tr>
                    <tr><td>Monthly report</td><td><span class="db-badge-void">Overdue</span></td></tr>
                    <tr><td>Customer follow-up</td><td><span class="db-badge-paid">Done</span></td></tr>
                </tbody>
            </table>
        </div>

        {{-- Recent Transactions --}}
        <div class="db-white" style="flex:1.5;">
            <h3 class="db-section-title" style="margin-bottom:1rem;">Recent</h3>
            <div style="overflow-x:auto;">
                <table class="db-table" style="width:100%;border-collapse:collapse;">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Account</th>
                            <th style="text-align:right;">Debit</th>
                            <th style="text-align:right;">Credit</th>
                            <th>Method</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentInvoices as $tx)
                            <tr>
                                <td style="color:#64748b;">{{ \Carbon\Carbon::parse($tx['created_at'])->format('d-m-Y') }}</td>
                                <td>{{ $tx['customer_name'] }}</td>
                                <td style="text-align:right;color:#22c55e;">{{ $tx['payment_method'] === 'cash' ? 'JD ' . number_format($tx['grand_total'], 2) : '' }}</td>
                                <td style="text-align:right;color:#ef4444;">{{ $tx['payment_method'] !== 'cash' ? 'JD ' . number_format($tx['grand_total'], 2) : '' }}</td>
                                <td><span class="db-badge-other">{{ ucfirst($tx['payment_method']) }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="5" style="text-align:center;padding:1.5rem;color:#94a3b8;">No transactions yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Stock Alert --}}
        <div class="db-white" style="flex:1;">
            <h3 class="db-section-title" style="margin-bottom:1rem;">Stock Alert</h3>
            <div style="overflow-x:auto;">
                @php $lowStock = $this->getLowStockItems(); @endphp
                @if(count($lowStock) > 0)
                    <table class="db-table" style="width:100%;border-collapse:collapse;">
                        <thead><tr><th>Item</th><th>Code</th><th style="text-align:right;">Qty</th><th>Status</th></tr></thead>
                        <tbody>
                            @foreach($lowStock as $item)
                                <tr>
                                    <td>{{ $item['name'] }}</td>
                                    <td style="color:#64748b;">{{ $item['code'] }}</td>
                                    <td style="text-align:right;font-weight:600;color:{{ $item['quantity'] === 0 ? '#ef4444' : '#f59e0b' }};">{{ $item['quantity'] }}</td>
                                    <td>
                                        @if($item['quantity'] === 0)
                                            <span class="db-badge-void">Out of Stock</span>
                                        @else
                                            <span class="db-badge-other">Low Stock</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div style="text-align:center;padding:2rem;color:#22c55e;">
                        <svg style="width:32px;height:32px;margin:0 auto 0.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <div style="font-weight:600;">All stock is healthy</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sales Chart
            const ctx = document.getElementById('salesChart');
            if (ctx) {
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: @json($chartLabels),
                        datasets: [{
                            label: 'Sales (JD)',
                            data: @json($chartValues),
                            borderColor: '#2ec4b6',
                            backgroundColor: 'rgba(46, 196, 182, 0.15)',
                            fill: true,
                            tension: 0.4,
                            pointRadius: 3,
                            pointBackgroundColor: '#2ec4b6',
                            borderWidth: 2,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { callback: v => v.toLocaleString(), color: '#94a3b8' } },
                            x: { grid: { display: false }, ticks: { color: '#94a3b8', maxRotation: 0 } }
                        }
                    }
                });
            }

            // Cashflow Chart
            const cf = document.getElementById('cashflowChart');
            if (cf) {
                new Chart(cf, {
                    type: 'line',
                    data: {
                        labels: @json($cashflowLabels),
                        datasets: [
                            {
                                label: 'Income',
                                data: @json($cashflowIncome),
                                borderColor: '#22c55e',
                                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                                fill: true,
                                tension: 0.4,
                                pointRadius: 3,
                                pointBackgroundColor: '#22c55e',
                                borderWidth: 2,
                            },
                            {
                                label: 'Expenses',
                                data: @json($cashflowExpenses),
                                borderColor: '#ef4444',
                                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                                fill: true,
                                tension: 0.4,
                                pointRadius: 3,
                                pointBackgroundColor: '#ef4444',
                                borderWidth: 2,
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { callback: v => v.toLocaleString(), color: '#94a3b8' } },
                            x: { grid: { display: false }, ticks: { color: '#94a3b8', maxRotation: 0 } }
                        }
                    }
                });
            }
        });
    </script>
    @endpush
</x-filament-panels::page>
