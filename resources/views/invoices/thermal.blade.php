<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Digital Creativity Tech Shop</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Courier New', monospace; font-size: 12px; width: 80mm; padding: 5mm; }
        @page { margin: 0; }
        @media print { @-webkit-keyframes uuid {} body { -webkit-animation: uuid; } }
        .center { text-align: center; }
        .bold { font-weight: bold; }
        .border-top { border-top: 1px dashed #000; margin: 3mm 0; }
        .border-bottom { border-bottom: 1px dashed #000; margin: 3mm 0; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 1mm 0; }
        .text-right { text-align: right; }
        .text-left { text-align: start; }
        .footer { margin-top: 5mm; text-align: center; font-size: 10px; }
        .void { color: red; font-size: 16px; font-weight: bold; text-align: center; margin: 3mm 0; }
    </style>
</head>
<body onload="window.print();">
    <div class="center" style="margin-bottom: 3mm;">
        <img src="/logo.png" alt="Digital Creativity Tech Shop" style="max-width: 60mm; height: auto;" />
    </div>
    @if ($invoice->status === 'void')
        <div class="void">*** VOID ***</div>
    @endif

    <div class="border-top"></div>

    <div class="bold">Customer:</div>
    <div>{{ $invoice->customer_name }}</div>
    @if ($invoice->customer_contact)
        <div>{{ $invoice->customer_contact }}</div>
    @endif

    <div class="border-top"></div>

    <table>
        <thead>
            <tr>
                <th class="text-left">Item</th>
                <th class="text-right">Qty</th>
                <th class="text-right">Price</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoice->items as $item)
                <tr>
                    <td class="text-left">{{ $item->item_name }}</td>
                    <td class="text-right">{{ $item->quantity }}</td>
                    <td class="text-right">{{ $primarySymbol }}{{ number_format($item->unit_price, 2) }}</td>
                    <td class="text-right">{{ $primarySymbol }}{{ number_format($item->line_total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="border-top"></div>

    <table>
        <tr>
            <td class="text-left">Subtotal:</td>
            <td class="text-right">{{ $primarySymbol }}{{ number_format($invoice->subtotal, 2) }}</td>
        </tr>
        @if ($secondarySymbol)
        <tr>
            <td class="text-left"></td>
            <td class="text-right" style="font-size:10px;">{{ number_format($invoice->subtotal * $exchangeRate, 0) }} {{ $secondarySymbol }}</td>
        </tr>
        @endif
        <tr>
            <td class="text-left">Tax ({{ $taxRate }}%):</td>
            <td class="text-right">{{ $primarySymbol }}{{ number_format($invoice->tax_total, 2) }}</td>
        </tr>
        <tr class="bold">
            <td class="text-left">TOTAL:</td>
            <td class="text-right">{{ $primarySymbol }}{{ number_format($invoice->grand_total, 2) }}</td>
        </tr>
        @if ($secondarySymbol)
        <tr class="bold">
            <td class="text-left"></td>
            <td class="text-right" style="font-size:11px;">{{ number_format($invoice->grand_total * $exchangeRate, 0) }} {{ $secondarySymbol }}</td>
        </tr>
        @endif
    </table>

    <div class="border-top"></div>

    <table>
        <tr>
            <td class="text-left">Payment:</td>
            <td class="text-right">{{ ucfirst($invoice->payment_method) }}</td>
        </tr>
        @if ($invoice->payment_method === 'cash')
        <tr>
            <td class="text-left">Amount Paid:</td>
            <td class="text-right">{{ $primarySymbol }}{{ number_format($invoice->amount_paid, 2) }}</td>
        </tr>
        <tr>
            <td class="text-left">Change:</td>
            <td class="text-right">{{ $primarySymbol }}{{ number_format($invoice->change_amount, 2) }}</td>
        </tr>
        @endif
    </table>

    @if ($invoice->status === 'void' && $invoice->void_reason)
        <div class="border-top"></div>
        <div class="bold">Void Reason: {{ $invoice->void_reason }}</div>
    @endif

    @if ($invoice->status === 'refunded')
        <div class="border-top"></div>
        <div class="bold">REFUNDED: {{ $primarySymbol }}{{ number_format($invoice->refund_amount, 2) }}</div>
    @endif

    <div class="border-top"></div>

    <div class="center" style="margin: 3mm 0;">
        <img src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data={{ urlencode($invoiceUrl) }}" alt="QR Code" style="width: 30mm; height: 30mm;" />
    </div>
    <div class="center" style="font-size: 9px; margin-bottom: 2mm;">Scan to view invoice online</div>

    <div class="border-top"></div>

    <div class="footer">
        <div class="bold">Thank you for your purchase!</div>
        <div>Served by: {{ $invoice->creator->name ?? 'N/A' }}</div>
    </div>
</body>
</html>
