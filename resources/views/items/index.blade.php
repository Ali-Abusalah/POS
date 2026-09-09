@extends('layouts.app')
@section('page-title', 'Items')
@push('styles')
<style>
    .items-page { padding: 1.5rem; max-width: 1400px; margin: 0 auto; }
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; }
    .page-header h1 { font-size: 1.5rem; font-weight: 700; color: #000080; margin: 0; }
    .btn { display: inline-flex; align-items: center; gap: 0.5rem; border-radius: 8px; font-weight: 600; text-decoration: none; cursor: pointer; border: none; transition: all 0.15s ease; }
    .btn-primary { background: linear-gradient(135deg, #000080, #000060); color: #fff; padding: 0.5rem 1.25rem; font-size: 0.875rem; }
    .btn-primary:hover { opacity: 0.9; }
    .btn-ghost { background: transparent; color: #555555; border: 1px solid #e0e0e0; padding: 0.375rem 0.75rem; font-size: 0.8125rem; }
    .btn-ghost:hover { background: #f5f5f5; color: #000080; border-color: #cbd5e1; }
    .btn-danger-ghost { background: transparent; color: #dc2626; border: 1px solid #fecaca; padding: 0.375rem 0.75rem; font-size: 0.8125rem; }
    .btn-danger-ghost:hover { background: #fef2f2; }
    .filter-row { display: flex; gap: 0.5rem; margin-bottom: 1rem; flex-wrap: wrap; }
    .filter-input { border: 1.5px solid #e0e0e0; border-radius: 8px; padding: 0.5rem 0.75rem; font-size: 0.8125rem; color: #000080; background: #fff; outline: none; transition: border-color 0.15s; }
    .filter-input:focus { border-color: #000080; box-shadow: 0 0 0 3px rgba(0,0,128,0.1); }
    .filter-input::placeholder { color: #888888; }
    .table-container { background: #fff; border: 1px solid #e0e0e0; border-radius: 12px; overflow: auto; }
    table { width: 100%; border-collapse: collapse; }
    table th { text-align: left; padding: 0.75rem 1rem; font-size: 0.75rem; font-weight: 600; color: #555555; text-transform: uppercase; letter-spacing: 0.05em; background: #f5f5f5; border-bottom: 1px solid #e0e0e0; }
    table td { padding: 0.75rem 1rem; font-size: 0.875rem; color: #000080; border-bottom: 1px solid #f5f5f5; }
    table tbody tr:hover td { background: #f5f5f5; }
    .text-right { text-align: right; }
    .text-center { text-align: center; }
    .font-bold { font-weight: 700; }
    .item-image { width: 36px; height: 36px; border-radius: 6px; object-fit: cover; background: #f5f5f5; }
    .badge { display: inline-flex; align-items: center; padding: 0.2rem 0.6rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; }
    .badge-active { background: #dcfce7; color: #166534; }
    .badge-inactive { background: #fee2e2; color: #991b1b; }
    .badge-category { background: #f5f5f5; color: #555555; }
    .stock-low { color: #dc2626; font-weight: 600; }
    .stock-ok { color: #000080; }
    .pagination { display: flex; justify-content: center; gap: 0.5rem; margin-top: 1rem; align-items: center; }
    .pagination a, .pagination span { display: inline-flex; align-items: center; justify-content: center; min-width: 2rem; height: 2rem; padding: 0 0.5rem; border-radius: 6px; font-size: 0.8125rem; font-weight: 500; text-decoration: none; transition: all 0.15s; }
    .pagination a { color: #555555; border: 1px solid #e0e0e0; background: #fff; }
    .pagination a:hover { background: #f5f5f5; border-color: #cbd5e1; }
    .pagination .active span { background: #000080; color: #fff; border: 1px solid #000080; }
    .pagination .disabled span { color: #cbd5e1; cursor: not-allowed; border: 1px solid #f5f5f5; }
    .page-counter { font-size: 0.8125rem; color: #555555; margin-left: 0.5rem; }
    .empty-msg { text-align: center; padding: 3rem 1rem; color: #888888; font-size: 0.9375rem; }

    .modal-overlay { position: fixed; inset: 0; background: rgba(15,23,42,0.4); z-index: 100; display: none; align-items: center; justify-content: center; padding: 1rem; }
    .modal-overlay.open { display: flex; }
    .modal { background: #fff; border-radius: 12px; padding: 1.5rem; width: 560px; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 60px rgba(0,0,0,0.15); }
    .modal-title { font-size: 1.125rem; font-weight: 700; color: #000080; margin: 0 0 1.25rem; }
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; }
    .form-group { display: flex; flex-direction: column; }
    .form-group label { font-size: 0.8125rem; font-weight: 600; color: #555555; margin-bottom: 0.375rem; }
    .form-group input, .form-group select, .form-group textarea { padding: 0.5rem 0.75rem; border: 1.5px solid #e0e0e0; border-radius: 8px; font-size: 0.8125rem; color: #000080; background: #fff; outline: none; transition: border-color 0.15s; }
    .form-group input:focus, .form-group select:focus, .form-group textarea:focus { border-color: #000080; box-shadow: 0 0 0 3px rgba(0,0,128,0.1); }
    .form-group textarea { resize: vertical; min-height: 60px; }
    .form-full { grid-column: 1 / -1; }
    .form-check { display: flex; align-items: center; gap: 0.5rem; }
    .form-check input[type="checkbox"] { width: auto; accent-color: #000080; }
    .form-check label { margin: 0; font-size: 0.8125rem; color: #555555; }
    .modal-actions { display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 1.25rem; padding-top: 1rem; border-top: 1px solid #e0e0e0; }
    .btn-cancel { background: transparent; color: #555555; border: 1px solid #e0e0e0; padding: 0.5rem 1.25rem; font-size: 0.875rem; border-radius: 8px; cursor: pointer; }
    .btn-cancel:hover { background: #f5f5f5; }
    .btn-save { background: linear-gradient(135deg, #000080, #000060); color: #fff; padding: 0.5rem 1.5rem; font-size: 0.875rem; border-radius: 8px; cursor: pointer; border: none; font-weight: 600; }
    .btn-save:hover { opacity: 0.9; }
    @media (max-width: 640px) { .form-grid { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')
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
                @forelse($items as $item)
                <tr>
                    <td>
                        @if($item->image)
                            <img src="{{ asset('storage/' . $item->image) }}" alt="" class="item-image">
                        @else
                            <div class="item-image" style="display:flex;align-items:center;justify-content:center;font-size:0.75rem;color:#888888;">N/A</div>
                        @endif
                    </td>
                    <td class="font-bold">{{ $item->code }}</td>
                    <td>{{ $item->name }}</td>
                    <td><span class="badge badge-category">{{ ucfirst($item->category ?? 'general') }}</span></td>
                    <td class="text-right font-bold">JD {{ number_format($item->pre_tax_price, 2) }}</td>
                    <td class="text-right {{ ($item->quantity ?? 0) <= ($item->low_stock_threshold ?? 5) ? 'stock-low' : 'stock-ok' }}">{{ $item->quantity ?? 0 }}</td>
                    <td>
                        @if($item->is_active)
                            <span class="badge badge-active">Active</span>
                        @else
                            <span class="badge badge-inactive">Inactive</span>
                        @endif
                    </td>
                    <td class="text-right">
                        <button class="btn btn-ghost" onclick="editItem({{ $item->id }})">Edit</button>
                        <button class="btn btn-danger-ghost" onclick="deleteItem({{ $item->id }})">Delete</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="empty-msg">No items found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($items->hasPages())
    <div class="pagination">
        @if($items->onFirstPage())
            <span class="disabled"><span>&laquo;</span></span>
        @else
            <a href="{{ $items->previousPageUrl() }}">&laquo;</a>
        @endif

        @foreach($items->getUrlRange(max(1, $items->currentPage() - 2), min($items->lastPage(), $items->currentPage() + 2)) as $page => $url)
            @if($page == $items->currentPage())
                <span class="active"><span>{{ $page }}</span></span>
            @else
                <a href="{{ $url }}">{{ $page }}</a>
            @endif
        @endforeach

        @if($items->currentPage() < $items->lastPage())
            <a href="{{ $items->nextPageUrl() }}">&raquo;</a>
        @else
            <span class="disabled"><span>&raquo;</span></span>
        @endif
        <span class="page-counter">Page {{ $items->currentPage() }} of {{ $items->lastPage() }}</span>
    </div>
    @endif
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
                    <input type="number" id="itemPrice" step="0.01" min="0" required inputmode="decimal">
                </div>
                <div class="form-group">
                    <label for="itemQty">Stock Quantity*</label>
                    <input type="number" id="itemQty" min="0" required inputmode="numeric">
                </div>
                <div class="form-group">
                    <label for="itemThreshold">Low Stock Threshold</label>
                    <input type="number" id="itemThreshold" min="0" value="5" inputmode="numeric">
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
@endsection

@push('scripts')
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
@endpush
