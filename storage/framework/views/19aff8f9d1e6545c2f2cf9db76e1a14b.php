<?php $__env->startSection('page-title', 'Settings'); ?>

<?php $__env->startSection('content'); ?>
<div style="padding: 1.5rem; max-width: 800px; margin: 0 auto">
    <h1 style="font-size: 1.5rem; font-weight: 700; color: #1e293b; margin: 0; margin-bottom: 1.25rem">System Settings</h1>

    <div style="background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; margin-bottom: 1.25rem; overflow: hidden">
        <div style="padding: 0.875rem 1.25rem; border-bottom: 1px solid #e5e7eb; font-weight: 700; font-size: 0.875rem; color: #1e293b">Tax & Currency</div>
        <div style="padding: 1.25rem">
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 1rem; padding: 0.5rem 0">
                <label style="font-size: 0.8125rem; font-weight: 600; color: #64748b; flex-shrink: 0; width: 180px">Tax Rate (%)</label>
                <input type="number" id="taxRate" step="0.01" min="0" max="100" value="<?php echo e($settings['tax_rate'] ?? 16); ?>" style="border: 1.5px solid #e2e8f0; border-radius: 8px; padding: 0.5rem 0.75rem; font-size: 0.8125rem; color: #1e293b; background: #fff; outline: none; flex: 1; max-width: 320px; text-align: right; box-sizing: border-box">
            </div>
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 1rem; padding: 0.5rem 0">
                <label style="font-size: 0.8125rem; font-weight: 600; color: #64748b; flex-shrink: 0; width: 180px">Primary Currency</label>
                <input type="text" id="primaryCurrency" value="<?php echo e($settings['primary_currency'] ?? 'USD'); ?>" style="border: 1.5px solid #e2e8f0; border-radius: 8px; padding: 0.5rem 0.75rem; font-size: 0.8125rem; color: #1e293b; background: #fff; outline: none; flex: 1; max-width: 320px; text-align: right; box-sizing: border-box">
            </div>
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 1rem; padding: 0.5rem 0">
                <label style="font-size: 0.8125rem; font-weight: 600; color: #64748b; flex-shrink: 0; width: 180px">Primary Symbol</label>
                <input type="text" id="primarySymbol" value="<?php echo e($settings['primary_symbol'] ?? 'JD'); ?>" style="border: 1.5px solid #e2e8f0; border-radius: 8px; padding: 0.5rem 0.75rem; font-size: 0.8125rem; color: #1e293b; background: #fff; outline: none; flex: 1; max-width: 320px; text-align: right; box-sizing: border-box">
            </div>
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 1rem; padding: 0.5rem 0">
                <label style="font-size: 0.8125rem; font-weight: 600; color: #64748b; flex-shrink: 0; width: 180px">Secondary Currency</label>
                <input type="text" id="secondaryCurrency" value="<?php echo e($settings['secondary_currency'] ?? ''); ?>" style="border: 1.5px solid #e2e8f0; border-radius: 8px; padding: 0.5rem 0.75rem; font-size: 0.8125rem; color: #1e293b; background: #fff; outline: none; flex: 1; max-width: 320px; text-align: right; box-sizing: border-box">
            </div>
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 1rem; padding: 0.5rem 0">
                <label style="font-size: 0.8125rem; font-weight: 600; color: #64748b; flex-shrink: 0; width: 180px">Secondary Symbol</label>
                <input type="text" id="secondarySymbol" value="<?php echo e($settings['secondary_symbol'] ?? ''); ?>" style="border: 1.5px solid #e2e8f0; border-radius: 8px; padding: 0.5rem 0.75rem; font-size: 0.8125rem; color: #1e293b; background: #fff; outline: none; flex: 1; max-width: 320px; text-align: right; box-sizing: border-box">
            </div>
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 1rem; padding: 0.5rem 0">
                <label style="font-size: 0.8125rem; font-weight: 600; color: #64748b; flex-shrink: 0; width: 180px">Exchange Rate</label>
                <input type="number" id="exchangeRate" step="0.0001" min="0" value="<?php echo e($settings['exchange_rate'] ?? 1); ?>" style="border: 1.5px solid #e2e8f0; border-radius: 8px; padding: 0.5rem 0.75rem; font-size: 0.8125rem; color: #1e293b; background: #fff; outline: none; flex: 1; max-width: 320px; text-align: right; box-sizing: border-box">
            </div>
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 1rem; padding: 0.5rem 0">
                <label style="font-size: 0.8125rem; font-weight: 600; color: #64748b; flex-shrink: 0; width: 180px">Site URL</label>
                <input type="url" id="siteUrl" value="<?php echo e($settings['site_url'] ?? ''); ?>" placeholder="https://example.com" style="border: 1.5px solid #e2e8f0; border-radius: 8px; padding: 0.5rem 0.75rem; font-size: 0.8125rem; color: #1e293b; background: #fff; outline: none; flex: 1; max-width: 320px; text-align: right; box-sizing: border-box">
            </div>
        </div>
    </div>

    <div style="background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; margin-bottom: 1.25rem; overflow: hidden">
        <div style="padding: 0.875rem 1.25rem; border-bottom: 1px solid #e5e7eb; font-weight: 700; font-size: 0.875rem; color: #1e293b">Targets</div>
        <div style="padding: 1.25rem">
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 1rem; padding: 0.5rem 0">
                <label style="font-size: 0.8125rem; font-weight: 600; color: #64748b; flex-shrink: 0; width: 180px">Target Income</label>
                <input type="number" id="targetIncome" step="0.01" min="0" value="<?php echo e($settings['target_income'] ?? 0); ?>" style="border: 1.5px solid #e2e8f0; border-radius: 8px; padding: 0.5rem 0.75rem; font-size: 0.8125rem; color: #1e293b; background: #fff; outline: none; flex: 1; max-width: 320px; text-align: right; box-sizing: border-box">
            </div>
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 1rem; padding: 0.5rem 0">
                <label style="font-size: 0.8125rem; font-weight: 600; color: #64748b; flex-shrink: 0; width: 180px">Target Expenses</label>
                <input type="number" id="targetExpenses" step="0.01" min="0" value="<?php echo e($settings['target_expenses'] ?? 0); ?>" style="border: 1.5px solid #e2e8f0; border-radius: 8px; padding: 0.5rem 0.75rem; font-size: 0.8125rem; color: #1e293b; background: #fff; outline: none; flex: 1; max-width: 320px; text-align: right; box-sizing: border-box">
            </div>
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 1rem; padding: 0.5rem 0">
                <label style="font-size: 0.8125rem; font-weight: 600; color: #64748b; flex-shrink: 0; width: 180px">Target Sales</label>
                <input type="number" id="targetSales" step="0.01" min="0" value="<?php echo e($settings['target_sales'] ?? 0); ?>" style="border: 1.5px solid #e2e8f0; border-radius: 8px; padding: 0.5rem 0.75rem; font-size: 0.8125rem; color: #1e293b; background: #fff; outline: none; flex: 1; max-width: 320px; text-align: right; box-sizing: border-box">
            </div>
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 1rem; padding: 0.5rem 0">
                <label style="font-size: 0.8125rem; font-weight: 600; color: #64748b; flex-shrink: 0; width: 180px">Target Net Income</label>
                <input type="number" id="targetNetIncome" step="0.01" min="0" value="<?php echo e($settings['target_net_income'] ?? 0); ?>" style="border: 1.5px solid #e2e8f0; border-radius: 8px; padding: 0.5rem 0.75rem; font-size: 0.8125rem; color: #1e293b; background: #fff; outline: none; flex: 1; max-width: 320px; text-align: right; box-sizing: border-box">
            </div>
        </div>
    </div>

    <div style="display: flex; justify-content: flex-end; margin-top: 1rem">
        <button id="saveBtn" onclick="saveSettings()" style="background: linear-gradient(135deg, #10b981, #059669); color: #fff; border: none; padding: 0.625rem 2rem; border-radius: 8px; font-size: 0.875rem; font-weight: 600; cursor: pointer">Save Settings</button>
    </div>

    <div id="saveMsg" style="display: none; text-align: center; padding: 0.75rem 1rem; border-radius: 8px; font-weight: 600; font-size: 0.875rem; margin-top: 1rem"></div>
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

    window.saveSettings = async function() {
        const btn = document.getElementById('saveBtn');
        const msg = document.getElementById('saveMsg');
        btn.disabled = true;
        btn.textContent = 'Saving...';
        msg.style.display = 'none';

        const payload = {
            tax_rate: parseFloat(document.getElementById('taxRate').value) || 0,
            primary_currency: document.getElementById('primaryCurrency').value || null,
            primary_symbol: document.getElementById('primarySymbol').value || null,
            secondary_currency: document.getElementById('secondaryCurrency').value || null,
            secondary_symbol: document.getElementById('secondarySymbol').value || null,
            exchange_rate: parseFloat(document.getElementById('exchangeRate').value) || 1,
            site_url: document.getElementById('siteUrl').value || null,
            target_sales: parseFloat(document.getElementById('targetSales').value) || 0,
            target_income: parseFloat(document.getElementById('targetIncome').value) || 0,
            target_expenses: parseFloat(document.getElementById('targetExpenses').value) || 0,
            target_net_income: parseFloat(document.getElementById('targetNetIncome').value) || 0,
        };

        try {
            const res = await apiFetch('<?php echo e(url("/api/settings")); ?>', {
                method: 'PUT',
                body: JSON.stringify(payload),
            });
            const data = await res.json();
            if (data.success) {
                msg.style.display = 'block';
                msg.style.background = '#d1fae5';
                msg.style.color = '#065f46';
                msg.style.borderLeft = '4px solid #059669';
                msg.textContent = 'Settings saved successfully!';
                setTimeout(() => { msg.style.display = 'none'; }, 3000);
            } else {
                msg.style.display = 'block';
                msg.style.background = '#fee2e2';
                msg.style.color = '#991b1b';
                msg.style.borderLeft = '4px solid #dc2626';
                msg.textContent = data.error || 'Failed to save settings';
            }
        } catch (e) {
            msg.style.display = 'block';
            msg.style.background = '#fee2e2';
            msg.style.color = '#991b1b';
            msg.style.borderLeft = '4px solid #dc2626';
            msg.textContent = 'Error saving settings';
        } finally {
            btn.disabled = false;
            btn.textContent = 'Save Settings';
        }
    };
})();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ALI.A.SALAH\Desktop\pos\resources\views/settings/index.blade.php ENDPATH**/ ?>