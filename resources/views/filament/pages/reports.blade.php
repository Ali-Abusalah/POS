@push('styles')
<style>
.rpt-layout { display: flex; flex-direction: column; gap: 1rem; }
.rpt-filter { display: flex; flex-wrap: wrap; align-items: flex-end; gap: 0.75rem; }
.rpt-stats { display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.75rem; }
@media (min-width: 768px) { .rpt-stats { grid-template-columns: repeat(4, 1fr); } }
.rpt-tables { display: grid; grid-template-columns: 1fr; gap: 1rem; }
@media (min-width: 1024px) { .rpt-tables { grid-template-columns: repeat(2, 1fr); } }
.rpt-table-full { grid-column: 1 / -1; }
</style>
@endpush

<x-ui.layout title="Sales Reports">
    <div class="rpt-layout">
        {{-- Filter --}}
        <div class="ui-card">
            <div class="ui-card-body">
                <form wire:submit="loadReport" class="rpt-filter">
                    <div>
                        <label class="ui-input-label">From</label>
                        <input type="date" wire:model="date_from" class="ui-input" />
                    </div>
                    <div>
                        <label class="ui-input-label">To</label>
                        <input type="date" wire:model="date_to" class="ui-input" />
                    </div>
                    <button type="submit" class="ui-btn ui-btn-primary">Generate Report</button>
                </form>
            </div>
        </div>

        {{-- Stats --}}
        <div class="rpt-stats">
            <div class="ui-card ui-stat">
                <div class="ui-stat-label">Invoices</div>
                <div class="ui-stat-value accent">{{ number_format($summary['total_invoices'] ?? 0) }}</div>
            </div>
            <div class="ui-card ui-stat">
                <div class="ui-stat-label">Revenue</div>
                <div class="ui-stat-value success">${{ number_format($summary['total_revenue'] ?? 0, 2) }}</div>
            </div>
            <div class="ui-card ui-stat">
                <div class="ui-stat-label">Tax Collected</div>
                <div class="ui-stat-value">${{ number_format($summary['total_tax'] ?? 0, 2) }}</div>
            </div>
            <div class="ui-card ui-stat">
                <div class="ui-stat-label">Voided</div>
                <div class="ui-stat-value danger">{{ number_format($summary['void_count'] ?? 0) }}</div>
            </div>
        </div>

        {{-- Tables --}}
        <div class="rpt-tables">
            <div class="ui-card">
                <div class="ui-card-body" style="padding-top:0;">
                    <div style="padding-top:1.25rem;">
                        <h3 class="ui-section-title" style="margin-bottom:0.75rem;">Daily Sales</h3>
                    </div>
                    @if (empty($dailySales))
                        <div class="ui-table-empty">No data for selected period</div>
                    @else
                        <table class="ui-table">
                            <thead><tr><th>Date</th><th>Invoices</th><th class="r">Revenue</th></tr></thead>
                            <tbody>
                                @foreach ($dailySales as $day)
                                    <tr>
                                        <td>{{ $day['date'] }}</td>
                                        <td>{{ $day['invoice_count'] }}</td>
                                        <td class="r bold">${{ number_format($day['total'], 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>

            <div class="ui-card">
                <div class="ui-card-body" style="padding-top:0;">
                    <div style="padding-top:1.25rem;">
                        <h3 class="ui-section-title" style="margin-bottom:0.75rem;">Top Selling Items</h3>
                    </div>
                    @if (empty($topItems))
                        <div class="ui-table-empty">No data for selected period</div>
                    @else
                        <table class="ui-table">
                            <thead><tr><th>Item</th><th class="r">Qty</th><th class="r">Revenue</th></tr></thead>
                            <tbody>
                                @foreach ($topItems as $item)
                                    <tr>
                                        <td>{{ $item['item_name'] }}</td>
                                        <td class="r">{{ $item['total_qty'] }}</td>
                                        <td class="r bold">${{ number_format($item['total_revenue'], 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>

            <div class="ui-card rpt-table-full">
                <div class="ui-card-body" style="padding-top:0;">
                    <div style="padding-top:1.25rem;">
                        <h3 class="ui-section-title" style="margin-bottom:0.75rem;">Top Customers</h3>
                    </div>
                    @if (empty($topCustomers))
                        <div class="ui-table-empty">No data for selected period</div>
                    @else
                        <table class="ui-table">
                            <thead><tr><th>Customer</th><th class="r">Invoices</th><th class="r">Total Spent</th></tr></thead>
                            <tbody>
                                @foreach ($topCustomers as $customer)
                                    <tr>
                                        <td>{{ $customer['customer_name'] }}</td>
                                        <td class="r">{{ $customer['invoice_count'] }}</td>
                                        <td class="r bold">${{ number_format($customer['total_spent'], 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-ui.layout>
