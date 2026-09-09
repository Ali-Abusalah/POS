<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Barcode Labels</title>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; }
        @page { margin: 5mm; size: auto; }
        .label-container {
            display: flex;
            flex-wrap: wrap;
            gap: 3mm;
            padding: 5mm;
        }
        .barcode-label {
            border: 1px solid #ccc;
            padding: 3mm;
            text-align: center;
            width: 50mm;
            height: 25mm;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            page-break-inside: avoid;
        }
        .barcode-label .item-name {
            font-size: 8px;
            font-weight: bold;
            margin-bottom: 1mm;
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .barcode-label .item-code {
            font-size: 7px;
            color: #666;
            margin-bottom: 1mm;
        }
        .barcode-label .item-price {
            font-size: 9px;
            font-weight: bold;
            color: #16a34a;
            margin-top: 1mm;
        }
        .barcode-label svg {
            max-width: 45mm;
            max-height: 12mm;
        }
        @media print {
            .label-container { padding: 0; gap: 2mm; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body onload="window.print();">
    <div class="no-print" style="padding: 10px; background: #f0f0f0; margin-bottom: 10px;">
        <strong>Barcode Labels</strong> — {{ $items->count() }} items × {{ $copies }} copies = {{ $items->count() * $copies }} labels
    </div>

    <div class="label-container">
        @foreach ($items as $item)
            @for ($i = 0; $i < $copies; $i++)
                <div class="barcode-label">
                    <div class="item-name">{{ $item->name }}</div>
                    <div class="item-code">{{ $item->code }}</div>
                    <svg class="barcode-svg" data-barcode="{{ $item->barcode ?? $item->code }}"></svg>
                    <div class="item-price">JD {{ number_format($item->pre_tax_price, 2) }}</div>
                </div>
            @endfor
        @endforeach
    </div>

    <script>
        JsBarcode('.barcode-svg')
            .init();
    </script>
</body>
</html>
