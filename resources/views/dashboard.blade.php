@extends('layouts.app')
@section('page-title', 'Dashboard')
@section('content')
<style>
    .dash-wrap { max-width: 1400px; margin: 0 auto; padding: 1.5rem; }
    .dash-title { font-size: 1.75rem; font-weight: 800; color: #000080; margin: 0 0 1.5rem; }
    .gradient-cards { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 1.25rem; }
    .gradient-card { display: flex; align-items: center; gap: 1rem; padding: 1.25rem 1.5rem; border-radius: 16px; color: #ffffff; min-height: 100px; position: relative; overflow: hidden; }
    .gradient-card::after { content: ''; position: absolute; top: -20px; right: -20px; width: 80px; height: 80px; border-radius: 50%; background: rgba(255,255,255,0.1); }
    .gc-icon { width: 48px; height: 48px; border-radius: 12px; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .gc-icon svg { width: 24px; height: 24px; fill: none; stroke: #ffffff; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
    .gc-info { position: relative; z-index: 1; }
    .gc-label { font-size: 0.8125rem; font-weight: 500; opacity: 0.9; }
    .gc-value { font-size: 1.625rem; font-weight: 800; margin-top: 0.25rem; }
    .gc-green { background: linear-gradient(135deg, #000080, #000060); }
    .gc-teal { background: linear-gradient(135deg, #4682b4, #000080); }
    .gc-orange { background: linear-gradient(135deg, #fbbf24, #f59e0b, #f59e0b); }
    .gc-pink { background: linear-gradient(135deg, #000080, #000060); }
    .progress-container { display: grid; grid-template-columns: repeat(4, 1fr); border: 1px solid #e0e0e0; border-radius: 16px; background: #ffffff; margin-bottom: 1.25rem; overflow: hidden; }
    .progress-item { padding: 1.25rem 1.5rem; border-right: 1px solid #e0e0e0; }
    .progress-item:last-child { border-right: none; }
    .pi-pct { font-size: 1.75rem; font-weight: 800; color: #000080; }
    .pi-label { font-size: 0.8125rem; font-weight: 600; color: #555555; margin-top: 0.125rem; }
    .pi-value { font-size: 0.75rem; color: #888888; margin-top: 0.25rem; }
    .pi-bar { height: 4px; background: #e0e0e0; border-radius: 9999px; margin-top: 0.75rem; overflow: hidden; }
    .pi-bar-fill { height: 100%; border-radius: 9999px; transition: width 0.6s ease; }
    .pi-bar-fill.green { background: #000080; }
    .pi-bar-fill.red { background: #dc2626; }
    .pi-bar-fill.blue { background: #000080; }
    .pi-bar-fill.amber { background: #f59e0b; }
    .bottom-grid { display: grid; grid-template-columns: 2.2fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem; }
    .chart-card { background: #ffffff; border: 1px solid #e0e0e0; border-radius: 16px; overflow: hidden; }
    .chart-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid #e0e0e0; }
    .chart-header h3 { font-size: 0.8125rem; font-weight: 700; color: #555555; text-transform: uppercase; letter-spacing: 0.04em; margin: 0; }
    .chart-body { padding: 1.5rem; height: 320px; display: flex; align-items: flex-end; gap: 3px; }
    .chart-bar-wrap { flex: 1; display: flex; flex-direction: column; align-items: center; gap: 4px; min-width: 0; }
    .chart-bar-val { font-size: 0.5625rem; color: #888888; white-space: nowrap; }
    .chart-bar { width: 100%; border-radius: 3px 3px 0 0; background: linear-gradient(to top, #000080, #b0c4de); transition: height 0.5s ease; min-height: 2px; }
    .chart-bar-date { font-size: 0.5rem; color: #888888; margin-top: 2px; }
    .buyers-card { background: #ffffff; border: 1px solid #e0e0e0; border-radius: 16px; overflow: hidden; }
    .buyers-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid #e0e0e0; display: flex; align-items: center; justify-content: space-between; }
    .buyers-header h3 { font-size: 0.875rem; font-weight: 700; color: #000080; margin: 0; }
    .buyers-list { max-height: 280px; overflow-y: auto; }
    .buyer-item { display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1.5rem; border-bottom: 1px solid #e0e0e0; }
    .buyer-item:last-child { border-bottom: none; }
    .buyer-avatar { width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.8125rem; font-weight: 700; color: #ffffff; flex-shrink: 0; }
    .buyer-info { flex: 1; min-width: 0; }
    .buyer-name { font-size: 0.8125rem; font-weight: 600; color: #000080; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .buyer-badge { font-size: 0.625rem; font-weight: 600; padding: 0.125rem 0.5rem; border-radius: 9999px; }
    .buyer-badge.paid { background: #d1fae5; color: #065f46; }
    .buyer-badge.pending { background: #fef3c7; color: #92400e; }
    .buyer-badge.void { background: #fee2e2; color: #991b1b; }
    .buyer-amount { font-size: 0.8125rem; font-weight: 700; color: #000080; white-space: nowrap; }
    .invoices-section { margin-bottom: 1.25rem; }
    .invoices-stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 1rem; }
    .inv-stat { background: #ffffff; border: 1px solid #e0e0e0; border-radius: 12px; padding: 1.25rem; text-align: center; }
    .inv-stat .is-value { font-size: 1.5rem; font-weight: 800; color: #000080; }
    .inv-stat .is-label { font-size: 0.6875rem; font-weight: 600; color: #888888; text-transform: uppercase; letter-spacing: 0.04em; margin-top: 0.25rem; }
    .invoices-table-wrap { background: #ffffff; border: 1px solid #e0e0e0; border-radius: 16px; overflow: hidden; }
    .inv-table-header { display: flex; align-items: center; justify-content: space-between; padding: 1rem 1.5rem; border-bottom: 1px solid #e0e0e0; }
    .inv-table-header h3 { font-size: 0.875rem; font-weight: 700; color: #000080; margin: 0; }
    .inv-actions { display: flex; gap: 0.5rem; }
    .inv-btn { display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.5rem 1rem; border-radius: 8px; font-size: 0.75rem; font-weight: 600; text-decoration: none; border: none; cursor: pointer; transition: all 0.15s; }
    .inv-btn-green { background: #000080; color: #ffffff; }
    .inv-btn-green:hover { background: #000080; }
    .inv-btn-blue { background: #000080; color: #ffffff; }
    .inv-btn-blue:hover { background: #000060; }
    .inv-table { width: 100%; border-collapse: collapse; }
    .inv-table th { text-align: left; padding: 0.75rem 1.5rem; font-size: 0.6875rem; font-weight: 700; color: #555555; text-transform: uppercase; letter-spacing: 0.05em; background: #f5f5f5; border-bottom: 1px solid #e0e0e0; }
    .inv-table td { padding: 0.75rem 1.5rem; font-size: 0.8125rem; color: #555555; border-bottom: 1px solid #e0e0e0; }
    .inv-table tr:last-child td { border-bottom: none; }
    .inv-table tr:hover td { background: #f5f5f5; }
    .inv-link { color: #000080; text-decoration: none; font-weight: 700; }
    .inv-link:hover { text-decoration: underline; }
    .inv-badge { display: inline-flex; align-items: center; padding: 0.25rem 0.625rem; border-radius: 9999px; font-size: 0.6875rem; font-weight: 700; }
    .inv-badge.paid { background: #d1fae5; color: #065f46; }
    .inv-badge.pending { background: #fef3c7; color: #92400e; }
    .inv-badge.void { background: #fee2e2; color: #991b1b; }
    .inv-badge.other { background: #e0e7ff; color: #3730a3; }
    .text-right { text-align: right; }
    .text-bold { font-weight: 700; }
    .empty-msg { padding: 2rem; text-align: center; color: #888888; font-size: 0.8125rem; }
    @media (max-width: 1024px) { .gradient-cards, .progress-container, .invoices-stats { grid-template-columns: repeat(2, 1fr); } .bottom-grid { grid-template-columns: 1fr; } }
    @media (max-width: 640px) { .gradient-cards, .progress-container, .invoices-stats { grid-template-columns: 1fr; } .inv-actions { flex-wrap: wrap; } }
</style>

<div class="dash-wrap">
    <h1 class="dash-title">Dashboard</h1>

    <div class="gradient-cards">
        <div class="gradient-card gc-green">
            <div class="gc-icon">
                <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
            </div>
            <div class="gc-info">
                <div class="gc-label">Today Invoices</div>
                <div class="gc-value">{{ $data['today_invoices'] }}</div>
            </div>
        </div>
        <div class="gradient-card gc-teal">
            <div class="gc-icon">
                <svg viewBox="0 0 24 24"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg>
            </div>
            <div class="gc-info">
                <div class="gc-label">This Month Invoices</div>
                <div class="gc-value">{{ $data['total_invoices'] }}</div>
            </div>
        </div>
        <div class="gradient-card gc-orange">
            <div class="gc-icon">
                <svg viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
            </div>
            <div class="gc-info">
                <div class="gc-label">Today Sales</div>
                <div class="gc-value">{{ $data['currency_symbol'] }} {{ number_format($data['today_sales'], 2) }}</div>
            </div>
        </div>
        <div class="gradient-card gc-pink">
            <div class="gc-icon">
                <svg viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
            </div>
            <div class="gc-info">
                <div class="gc-label">This Month Sales</div>
                <div class="gc-value">{{ $data['currency_symbol'] }} {{ number_format($data['month_sales'], 2) }}</div>
            </div>
        </div>
    </div>

    @php
        $incomePct = $data['target_sales'] > 0 ? min(100, round(($data['total_revenue'] / ($data['target_sales'] * 12)) * 100)) : 0;
        $expensePct = $data['total_revenue'] > 0 ? min(100, round(($data['total_tax'] / $data['total_revenue']) * 100)) : 0;
        $salesPct = $data['target_sales'] > 0 ? min(100, round(($data['month_sales'] / $data['target_sales']) * 100)) : 0;
        $netIncome = $data['total_revenue'] - $data['total_tax'];
        $netPct = $data['total_revenue'] > 0 ? min(100, round(($netIncome / $data['total_revenue']) * 100)) : 0;
    @endphp

    <div class="progress-container">
        <div class="progress-item">
            <div class="pi-pct">{{ $incomePct }}%</div>
            <div class="pi-label">August Income</div>
            <div class="pi-value">{{ $data['currency_symbol'] }} {{ number_format($data['total_revenue'], 2) }}</div>
            <div class="pi-bar"><div class="pi-bar-fill green" style="width: {{ $incomePct }}%;"></div></div>
        </div>
        <div class="progress-item">
            <div class="pi-pct">{{ $expensePct }}%</div>
            <div class="pi-label">August Expenses</div>
            <div class="pi-value">{{ $data['currency_symbol'] }} {{ number_format($data['total_tax'], 2) }}</div>
            <div class="pi-bar"><div class="pi-bar-fill red" style="width: {{ $expensePct }}%;"></div></div>
        </div>
        <div class="progress-item">
            <div class="pi-pct">{{ $salesPct }}%</div>
            <div class="pi-label">August Sales</div>
            <div class="pi-value">{{ $data['currency_symbol'] }} {{ number_format($data['month_sales'], 2) }}</div>
            <div class="pi-bar"><div class="pi-bar-fill blue" style="width: {{ $salesPct }}%;"></div></div>
        </div>
        <div class="progress-item">
            <div class="pi-pct">{{ $netPct }}%</div>
            <div class="pi-label">August Net Income</div>
            <div class="pi-value">{{ $data['currency_symbol'] }} {{ number_format($netIncome, 2) }}</div>
            <div class="pi-bar"><div class="pi-bar-fill amber" style="width: {{ $netPct }}%;"></div></div>
        </div>
    </div>

    <div class="bottom-grid">
        <div class="chart-card">
            <div class="chart-header">
                <h3>Graphical Presentation of Invoices and Sales Done in Last 30 Days.</h3>
            </div>
            <div class="chart-body">
                @php $maxVal = collect($data['series'])->max('total') ?: 1; @endphp
                @foreach($data['series'] as $point)
                    @php $h = $maxVal > 0 ? round(($point['total'] / $maxVal) * 260) : 0; @endphp
                    <div class="chart-bar-wrap">
                        <div class="chart-bar-val">{{ $data['currency_symbol'] }}{{ number_format($point['total'], 0) }}</div>
                        <div class="chart-bar" style="height: {{ max(2, $h) }}px;" title="{{ $point['date'] }}: {{ $data['currency_symbol'] }}{{ number_format($point['total'], 2) }}"></div>
                        <div class="chart-bar-date">{{ \Carbon\Carbon::parse($point['date'])->format('d') }}</div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="buyers-card">
            <div class="buyers-header">
                <h3>Recent Buyers</h3>
            </div>
            <div class="buyers-list">
                @php
                    $avatarColors = ['#000080','#000080','#8b5cf6','#f59e0b','#dc2626','#ec4899','#06b6d4','#f97316'];
                @endphp
                @forelse($data['recent_invoices'] as $idx => $inv)
                    @php $color = $avatarColors[$idx % count($avatarColors)]; @endphp
                    <div class="buyer-item">
                        <div class="buyer-avatar" style="background: {{ $color }};">{{ strtoupper(substr($inv->customer_name, 0, 1)) }}</div>
                        <div class="buyer-info">
                            <div class="buyer-name">{{ $inv->customer_name }}</div>
                        </div>
                        @if($inv->status === 'paid')
                            <span class="buyer-badge paid">Paid</span>
                        @elseif($inv->status === 'pending')
                            <span class="buyer-badge pending">Pending</span>
                        @elseif($inv->status === 'void')
                            <span class="buyer-badge void">Void</span>
                        @endif
                        <div class="buyer-amount">{{ $data['currency_symbol'] }} {{ number_format($inv->grand_total, 2) }}</div>
                    </div>
                @empty
                    <div class="empty-msg">No recent buyers</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="invoices-section">
        <div class="invoices-stats">
            <div class="inv-stat">
                <div class="is-value">{{ $data['total_invoices'] }}</div>
                <div class="is-label">TOTAL INVOICES (PAID)</div>
            </div>
            <div class="inv-stat">
                <div class="is-value">{{ $data['currency_symbol'] }} {{ number_format($data['total_revenue'], 2) }}</div>
                <div class="is-label">TOTAL REVENUE</div>
            </div>
            <div class="inv-stat">
                <div class="is-value">{{ $data['currency_symbol'] }} {{ number_format($data['total_tax'], 2) }}</div>
                <div class="is-label">TOTAL TAX COLLECTED</div>
            </div>
            <div class="inv-stat">
                <div class="is-value">{{ $data['currency_symbol'] }} {{ number_format($data['total_discount'], 2) }}</div>
                <div class="is-label">TOTAL DISCOUNT</div>
            </div>
        </div>

        <div class="invoices-table-wrap">
            <div class="inv-table-header">
                <h3>Recent Invoices</h3>
                <div class="inv-actions">
                    <a href="{{ url('/sales/create') }}" class="inv-btn inv-btn-green">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        Add Sale
                    </a>
                    <a href="{{ url('/invoices') }}" class="inv-btn inv-btn-blue">Manage Invoices</a>
                    <a href="{{ url('/pos') }}" class="inv-btn inv-btn-blue">POS</a>
                </div>
            </div>
            <div style="overflow-x: auto;">
                <table class="inv-table">
                    <thead>
                        <tr>
                            <th>INVOICES#</th>
                            <th>CUSTOMER</th>
                            <th>STATUS</th>
                            <th>DUE</th>
                            <th class="text-right">AMOUNT</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data['recent_invoices'] as $inv)
                            <tr>
                                <td><a href="{{ url('/invoices/' . $inv->id) }}" class="inv-link">{{ $inv->invoice_number }}</a></td>
                                <td>{{ $inv->customer_name }}</td>
                                <td>
                                    @if($inv->status === 'paid')
                                        <span class="inv-badge paid">Paid</span>
                                    @elseif($inv->status === 'void')
                                        <span class="inv-badge void">Void</span>
                                    @elseif($inv->status === 'pending')
                                        <span class="inv-badge pending">Pending</span>
                                    @else
                                        <span class="inv-badge other">{{ ucfirst($inv->status) }}</span>
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($inv->created_at)->format('M d, Y') }}</td>
                                <td class="text-right text-bold">{{ $data['currency_symbol'] }} {{ number_format($inv->grand_total, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="empty-msg">No invoices yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
