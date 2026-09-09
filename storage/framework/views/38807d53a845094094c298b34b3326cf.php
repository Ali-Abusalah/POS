<?php $__env->startSection('page-title', 'Customers'); ?>

<?php $__env->startSection('content'); ?>
<div style="padding: 1.5rem; max-width: 1400px; margin: 0 auto">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem">
        <h1 style="font-size: 1.5rem; font-weight: 700; color: #1e293b; margin: 0">Customers</h1>
        <button onclick="openModal()" style="background: linear-gradient(135deg, #10b981, #059669); color: #fff; border: none; padding: 0.5rem 1.25rem; border-radius: 8px; font-size: 0.8125rem; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            + New Customer
        </button>
    </div>

    <input type="text" id="searchInput" placeholder="Search name, email, phone..." style="border: 1.5px solid #e2e8f0; border-radius: 8px; padding: 0.5rem 0.75rem; font-size: 0.8125rem; color: #1e293b; background: #fff; outline: none; max-width: 360px; width: 100%; margin-bottom: 1rem">

    <div style="background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; overflow: auto">
        <table style="width: 100%; border-collapse: collapse">
            <thead>
                <tr>
                    <th style="padding: 0.625rem 1rem; font-size: 0.6875rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.06em; border-bottom: 1px solid #e5e7eb; text-align: left">Name</th>
                    <th style="padding: 0.625rem 1rem; font-size: 0.6875rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.06em; border-bottom: 1px solid #e5e7eb; text-align: left">Email</th>
                    <th style="padding: 0.625rem 1rem; font-size: 0.6875rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.06em; border-bottom: 1px solid #e5e7eb; text-align: left">Phone</th>
                    <th style="padding: 0.625rem 1rem; font-size: 0.6875rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.06em; border-bottom: 1px solid #e5e7eb; text-align: right">Balance</th>
                    <th style="padding: 0.625rem 1rem; font-size: 0.6875rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.06em; border-bottom: 1px solid #e5e7eb; text-align: right">Total Spent</th>
                    <th style="padding: 0.625rem 1rem; font-size: 0.6875rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.06em; border-bottom: 1px solid #e5e7eb; text-align: right">Invoices</th>
                    <th style="padding: 0.625rem 1rem; font-size: 0.6875rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.06em; border-bottom: 1px solid #e5e7eb; text-align: right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <tr>
                    <td style="padding: 0.625rem 1rem; font-size: 0.8125rem; color: #475569; border-bottom: 1px solid #f1f5f9; font-weight: 600"><?php echo e($customer->name); ?></td>
                    <td style="padding: 0.625rem 1rem; font-size: 0.8125rem; color: #475569; border-bottom: 1px solid #f1f5f9"><?php echo e($customer->email ?? '-'); ?></td>
                    <td style="padding: 0.625rem 1rem; font-size: 0.8125rem; color: #475569; border-bottom: 1px solid #f1f5f9"><?php echo e($customer->phone ?? '-'); ?></td>
                    <td style="padding: 0.625rem 1rem; font-size: 0.8125rem; color: <?php echo e(($customer->balance ?? 0) > 0 ? '#dc2626' : '#059669'); ?>; border-bottom: 1px solid #f1f5f9; text-align: right; font-weight: 600"><?php echo e(number_format($customer->balance ?? 0, 2)); ?></td>
                    <td style="padding: 0.625rem 1rem; font-size: 0.8125rem; color: #475569; border-bottom: 1px solid #f1f5f9; text-align: right; font-weight: 600">JD <?php echo e(number_format($customer->total_purchases ?? 0, 2)); ?></td>
                    <td style="padding: 0.625rem 1rem; font-size: 0.8125rem; color: #475569; border-bottom: 1px solid #f1f5f9; text-align: right"><?php echo e($customer->invoices_count ?? 0); ?></td>
                    <td style="padding: 0.625rem 1rem; font-size: 0.8125rem; color: #475569; border-bottom: 1px solid #f1f5f9; text-align: right">
                        <button onclick="editCustomer(<?php echo e($customer->id); ?>)" style="background: transparent; border: 1px solid #e2e8f0; color: #64748b; padding: 0.375rem 0.75rem; border-radius: 8px; font-size: 0.8125rem; font-weight: 500; cursor: pointer; margin-right: 0.375rem">Edit</button>
                        <button onclick="deleteCustomer(<?php echo e($customer->id); ?>)" style="background: transparent; border: 1px solid #e2e8f0; color: #64748b; padding: 0.375rem 0.75rem; border-radius: 8px; font-size: 0.8125rem; font-weight: 500; cursor: pointer">Delete</button>
                    </td>
                </tr>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <tr>
                    <td colspan="7" style="padding: 2rem 1rem; text-align: center; color: #94a3b8; font-size: 0.875rem">No customers found.</td>
                </tr>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($customers->hasPages()): ?>
    <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 1rem; font-size: 0.8125rem; color: #64748b">
        <span>Page <?php echo e($customers->currentPage()); ?> of <?php echo e($customers->lastPage()); ?></span>
        <div style="display: flex; gap: 0.5rem">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($customers->onFirstPage()): ?>
                <span style="background: transparent; border: 1px solid #e2e8f0; color: #cbd5e1; padding: 0.375rem 0.75rem; border-radius: 8px; font-size: 0.8125rem; font-weight: 500; cursor: not-allowed">Prev</span>
            <?php else: ?>
                <a href="<?php echo e($customers->previousPageUrl()); ?>" style="background: transparent; border: 1px solid #e2e8f0; color: #64748b; padding: 0.375rem 0.75rem; border-radius: 8px; font-size: 0.8125rem; font-weight: 500; cursor: pointer; text-decoration: none">Prev</a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($customers->currentPage() < $customers->lastPage()): ?>
                <a href="<?php echo e($customers->nextPageUrl()); ?>" style="background: transparent; border: 1px solid #e2e8f0; color: #64748b; padding: 0.375rem 0.75rem; border-radius: 8px; font-size: 0.8125rem; font-weight: 500; cursor: pointer; text-decoration: none">Next</a>
            <?php else: ?>
                <span style="background: transparent; border: 1px solid #e2e8f0; color: #cbd5e1; padding: 0.375rem 0.75rem; border-radius: 8px; font-size: 0.8125rem; font-weight: 500; cursor: not-allowed">Next</span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>

