<?php $__env->startSection('page-title', 'Items'); ?>
<?php $__env->startPush('styles'); ?>
<style>
    .items-page { padding: 1.5rem; max-width: 1400px; margin: 0 auto; }
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; }
    .page-header h1 { font-size: 1.5rem; font-weight: 700; color: #1e293b; margin: 0; }
    .btn { display: inline-flex; align-items: center; gap: 0.5rem; border-radius: 8px; font-weight: 600; text-decoration: none; cursor: pointer; border: none; transition: all 0.15s ease; }
    .btn-primary { background: linear-gradient(135deg, #3b82f6, #2563eb); color: #fff; padding: 0.5rem 1.25rem; font-size: 0.875rem; }
    .btn-primary:hover { opacity: 0.9; }
    .btn-ghost { background: transparent; color: #64748b; border: 1px solid #e2e8f0; padding: 0.375rem 0.75rem; font-size: 0.8125rem; }
    .btn-ghost:hover { background: #f1f5f9; color: #1e293b; border-color: #cbd5e1; }
    .btn-danger-ghost { background: transparent; color: #ef4444; border: 1px solid #fecaca; padding: 0.375rem 0.75rem; font-size: 0.8125rem; }
    .btn-danger-ghost:hover { background: #fef2f2; }
    .filter-row { display: flex; gap: 0.5rem; margin-bottom: 1rem; flex-wrap: wrap; }
    .filter-input { border: 1.5px solid #e2e8f0; border-radius: 8px; padding: 0.5rem 0.75rem; font-size: 0.8125rem; color: #1e293b; background: #fff; outline: none; transition: border-color 0.15s; }
    .filter-input:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.1); }
    .filter-input::placeholder { color: #94a3b8; }
    .table-container { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; overflow: auto; }
    table { width: 100%; border-collapse: collapse; }
    table th { text-align: left; padding: 0.75rem 1rem; font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; background: #f8fafc; border-bottom: 1px solid #e5e7eb; }
    table td { padding: 0.75rem 1rem; font-size: 0.875rem; color: #1e293b; border-bottom: 1px solid #f1f5f9; }
    table tbody tr:hover td { background: #f8fafc; }
    .text-right { text-align: right; }
    .text-center { text-align: center; }
    .font-bold { font-weight: 700; }
    .item-image { width: 36px; height: 36px; border-radius: 6px; object-fit: cover; background: #f1f5f9; }
    .badge { display: inline-flex; align-items: center; padding: 0.2rem 0.6rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; }
    .badge-active { background: #dcfce7; color: #166534; }
    .badge-inactive { background: #fee2e2; color: #991b1b; }
    .badge-category { background: #f1f5f9; color: #475569; }
    .stock-low { color: #ef4444; font-weight: 600; }
    .stock-ok { color: #1e293b; }
    .pagination { display: flex; justify-content: center; gap: 0.5rem; margin-top: 1rem; align-items: center; }
    .pagination a, .pagination span { display: inline-flex; align-items: center; justify-content: center; min-width: 2rem; height: 2rem; padding: 0 0.5rem; border-radius: 6px; font-size: 0.8125rem; font-weight: 500; text-decoration: none; transition: all 0.15s; }
    .pagination a { color: #475569; border: 1px solid #e2e8f0; background: #fff; }
    .pagination a:hover { background: #f1f5f9; border-color: #cbd5e1; }
    .pagination .active span { background: #3b82f6; color: #fff; border: 1px solid #3b82f6; }
    .pagination .disabled span { color: #cbd5e1; cursor: not-allowed; border: 1px solid #f1f5f9; }
    .page-counter { font-size: 0.8125rem; color: #64748b; margin-left: 0.5rem; }
    .empty-msg { text-align: center; padding: 3rem 1rem; color: #94a3b8; font-size: 0.9375rem; }

    .modal-overlay { position: fixed; inset: 0; background: rgba(15,23,42,0.4); z-index: 100; display: none; align-items: center; justify-content: center; padding: 1rem; }
    .modal-overlay.open { display: flex; }
    .modal { background: #fff; border-radius: 12px; padding: 1.5rem; width: 560px; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 60px rgba(0,0,0,0.15); }
    .modal-title { font-size: 1.125rem; font-weight: 700; color: #1e293b; margin: 0 0 1.25rem; }
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; }
    .form-group { display: flex; flex-direction: column; }
    .form-group label { font-size: 0.8125rem; font-weight: 600; color: #475569; margin-bottom: 0.375rem; }
    .form-group input, .form-group select, .form-group textarea { padding: 0.5rem 0.75rem; border: 1.5px solid #e2e8f0; border-radius: 8px; font-size: 0.8125rem; color: #1e293b; background: #fff; outline: none; transition: border-color 0.15s; }
    .form-group input:focus, .form-group select:focus, .form-group textarea:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.1); }
    .form-group textarea { resize: vertical; min-height: 60px; }
    .form-full { grid-column: 1 / -1; }
    .form-check { display: flex; align-items: center; gap: 0.5rem; }
    .form-check input[type="checkbox"] { width: auto; accent-color: #3b82f6; }
    .form-check label { margin: 0; font-size: 0.8125rem; color: #475569; }
    .modal-actions { display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 1.25rem; padding-top: 1rem; border-top: 1px solid #e5e7eb; }
    .btn-cancel { background: transparent; color: #64748b; border: 1px solid #e2e8f0; padding: 0.5rem 1.25rem; font-size: 0.875rem; border-radius: 8px; cursor: pointer; }
    .btn-cancel:hover { background: #f1f5f9; }
    .btn-save { background: linear-gradient(135deg, #3b82f6, #2563eb); color: #fff; padding: 0.5rem 1.5rem; font-size: 0.875rem; border-radius: 8px; cursor: pointer; border: none; font-weight: 600; }
    .btn-save:hover { opacity: 0.9; }
    @media (max-width: 640px) { .form-grid { grid-template-columns: 1fr; } }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="items-page">
    <div class="page-header">
        <h1>Items</h1>
        <button class="btn btn-primary" onclick="openModal()">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            + New Item
        </button>
    </div>

    <div class="filter-row">
        <input type="text" class="filter-input" id="searchInput" placeholder="Search name, code, barcode..." style="flex: 1; min-width: 200px;">
        <select class="filter-input" id="categoryFilter">
            <option value="">All Categories</option>
            <option value="general">General</option>
            <option value="electronics">Electronics</option>
            <option value="groceries">Groceries</option>
            <option value="clothing">Clothing</option>
            <option value="furniture">Furniture</option>
            <option value="stationery">Stationery</option>
        </select>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th class="text-right">Price</th>
                    <th class="text-right">Stock</th>
                    <th>Status</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <tr>
                    <td>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->image): ?>
                            <img src="<?php echo e(asset('storage/' . $item->image)); ?>" alt="" class="item-image">
                        <?php else: ?>
                            <div class="item-image" style="display:flex;align-items:center;justify-content:center;font-size:0.75rem;color:#94a3b8;">N/A</div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </td>
                    <td class="font-bold"><?php echo e($item->code); ?></td>
                    <td><?php echo e($item->name); ?></td>
                    <td><span class="badge badge-category"><?php echo e(ucfirst($item->category ?? 'general')); ?></span></td>
                    <td class="text-right font-bold">JD <?php echo e(number_format($item->pre_tax_price, 2)); ?></td>
                    <td class="text-right <?php echo e(($item->quantity ?? 0) <= ($item->low_stock_threshold ?? 5) ? 'stock-low' : 'stock-ok'); ?>"><?php echo e($item->quantity ?? 0); ?></td>
                    <td>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->is_active): ?>
                            <span class="badge badge-active">Active</span>
                        <?php else: ?>
                            <span class="badge badge-inactive">Inactive</span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </td>
                    <td class="text-right">
                        <button class="btn btn-ghost" onclick="editItem(<?php echo e($item->id); ?>)">Edit</button>
                        <button class="btn btn-danger-ghost" onclick="deleteItem(<?php echo e($item->id); ?>)">Delete</button>
                    </td>
                </tr>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <tr>
                    <td colspan="8" class="empty-msg">No items found.</td>
                </tr>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($items->hasPages()): ?>
    <div class="pagination">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($items->onFirstPage()): ?>
            <span class="disabled"><span>&laquo;</span></span>
        <?php else: ?>
            <a href="<?php echo e($items->previousPageUrl()); ?>">&laquo;</a>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $items->getUrlRange(max(1, $items->currentPage() - 2), min($items->lastPage(), $items->currentPage() + 2)); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($page == $items->currentPage()): ?>
                <span class="active"><span><?php echo e($page); ?></span></span>
            <?php else: ?>
                <a href="<?php echo e($url); ?>"><?php echo e($page); ?></a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($items->currentPage() < $items->lastPage()): ?>
            <a href="<?php echo e($items->nextPageUrl()); ?>">&raquo;</a>
        <?php else: ?>
            <span class="disabled"><span>&raquo;</span></span>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <span class="page-counter">Page <?php echo e($items->currentPage()); ?> of <?php echo e($items->lastPage()); ?></span>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>

<div class="modal-overlay" id="itemModal">
    <div class="modal">
        <h3 class="modal-title" id="modalTitle">New Item</h3>
        <form id="itemForm">
            <input type="hidden" id="itemId">
            <div class="form-grid">
                <div class="form-group">
                    <label for="itemName">Name*</label>
                    <input type="text" id="itemName" required>
                </div>
                <div class="form-group">
                    <label for="itemCode">Code*</label>
                    <input type="text" id="itemCode" required>
                </div>
                <div class="form-group">
                    <label for="itemBarcode">Barcode</label>
                    <input type="text" id="itemBarcode">
                </div>
                <div class="form-group">
                    <label for="itemCategory">Category</label>
                    <select id="itemCategory">
                        <option value="general">General</option>
                        <option value="electronics">Electronics</option>
                        <option value="groceries">Groceries</option>
                        <option value="clothing">Clothing</option>
                        <option value="furniture">Furniture</option>
                        <option value="stationery">Stationery</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="itemUnit">Unit</label>
                    <input type="text" id="itemUnit" placeholder="e.g. pcs, kg">
                </div>
                <div class="form-group">
                    <label for="itemPrice">Price (pre-tax)*</label>
                    <input type="number" id="itemPrice" step="0.01" min="0" required>
                </div>
                <div class="form-group">
                    <label for="itemQty">Stock Quantity*</label>
                    <input type="number" id="itemQty" min="0" required>
                </div>
                <div class="form-group">
                    <label for="itemThreshold">Low Stock Threshold</label>
                    <input type="number" id="itemThreshold" min="0" value="5">
                </div>
                <div class="form-group form-full">
                    <label for="itemImage">Image</label>
                    <input type="file" id="itemImage" accept="image/*">
                </div>
                <div class="form-group form-full">
                    <label for="itemDesc">Description</label>
                    <textarea id="itemDesc" rows="3"></textarea>
                </div>
                <div class="form-group form-full">
                    <div class="form-check">
                        <input type="checkbox" id="itemActive" checked>
                        <label for="itemActive">Active</label>
                    </div>
                </div>
            </div>
        </form>
        <div class="modal-actions">
            <button class="btn-cancel" onclick="closeModal()">Cancel</button>
            <button class="btn-save" onclick="saveItem()">Save</button>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
(function() {
    const token = document.querySelector('meta[name="csrf-token"]').content;
    function apiFetch(url, opts = {}) {
        const h = opts.headers || {};
        h['X-CSRF-TOKEN'] = token;
        h['Accept'] = 'application/json';
        if (opts.body && typeof opts.body === 'string') h['Content-Type'] = 'application/json';
        return fetch(url, { ...opts, headers: h, credentials: 'same-origin' });
    }

    window.openModal = function(item) {
        document.getElementById('modalTitle').textContent = item ? 'Edit: ' + item.name : 'New Item';
        document.getElementById('itemId').value = item ? item.id : '';
        document.getElementById('itemName').value = item ? item.name : '';
        document.getElementById('itemCode').value = item ? item.code : '';
        document.getElementById('itemBarcode').value = item ? (item.barcode || '') : '';
        document.getElementById('itemCategory').value = item ? (item.category || 'general') : 'general';
        document.getElementById('itemUnit').value = item ? (item.unit || '') : '';
        document.getElementById('itemPrice').value = item ? item.pre_tax_price : '';
        document.getElementById('itemQty').value = item ? item.quantity : '';
        document.getElementById('itemThreshold').value = item ? (item.low_stock_threshold || 5) : 5;
        document.getElementById('itemDesc').value = item ? (item.description || '') : '';
        document.getElementById('itemActive').checked = item ? !!item.is_active : true;
        document.getElementById('itemModal').classList.add('open');
    };

    window.closeModal = function() {
        document.getElementById('itemModal').classList.remove('open');
    };

    window.editItem = function(id) {
        apiFetch('/api/items/' + id).then(function(r) { return r.json(); }).then(function(data) {
            if (data) openModal(data);
        });
    };

    window.deleteItem = async function(id) {
        if (!confirm('Are you sure you want to delete this item?')) return;
        try {
            const res = await apiFetch('/api/items/' + id, { method: 'DELETE' });
            const data = await res.json();
            if (data.success) location.reload();
            else alert(data.error || 'Error deleting item');
        } catch (e) { alert('Error deleting item'); }
    };

    window.saveItem = async function() {
        const id = document.getElementById('itemId').value;
        const payload = {
            name: document.getElementById('itemName').value,
            code: document.getElementById('itemCode').value,
            barcode: document.getElementById('itemBarcode').value || null,
            category: document.getElementById('itemCategory').value,
            unit: document.getElementById('itemUnit').value || null,
            pre_tax_price: parseFloat(document.getElementById('itemPrice').value),
            quantity: parseInt(document.getElementById('itemQty').value),
            low_stock_threshold: parseInt(document.getElementById('itemThreshold').value) || 5,
            description: document.getElementById('itemDesc').value || null,
            is_active: document.getElementById('itemActive').checked ? 1 : 0,
        };
        try {
            const url = id ? '/api/items/' + id : '/api/items';
            const res = await apiFetch(url, { method: 'POST', body: JSON.stringify(payload) });
            const data = await res.json();
            if (data.item || data.success) { closeModal(); location.reload(); }
            else alert(data.error || 'Validation error. Please check your inputs.');
        } catch (e) { alert('Error saving item'); }
    };

    document.getElementById('itemModal').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });
})();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ALI.A.SALAH\Desktop\pos\resources\views/items/index.blade.php ENDPATH**/ ?>