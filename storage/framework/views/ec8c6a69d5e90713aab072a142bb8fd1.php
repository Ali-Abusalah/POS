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
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($invoice->status === 'void'): ?>
        <div class="void">*** VOID ***</div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="border-top"></div>

    <div class="bold">Customer:</div>
    <div><?php echo e($invoice->customer_name); ?></div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($invoice->customer_contact): ?>
        <div><?php echo e($invoice->customer_contact); ?></div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

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
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $invoice->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <tr>
                    <td class="text-left"><?php echo e($item->item_name); ?></td>
                    <td class="text-right"><?php echo e($item->quantity); ?></td>
                    <td class="text-right"><?php echo e($primarySymbol); ?><?php echo e(number_format($item->unit_price, 2)); ?></td>
                    <td class="text-right"><?php echo e($primarySymbol); ?><?php echo e(number_format($item->line_total, 2)); ?></td>
                </tr>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </tbody>
    </table>

    <div class="border-top"></div>

    <table>
        <tr>
            <td class="text-left">Subtotal:</td>
            <td class="text-right"><?php echo e($primarySymbol); ?><?php echo e(number_format($invoice->subtotal, 2)); ?></td>
        </tr>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($secondarySymbol): ?>
        <tr>
            <td class="text-left"></td>
            <td class="text-right" style="font-size:10px;"><?php echo e(number_format($invoice->subtotal * $exchangeRate, 0)); ?> <?php echo e($secondarySymbol); ?></td>
        </tr>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <tr>
            <td class="text-left">Tax (<?php echo e($taxRate); ?>%):</td>
            <td class="text-right"><?php echo e($primarySymbol); ?><?php echo e(number_format($invoice->tax_total, 2)); ?></td>
        </tr>
        <tr class="bold">
            <td class="text-left">TOTAL:</td>
            <td class="text-right"><?php echo e($primarySymbol); ?><?php echo e(number_format($invoice->grand_total, 2)); ?></td>
        </tr>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($secondarySymbol): ?>
        <tr class="bold">
            <td class="text-left"></td>
            <td class="text-right" style="font-size:11px;"><?php echo e(number_format($invoice->grand_total * $exchangeRate, 0)); ?> <?php echo e($secondarySymbol); ?></td>
        </tr>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </table>

    <div class="border-top"></div>

    <table>
        <tr>
            <td class="text-left">Payment:</td>
            <td class="text-right"><?php echo e(ucfirst($invoice->payment_method)); ?></td>
        </tr>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($invoice->payment_method === 'cash'): ?>
        <tr>
            <td class="text-left">Amount Paid:</td>
            <td class="text-right"><?php echo e($primarySymbol); ?><?php echo e(number_format($invoice->amount_paid, 2)); ?></td>
        </tr>
        <tr>
            <td class="text-left">Change:</td>
            <td class="text-right"><?php echo e($primarySymbol); ?><?php echo e(number_format($invoice->change_amount, 2)); ?></td>
        </tr>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </table>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($invoice->status === 'void' && $invoice->void_reason): ?>
        <div class="border-top"></div>
        <div class="bold">Void Reason: <?php echo e($invoice->void_reason); ?></div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($invoice->status === 'refunded'): ?>
        <div class="border-top"></div>
        <div class="bold">REFUNDED: <?php echo e($primarySymbol); ?><?php echo e(number_format($invoice->refund_amount, 2)); ?></div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="border-top"></div>

    <div class="center" style="margin: 3mm 0;">
        <img src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data=<?php echo e(urlencode($invoiceUrl)); ?>" alt="QR Code" style="width: 30mm; height: 30mm;" />
    </div>
    <div class="center" style="font-size: 9px; margin-bottom: 2mm;">Scan to view invoice online</div>

    <div class="border-top"></div>

    <div class="footer">
        <div class="bold">Thank you for your purchase!</div>
        <div>Served by: <?php echo e($invoice->creator->name ?? 'N/A'); ?></div>
    </div>
</body>
</html>
<?php /**PATH C:\Users\ALI.A.SALAH\Desktop\pos\resources\views/invoices/thermal.blade.php ENDPATH**/ ?>