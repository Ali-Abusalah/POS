@extends('layouts.app')
@section('page-title', 'Manage Invoices')
@push('styles')
<style>
    .sales-page { padding: 1.5rem; max-width: 1400px; margin: 0 auto; }
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; }
    .page-header h1 { font-size: 1.5rem; font-weight: 700; color: #000080; margin: 0; }
    .page-header p { font-size: 0.8125rem; color: #888888; margin: 0.25rem 0 0; }
    .btn { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1.25rem; border-radius: 8px; font-size: 0.875rem; font-weight: 600; text-decoration: none; cursor: pointer; border: none; transition: all 0.15s ease; }
    .btn-green { background: #000080; color: #ffffff; }
    .btn-green:hover { background: #000080; }
    .btn-ghost { background: transparent; color: #888888; border: 1px solid #e0e0e0; padding: 0.375rem 0.75rem; font-size: 0.8125rem; }
    .btn-ghost:hover { background: #f5f5f5; color: #000080; border-color: #cbd5e1; }
    .filter-row { display: flex; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 1rem; }
    .filter-input { border: 1.5px solid #e0e0e0; border-radius: 8px; padding: 0.5rem 0.75rem; font-size: 0.8125rem; color: #000080; background: #ffffff; outline: none; transition: border-color 0.15s; }
    .filter-input:focus { border-color: #000080; box-shadow: 0 0 0 3px rgba(0,0,128,0.1); }
    .filter-input::placeholder { color: #555555; }
    .table-container { background: #ffffff; border: 1px solid #e0e0e0; border-radius: 12px; overflow: auto; }
    table { width: 100%; border-collapse: collapse; }
    table th { text-align: left; padding: 0.75rem 1rem; font-size: 0.75rem; font-weight: 600; color: #888888; text-transform: uppercase; letter-spacing: 0.05em; background: #f5f5f5; border-bottom: 1px solid #e0e0e0; }
    table td { padding: 0.75rem 1rem; font-size: 0.875rem; color: #000080; border-bottom: 1px solid #f5f5f5; }
    table tbody tr:hover td { background: #f5f5f5; }
    .text-right { text-align: right; }
    .font-bold { font-weight: 700; }
    .link-teal { color: #000080; text-decoration: none; font-weight: 600; }
    .link-teal:hover { text-decoration: underline; }
    .badge { display: inline-flex; align-items: center; padding: 0.2rem 0.6rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; }
    .badge-paid { background: #dcfce7; color: #166534; }
    .badge-void { background: #fee2e2; color: #991b1b; }
    .badge-refunded { background: #fef3c7; color: #92400e; }
    .pagination { display: flex; justify-content: center; gap: 0.5rem; margin-top: 1rem; align-items: center; }
    .pagination a, .pagination span { display: inline-flex; align-items: center; justify-content: center; min-width: 2rem; height: 2rem; padding: 0 0.5rem; border-radius: 6px; font-size: 0.8125rem; font-weight: 500; text-decoration: none; transition: all 0.15s; }
    .pagination a { color: #555555; border: 1px solid #e0e0e0; background: #ffffff; }
    .pagination a:hover { background: #f5f5f5; border-color: #cbd5e1; }
    .pagination .active span { background: #000080; color: #ffffff; border: 1px solid #000080; }
    .pagination .disabled span { color: #cbd5e1; cursor: not-allowed; border: 1px solid #f5f5f5; }
    .page-counter { font-size: 0.8125rem; color: #888888; margin-left: 0.5rem; }
    .empty-msg { text-align: center; padding: 3rem 1rem; color: #555555; font-size: 0.9375rem; }
</style>
@endpush

@section('content')
<div class="sales-page">
    <div class="page-header">
        <div>
            <h1>Manage Invoices</h1>
            <p>View and manage all invoices</p>
        </div>
        <a href="{{ route('sales.create') }}" class="btn btn-green">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            + Add Sale
        </a>
    </div>

    <div class="filter-row">
        <input type="text" class="filter-input" id="searchInput" placeholder="Search invoice # or customer..." style="flex: 1; min-width: 200px;">
        <select class="filter-input" id="statusFilter">
            <option value="">All Status</option>
            <option value="paid">Paid</option>
            <option value="void">Void</option>
            <option value="refunded">Refunded</option>
        </select>
        <select class="filter-input" id="paymentFilter">
            <option value="">All Payments</option>
            <option value="cash">Cash</option>
            <option value="card">Card</option>
            <option value="other">Other</option>
        </select>
        <input type="date" class="filter-input" id="dateFrom" placeholder="Date From">
        <input type="date" class="filter-input" id="dateTo" placeholder="Date To">
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>INVOICES#</th>
                    <th>CUSTOMER</th>
                    <th>STATUS</th>
                    <th>DATE</th>
                    <th>PAYMENT</th>
                    <th class="text-right">AMOUNT</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoices as $inv)
                <tr>
                    <td><a href="{{ url('/invoices/' . $inv->id) }}" class="link-teal">{{ $inv->invoice_number }}</a></td>
                    <td>{{ $inv->customer->name ?? 'Walk-in' }}</td>
                    <td>
                        @if($inv->status === 'paid')
                            <span class="badge badge-paid">Paid</span>
                        @elseif($inv->status === 'void')
                            <span class="badge badge-void">Void</span>
                        @elseif($inv->status === 'refunded')
                            <span class="badge badge-refunded">Refunded</span>
                        @else
                            <span class="badge">{{ ucfirst($inv->status) }}</span>
                        @endif
                    </td>
                    <td>{{ \Carbon\Carbon::parse($inv->created_at)->format('d/m/Y') }}</td>
                    <td>{{ ucfirst($inv->payment_method ?? '-') }}</td>
                    <td class="text-right font-bold">JD {{ number_format($inv->grand_total, 2) }}</td>
                    <td class="text-right">
                        <a href="{{ url('/invoices/' . $inv->id) }}" class="btn btn-ghost">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="empty-msg">No invoices found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($invoices->hasPages())
    <div class="pagination">
        @if($invoices->onFirstPage())
            <span class="disabled"><span>&laquo;</span></span>
        @else
            <a href="{{ $invoices->previousPageUrl() }}">&laquo;</a>
        @endif

        @foreach($invoices->getUrlRange(max(1, $invoices->currentPage() - 2), min($invoices->lastPage(), $invoices->currentPage() + 2)) as $page => $url)
            @if($page == $invoices->currentPage())
                <span class="active"><span>{{ $page }}</span></span>
            @else
                <a href="{{ $url }}">{{ $page }}</a>
            @endif
        @endforeach

        @if($invoices->currentPage() < $invoices->lastPage())
            <a href="{{ $invoices->nextPageUrl() }}">&raquo;</a>
        @else
            <span class="disabled"><span>&raquo;</span></span>
        @endif
        <span class="page-counter">Page {{ $invoices->currentPage() }} of {{ $invoices->lastPage() }}</span>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
(function() {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const paymentFilter = document.getElementById('paymentFilter');
    const dateFrom = document.getElementById('dateFrom');
    const dateTo = document.getElementById('dateTo');

    let debounce;
    function applyFilters() {
        const params = new URLSearchParams();
        if (searchInput.value) params.set('q', searchInput.value);
        if (statusFilter.value) params.set('status', statusFilter.value);
        if (paymentFilter.value) params.set('payment_method', paymentFilter.value);
        if (dateFrom.value) params.set('date_from', dateFrom.value);
        if (dateTo.value) params.set('date_to', dateTo.value);
        params.set('per_page', '15');
        window.location.href = '{{ url("/sales") }}?' + params.toString();
    }

    searchInput.addEventListener('input', function() {
        clearTimeout(debounce);
        debounce = setTimeout(applyFilters, 500);
    });
    statusFilter.addEventListener('change', applyFilters);
    paymentFilter.addEventListener('change', applyFilters);
    dateFrom.addEventListener('change', applyFilters);
    dateTo.addEventListener('change', applyFilters);
})();
</script>
@endpush