<div id="customerModal" style="position: fixed; inset: 0; background: rgba(15,23,42,0.4); z-index: 100; display: none; align-items: center; justify-content: center; padding: 1rem">
    <div style="background: #fff; border-radius: 12px; padding: 1.5rem; max-height: 90vh; overflow-y: auto; width: 100%; max-width: 480px">
        <h3 id="modalTitle" style="font-size: 1.125rem; font-weight: 700; color: #1e293b; margin: 0 0 1.25rem">New Customer</h3>
        <form id="customerForm">
            <input type="hidden" id="customerId">
            <div style="margin-bottom: 1rem">
                <label style="display: block; font-size: 0.75rem; font-weight: 600; color: #64748b; margin-bottom: 0.3rem">Name *</label>
                <input type="text" id="custName" required style="width: 100%; border: 1.5px solid #e2e8f0; border-radius: 8px; padding: 0.5rem 0.75rem; font-size: 0.8125rem; color: #1e293b; background: #fff; outline: none; box-sizing: border-box">
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem">
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 600; color: #64748b; margin-bottom: 0.3rem">Email</label>
                    <input type="email" id="custEmail" style="width: 100%; border: 1.5px solid #e2e8f0; border-radius: 8px; padding: 0.5rem 0.75rem; font-size: 0.8125rem; color: #1e293b; background: #fff; outline: none; box-sizing: border-box">
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 600; color: #64748b; margin-bottom: 0.3rem">Phone</label>
                    <input type="text" id="custPhone" style="width: 100%; border: 1.5px solid #e2e8f0; border-radius: 8px; padding: 0.5rem 0.75rem; font-size: 0.8125rem; color: #1e293b; background: #fff; outline: none; box-sizing: border-box">
                </div>
            </div>
            <div style="margin-bottom: 1rem">
                <label style="display: block; font-size: 0.75rem; font-weight: 600; color: #64748b; margin-bottom: 0.3rem">Address</label>
                <textarea id="custAddress" rows="2" style="width: 100%; border: 1.5px solid #e2e8f0; border-radius: 8px; padding: 0.5rem 0.75rem; font-size: 0.8125rem; color: #1e293b; background: #fff; outline: none; resize: vertical; box-sizing: border-box"></textarea>
            </div>
            <div style="margin-bottom: 1rem">
                <label style="display: block; font-size: 0.75rem; font-weight: 600; color: #64748b; margin-bottom: 0.3rem">Credit Balance</label>
                <input type="number" id="custBalance" step="0.01" min="0" style="width: 100%; border: 1.5px solid #e2e8f0; border-radius: 8px; padding: 0.5rem 0.75rem; font-size: 0.8125rem; color: #1e293b; background: #fff; outline: none; box-sizing: border-box">
            </div>
            <div style="margin-bottom: 1rem">
                <label style="display: block; font-size: 0.75rem; font-weight: 600; color: #64748b; margin-bottom: 0.3rem">Notes</label>
                <textarea id="custNotes" rows="2" style="width: 100%; border: 1.5px solid #e2e8f0; border-radius: 8px; padding: 0.5rem 0.75rem; font-size: 0.8125rem; color: #1e293b; background: #fff; outline: none; resize: vertical; box-sizing: border-box"></textarea>
            </div>
            <div style="margin-bottom: 1.25rem">
                <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.8125rem; font-weight: 500; color: #475569; cursor: pointer">
                    <input type="checkbox" id="custActive" checked style="width: 16px; height: 16px; accent-color: #10b981">
                    Active
                </label>
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 0.75rem; padding-top: 1rem; border-top: 1px solid #f1f5f9">
                <button type="button" onclick="closeModal()" style="background: transparent; border: 1px solid #e2e8f0; color: #64748b; padding: 0.375rem 0.75rem; border-radius: 8px; font-size: 0.8125rem; font-weight: 500; cursor: pointer">Cancel</button>
                <button type="button" onclick="saveCustomer()" style="background: linear-gradient(135deg, #10b981, #059669); color: #fff; border: none; padding: 0.5rem 1.25rem; border-radius: 8px; font-size: 0.8125rem; font-weight: 600; cursor: pointer">Save Customer</button>
            </div>
        </form>
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

    const searchInput = document.getElementById('searchInput');
    let debounce;
    searchInput.addEventListener('input', function() {
        clearTimeout(debounce);
        debounce = setTimeout(function() {
            const q = searchInput.value.trim();
            const url = q ? '<?php echo e(url("/customers")); ?>?q=' + encodeURIComponent(q) : '<?php echo e(url("/customers")); ?>';
            window.location.href = url;
        }, 500);
    });

    window.openModal = function(cust) {
        document.getElementById('modalTitle').textContent = cust ? 'Edit Customer' : 'New Customer';
        document.getElementById('customerId').value = cust ? cust.id : '';
        document.getElementById('custName').value = cust ? cust.name : '';
        document.getElementById('custEmail').value = cust ? (cust.email || '') : '';
        document.getElementById('custPhone').value = cust ? (cust.phone || '') : '';
        document.getElementById('custAddress').value = cust ? (cust.address || '') : '';
        document.getElementById('custBalance').value = cust ? (cust.balance || '') : '';
        document.getElementById('custNotes').value = cust ? (cust.notes || '') : '';
        document.getElementById('custActive').checked = cust ? (cust.is_active !== false) : true;
        document.getElementById('customerModal').style.display = 'flex';
    };

    window.closeModal = function() {
        document.getElementById('customerModal').style.display = 'none';
    };

    window.editCustomer = function(id) {
        apiFetch('/api/customers/' + id).then(function(r) { return r.json(); }).then(function(data) {
            if (data) openModal(data);
        });
    };

    window.deleteCustomer = async function(id) {
        if (!confirm('Are you sure you want to delete this customer?')) return;
        try {
            const res = await apiFetch('/api/customers/' + id, { method: 'DELETE' });
            const data = await res.json();
            if (data.success) location.reload();
            else alert(data.error || 'Error deleting customer');
        } catch (e) { alert('Error deleting customer'); }
    };

    window.saveCustomer = async function() {
        const id = document.getElementById('customerId').value;
        const payload = {
            name: document.getElementById('custName').value,
            email: document.getElementById('custEmail').value || null,
            phone: document.getElementById('custPhone').value || null,
            address: document.getElementById('custAddress').value || null,
            balance: parseFloat(document.getElementById('custBalance').value) || 0,
            notes: document.getElementById('custNotes').value || null,
            is_active: document.getElementById('custActive').checked,
        };
        try {
            const url = id ? '/api/customers/' + id : '/api/customers';
            const res = await apiFetch(url, { method: 'POST', body: JSON.stringify(payload) });
            const data = await res.json();
            if (data.customer || data.success) { closeModal(); location.reload(); }
            else alert(data.error || 'Error saving customer');
        } catch (e) { alert('Error saving customer'); }
    };

    document.getElementById('customerModal').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });
})();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ALI.A.SALAH\Desktop\pos\resources\views/customers/index.blade.php ENDPATH**/ ?>