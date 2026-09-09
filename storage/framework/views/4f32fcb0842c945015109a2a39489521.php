<?php $__env->startSection('page-title', 'Manage Invoices'); ?>
<?php $__env->startPush('styles'); ?>
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
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="sales-page">
    <div class="page-header">
        <div>
            <h1>Manage Invoices</h1>
            <p>View and manage all invoices</p>
        </div>
        <a href="<?php echo e(route('sales.create')); ?>" class="btn btn-green">
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
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <tr>
                    <td><a href="<?php echo e(url('/invoices/' . $inv->id)); ?>" class="link-teal"><?php echo e($inv->invoice_number); ?></a></td>
                    <td><?php echo e($inv->customer->name ?? 'Walk-in'); ?></td>
                    <td>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($inv->status === 'paid'): ?>
                            <span class="badge badge-paid">Paid</span>
                        <?php elseif($inv->status === 'void'): ?>
                            <span class="badge badge-void">Void</span>
                        <?php elseif($inv->status === 'refunded'): ?>
                            <span class="badge badge-refunded">Refunded</span>
                        <?php else: ?>
                            <span class="badge"><?php echo e(ucfirst($inv->status)); ?></span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </td>
                    <td><?php echo e(\Carbon\Carbon::parse($inv->created_at)->format('d/m/Y')); ?></td>
                    <td><?php echo e(ucfirst($inv->payment_method ?? '-')); ?></td>
                    <td class="text-right font-bold">JD <?php echo e(number_format($inv->grand_total, 2)); ?></td>
                    <td class="text-right">
                        <a href="<?php echo e(url('/invoices/' . $inv->id)); ?>" class="btn btn-ghost">View</a>
                    </td>
                </tr>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <tr>
                    <td colspan="7" class="empty-msg">No invoices found.</td>
                </tr>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($invoices->hasPages()): ?>
    <div class="pagination">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($invoices->onFirstPage()): ?>
            <span class="disabled"><span>&laquo;</span></span>
        <?php else: ?>
            <a href="<?php echo e($invoices->previousPageUrl()); ?>">&laquo;</a>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $invoices->getUrlRange(max(1, $invoices->currentPage() - 2), min($invoices->lastPage(), $invoices->currentPage() + 2)); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($page == $invoices->currentPage()): ?>
                <span class="active"><span><?php echo e($page); ?></span></span>
            <?php else: ?>
                <a href="<?php echo e($url); ?>"><?php echo e($page); ?></a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($invoices->currentPage() < $invoices->lastPage()): ?>
            <a href="<?php echo e($invoices->nextPageUrl()); ?>">&raquo;</a>
        <?php else: ?>
            <span class="disabled"><span>&raquo;</span></span>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <span class="page-counter">Page <?php echo e($invoices->currentPage()); ?> of <?php echo e($invoices->lastPage()); ?></span>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
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
        window.location.href = '<?php echo e(url("/sales")); ?>?' + params.toString();
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
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ALI.A.SALAH\Desktop\pos\resources\views/sales/index.blade.php ENDPATH**/ ?>