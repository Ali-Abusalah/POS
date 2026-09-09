@extends('layouts.app')
@section('page-title', $invoice->invoice_number)
@section('content')
<style>
    .inv-container { padding: 1.5rem; max-width: 900px; margin: 0 auto; }
    .inv-header { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.75rem; margin-bottom: 1.25rem; }
    .inv-header-left { display: flex; flex-direction: column; gap: 0.375rem; }
    .inv-header-left h1 { font-size: 1.5rem; font-weight: 700; color: #000080; margin: 0; }
    .inv-header-meta { display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; font-size: 0.875rem; color: #6b7280; }
    .inv-header-right { display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; }
    .inv-btn { display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.5rem 1rem; border-radius: 8px; font-size: 0.8125rem; font-weight: 500; cursor: pointer; text-decoration: none; border: 1px solid #e0e0e0; background: #ffffff; color: #374151; transition: all 0.15s; }
    .inv-btn:hover { background: #f9fafb; }
    .inv-btn-ghost { background: transparent; border: none; color: #6b7280; padding: 0.375rem 0.5rem; }
    .inv-btn-ghost:hover { color: #000080; background: #f3f4f6; }
    .inv-btn-ghost-danger { background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; padding: 0.375rem 0.75rem; border-radius: 8px; font-size: 0.8125rem; }
    .inv-btn-ghost-danger:hover { background: #fee2e2; }
    .badge { display: inline-flex; align-items: center; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; }
    .badge-paid { background: #d1fae5; color: #065f46; }
    .badge-void { background: #fee2e2; color: #991b1b; }
    .badge-refunded { background: #fef3c7; color: #92400e; }
    .badge-pending { background: #e0e7ff; color: #3730a3; }
    .info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 0.875rem; margin-bottom: 1.25rem; }
    .info-card { background: #ffffff; border: 1px solid #e0e0e0; border-radius: 12px; padding: 0.875rem 1.25rem; }
    .info-card .ic-label { font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.04em; }
    .info-card .ic-value { font-size: 1.125rem; font-weight: 700; color: #111827; margin-top: 0.25rem; }
    .info-card .ic-sub { font-size: 0.8125rem; color: #9ca3af; margin-top: 0.125rem; }
    .items-card { background: #ffffff; border: 1px solid #e0e0e0; border-radius: 12px; overflow: hidden; margin-bottom: 1.25rem; }
    .items-card-header { padding: 1rem 1.5rem; border-bottom: 1px solid #e0e0e0; }
    .items-card-header h3 { font-size: 0.9375rem; font-weight: 600; color: #111827; margin: 0; }
    .items-table { width: 100%; border-collapse: collapse; }
    .items-table th { text-align: left; padding: 0.75rem 1.5rem; font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.04em; background: #f9fafb; border-bottom: 1px solid #e0e0e0; }
    .items-table td { padding: 0.75rem 1.5rem; font-size: 0.875rem; color: #374151; border-bottom: 1px solid #f3f4f6; }
    .items-table .text-right { text-align: right; }
    .items-table .text-bold { font-weight: 600; }
    .items-card-footer { padding: 1rem 1.5rem; border-top: 1px solid #e0e0e0; display: flex; justify-content: flex-end; align-items: center; gap: 1.5rem; flex-wrap: wrap; }
    .totals-grand { font-size: 1.25rem; font-weight: 700; color: #000080; }
    .totals-detail { display: flex; gap: 1.5rem; font-size: 0.8125rem; color: #6b7280; }
    .totals-detail span { font-weight: 600; color: #374151; }
    @media (max-width: 768px) { .inv-header { flex-direction: column; align-items: flex-start; } }
</style>

<div class="inv-container">
    <div class="inv-header">
        <div class="inv-header-left">
            <a href="{{ url('/invoices') }}" class="inv-btn inv-btn-ghost" style="align-self: flex-start;">←</a>
            <h1>{{ $invoice->invoice_number }}</h1>
            <div class="inv-header-meta">
                @if($invoice->status === 'paid')
                    <span class="badge badge-paid">Paid</span>
                @elseif($invoice->status === 'void')
                    <span class="badge badge-void">Void</span>
                @elseif($invoice->status === 'refunded')
                    <span class="badge badge-refunded">Refunded</span>
                @else
                    <span class="badge badge-pending">{{ ucfirst($invoice->status) }}</span>
                @endif
                <span>{{ \Carbon\Carbon::parse($invoice->created_at)->format('F d, Y h:i A') }}</span>
            </div>
        </div>
        <div class="inv-header-right">
            <a href="{{ url('/invoices/' . $invoice->id . '/print-thermal') }}" target="_blank" class="inv-btn">Receipt</a>
            <a href="{{ url('/invoices/' . $invoice->id . '/print-a4') }}" target="_blank" class="inv-btn">A4</a>
            @if(auth()->user()->role === 'admin' && $invoice->status === 'paid')
                <button class="inv-btn inv-btn-ghost-danger" onclick="document.getElementById('voidModal').classList.add('open')">Void</button>
                <button class="inv-btn inv-btn-ghost-danger" onclick="document.getElementById('refundModal').classList.add('open')">Refund</button>
            @endif
        </div>
    </div>

    <div class="info-grid">
        <div class="info-card">
            <div class="ic-label">Customer</div>
            <div class="ic-value">{{ $invoice->customer_name }}</div>
            @if($invoice->customer_contact)
                <div class="ic-sub">{{ $invoice->customer_contact }}</div>
            @endif
        </div>
        <div class="info-card">
            <div class="ic-label">Payment Method</div>
            <div class="ic-value">{{ ucfirst($invoice->payment_method) }}</div>
        </div>
        <div class="info-card">
            <div class="ic-label">Subtotal</div>
            <div class="ic-value">${{ number_format($invoice->subtotal, 2) }}</div>
        </div>
        <div class="info-card">
            <div class="ic-label">Tax</div>
            <div class="ic-value">${{ number_format($invoice->tax_total, 2) }}</div>
        </div>
    </div>

    @if($invoice->void_reason)
        <div style="background: #fef2f2; border: 2px solid #fecaca; border-radius: 12px; padding: 1rem 1.25rem; margin-bottom: 1.25rem;">
            <div style="font-size: 0.875rem; font-weight: 600; color: #dc2626; margin-bottom: 0.25rem;">Void Reason</div>
            <div style="font-size: 0.875rem; color: #374151;">{{ $invoice->void_reason }}</div>
        </div>
    @endif

    <div class="items-card">
        <div class="items-card-header">
            <h3>Line Items</h3>
        </div>
        <div style="overflow-x: auto;">
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Code</th>
                        <th class="text-right">Qty</th>
                        <th class="text-right">Unit Price</th>
                        <th class="text-right">Tax</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                        <tr>
                            <td class="text-bold">{{ $item->item_name }}</td>
                            <td>{{ $item->item_code }}</td>
                            <td class="text-right">{{ $item->quantity }}</td>
                            <td class="text-right">${{ number_format($item->unit_price, 2) }}</td>
                            <td class="text-right">${{ number_format($item->tax_amount, 2) }}</td>
                            <td class="text-right text-bold">${{ number_format($item->line_total, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="items-card-footer">
            <div class="totals-detail">
                @if($invoice->amount_paid > 0)
                    <div>Paid: <span>${{ number_format($invoice->amount_paid, 2) }}</span></div>
                @endif
                @if($invoice->change_amount > 0)
                    <div>Change: <span>${{ number_format($invoice->change_amount, 2) }}</span></div>
                @endif
            </div>
            <div class="totals-grand">${{ number_format($invoice->grand_total, 2) }}</div>
        </div>
    </div>
</div>
@endsection