<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Digital Creativity Tech Shop</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 14px; padding: 20mm; color: #333; }
        .header { display: flex; justify-content: space-between; margin-bottom: 20px; border-bottom: 3px solid #2563eb; padding-bottom: 15px; }
        .invoice-title { font-size: 28px; font-weight: bold; color: #2563eb; }
        .invoice-details { text-align: right; }
        .invoice-details div { margin: 2px 0; }
        .section { margin: 15px 0; }
        .section-title { font-weight: bold; font-size: 16px; color: #2563eb; margin-bottom: 8px; border-bottom: 1px solid #e5e7eb; padding-bottom: 4px; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        th { background: #f3f4f6; padding: 8px 12px; text-align: start; border-bottom: 2px solid #d1d5db; font-weight: 600; }
        td { padding: 8px 12px; border-bottom: 1px solid #e5e7eb; }
        .text-right { text-align: right; }
        .totals { width: 300px; margin-left: auto; }
        .totals table { width: 100%; }
        .totals td { padding: 4px 8px; }
        .grand-total { font-size: 18px; font-weight: bold; color: #2563eb; border-top: 2px solid #2563eb; }
        .void { color: red; font-size: 24px; font-weight: bold; text-align: center; margin: 10px 0; padding: 10px; border: 2px solid red; }
        .footer { margin-top: 30px; text-align: center; color: #6b7280; font-size: 12px; border-top: 1px solid #e5e7eb; padding-top: 10px; }
        @page { margin: 0; }
        @media print { @-webkit-keyframes uuid {} body { -webkit-animation: uuid; } }
    </style>
</head>
<body onload="window.print();">
    <div class="header">
        <div>
            <img src="/logo.png" alt="Digital Creativity Tech Shop" style="max-height: 60px; margin-bottom: 10px;" />
            @if ($invoice->status === 'void')
                <div class="void">VOID</div>
            @endif
        </div>
        <div class="invoice-details">
        </div>
    </div>

    <div class="section">
        <div class="section-title">Customer Information</div>
        <table>
            <tr>
                <td style="width: 120px; font-weight: 600;">Name:</td>
                <td>{{ $invoice->customer_name }}</td>
            </tr>
            @if ($invoice->customer_contact)
            <tr>
                <td style="font-weight: 600;">Contact:</td>
                <td>{{ $invoice->customer_contact }}</td>
            </tr>
            @endif
            <tr>
                <td style="font-weight: 600;">Payment:</td>
                <td>{{ ucfirst($invoice->payment_method) }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Line Items</div>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Item</th>
                    <th>Code</th>
                    <th class="text-right">Qty</th>
                    <th class="text-right">Unit Price</th>
                    <th class="text-right">Tax</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoice->items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->item_name }}</td>
                        <td>{{ $item->item_code }}</td>
                        <td class="text-right">{{ $item->quantity }}</td>
                        <td class="text-right">{{ $primarySymbol }}{{ number_format($item->unit_price, 2) }}</td>
                        <td class="text-right">{{ $primarySymbol }}{{ number_format($item->tax_amount, 2) }}</td>
                        <td class="text-right">{{ $primarySymbol }}{{ number_format($item->line_total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="totals">
        <table>
            <tr>
                <td>Subtotal:</td>
                <td class="text-right">{{ $primarySymbol }}{{ number_format($invoice->subtotal, 2) }}</td>
            </tr>
            @if ($secondarySymbol)
            <tr>
                <td></td>
                <td class="text-right" style="font-size:12px; color:#6b7280;">{{ number_format($invoice->subtotal * $exchangeRate, 0) }} {{ $secondarySymbol }}</td>
            </tr>
            @endif
            <tr>
                <td>Tax ({{ $taxRate }}%):</td>
                <td class="text-right">{{ $primarySymbol }}{{ number_format($invoice->tax_total, 2) }}</td>
            </tr>
            <tr class="grand-total">
                <td>Grand Total:</td>
                <td class="text-right">{{ $primarySymbol }}{{ number_format($invoice->grand_total, 2) }}</td>
            </tr>
            @if ($secondarySymbol)
            <tr class="grand-total">
                <td></td>
                <td class="text-right" style="font-size:14px;">{{ number_format($invoice->grand_total * $exchangeRate, 0) }} {{ $secondarySymbol }}</td>
            </tr>
            @endif
        </table>
    </div>

    @if ($invoice->payment_method === 'cash')
    <div class="totals" style="margin-top: 10px;">
        <table>
            <tr>
                <td>Amount Paid:</td>
                <td class="text-right">{{ $primarySymbol }}{{ number_format($invoice->amount_paid, 2) }}</td>
            </tr>
            <tr>
                <td>Change:</td>
                <td class="text-right">{{ $primarySymbol }}{{ number_format($invoice->change_amount, 2) }}</td>
            </tr>
        </table>
    </div>
    @endif

    @if ($invoice->status === 'void' && $invoice->void_reason)
    <div class="section" style="margin-top: 20px;">
        <div class="section-title" style="color: #dc2626;">Void Details</div>
        <p><strong>Reason:</strong> {{ $invoice->void_reason }}</p>
    </div>
    @endif

    @if ($invoice->status === 'refunded')
    <div class="section" style="margin-top: 20px;">
        <div class="section-title" style="color: #d97706;">Refund Details</div>
        <p><strong>Refund Amount:</strong> {{ $primarySymbol }}{{ number_format($invoice->refund_amount, 2) }}</p>
    </div>
    @endif

    <div style="text-align:center; margin: 20px 0;">
        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode($invoiceUrl) }}" alt="QR Code" style="width: 120px; height: 120px;" />
        <p style="font-size: 12px; color: #6b7280; margin-top: 6px;">Scan to view invoice online</p>
    </div>

    <div class="footer">
        <p>Served by: {{ $invoice->creator->name ?? 'N/A' }}</p>
        <p>Thank you for your purchase!</p>
    </div>
</body>
</html>
