import { useState, useRef, useEffect } from 'react';
import { router, usePage } from '@inertiajs/react';
import Layout from '../Layout';

export default function POS({ products: initialProducts, cart: initialCart, customerName: initialCustomerName, taxRate, search: initialSearch, filterField: initialFilterField }) {
    const { flash } = usePage().props;
    const [cart, setCart] = useState(initialCart || []);
    const [customerName, setCustomerName] = useState(initialCustomerName || 'Walk-in Customer');
    const [search, setSearch] = useState(initialSearch || '');
    const [filterField, setFilterField] = useState(initialFilterField || 'all');
    const [products, setProducts] = useState(initialProducts || []);
    const [loading, setLoading] = useState(false);
    const [paymentMethod, setPaymentMethod] = useState('cash');
    const [amountPaid, setAmountPaid] = useState('');
    const [cardAmount, setCardAmount] = useState('');
    const searchInputRef = useRef(null);

    // Calculate totals
    const subtotal = cart.reduce((sum, item) => sum + (item.unit_price * item.quantity), 0);
    const taxTotal = cart.reduce((sum, item) => sum + item.tax_amount, 0);
    const grandTotal = cart.reduce((sum, item) => sum + item.line_total, 0);
    const change = paymentMethod === 'cash' ? Math.max(0, (parseFloat(amountPaid) || 0) - grandTotal) : 0;

    useEffect(() => {
        if (searchInputRef.current) {
            searchInputRef.current.focus();
        }
    }, []);

    const handleSearch = (value) => {
        setSearch(value);
        setLoading(true);
        router.get('/pos', { search: value, filterField }, {
            preserveState: true,
            replace: true,
            onFinish: () => setLoading(false),
        });
    };

    const handleFilterChange = (field) => {
        setFilterField(field);
        router.get('/pos', { search, filterField: field }, {
            preserveState: true,
            replace: true,
        });
    };

    const handleBarcodeScan = (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            setLoading(true);
            router.post('/pos/scan-barcode', { search, filterField }, {
                preserveState: true,
                onFinish: () => {
                    setLoading(false);
                    setSearch('');
                    searchInputRef.current?.focus();
                },
            });
        }
    };

    const addToCart = (itemId) => {
        router.post(`/pos/add-to-cart/${itemId}`, {}, {
            preserveState: true,
            onFinish: () => {
                searchInputRef.current?.focus();
            },
        });
    };

    const updateQuantity = (index, quantity) => {
        router.post(`/pos/update-quantity/${index}`, { quantity }, {
            preserveState: true,
        });
    };

    const removeItem = (index) => {
        router.post(`/pos/remove-from-cart/${index}`, {}, {
            preserveState: true,
        });
    };

    const clearCart = () => {
        router.post('/pos/clear-cart', {}, {
            preserveState: true,
        });
    };

    const updateCustomer = (name) => {
        setCustomerName(name);
        router.post('/pos/update-customer', { customer_name: name }, {
            preserveState: true,
            replace: true,
        });
    };

    const createInvoice = () => {
        router.post('/pos/create-invoice', {
            payment_method: paymentMethod,
            amount_paid: amountPaid || grandTotal,
            card_amount: cardAmount || 0,
        }, {
            preserveState: true,
            onSuccess: () => {
                setCart([]);
                setCustomerName('Walk-in Customer');
                setPaymentMethod('cash');
                setAmountPaid('');
                setCardAmount('');
            },
        });
    };

    const getCategoryClass = (category) => {
        const classes = {
            electronics: 'pos-cat-electronics',
            stationery: 'pos-cat-stationery',
            furniture: 'pos-cat-furniture',
            groceries: 'pos-cat-groceries',
            clothing: 'pos-cat-clothing',
        };
        return classes[category] || 'pos-cat-general';
    };

    return (
        <Layout title="Point of Sale" subtitle={new Date().toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'short', day: 'numeric' })}>
            {/* Flash messages */}
            {flash?.success && (
                <div style={{ background: '#ecfdf5', color: '#065f46', padding: '0.75rem 1rem', borderRadius: '8px', marginBottom: '1rem', border: '1px solid #a7f3d0' }}>
                    {flash.success}
                </div>
            )}
            {flash?.error && (
                <div style={{ background: '#fef2f2', color: '#991b1b', padding: '0.75rem 1rem', borderRadius: '8px', marginBottom: '1rem', border: '1px solid #fecaca' }}>
                    {flash.error}
                </div>
            )}

            <div className="pos-layout">
                {/* Left: Search + Products */}
                <div className="pos-items-panel">
                    <div className="pos-panel-header">
                        <div className="pos-filter-tabs">
                            {['all', 'name', 'code', 'barcode'].map((field) => (
                                <button
                                    key={field}
                                    onClick={() => handleFilterChange(field)}
                                    className={`pos-filter-tab ${filterField === field ? 'active' : ''}`}
                                >
                                    {field.charAt(0).toUpperCase() + field.slice(1)}
                                </button>
                            ))}
                        </div>
                        <div className="pos-search-box">
                            <svg className="pos-search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                                <circle cx="11" cy="11" r="8" />
                                <path d="m21 21-4.3-4.3" />
                            </svg>
                            <input
                                ref={searchInputRef}
                                type="text"
                                placeholder="Scan barcode or search products..."
                                className="pos-search"
                                autoFocus
                                value={search}
                                onChange={(e) => handleSearch(e.target.value)}
                                onKeyDown={handleBarcodeScan}
                            />
                            {loading && (
                                <div className="pos-search-spinner">
                                    <svg className="animate-spin" width="18" height="18" viewBox="0 0 24 24" fill="none">
                                        <circle cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="3" opacity="0.25" />
                                        <path d="M4 12a8 8 0 018-8" stroke="currentColor" strokeWidth="3" strokeLinecap="round" />
                                    </svg>
                                </div>
                            )}
                        </div>
                    </div>

                    <div className="pos-items-body">
                        {products.length > 0 ? (
                            <>
                                <div className="pos-results-bar">
                                    <span className="pos-results-title">{search ? 'Search Results' : 'All Products'}</span>
                                    <span className="ui-badge-accent">{products.length} found</span>
                                </div>
                                <div className="pos-items-grid">
                                    {products.map((product) => (
                                        <button key={product.id} onClick={() => addToCart(product.id)} className="pos-item">
                                            {product.image_url ? (
                                                <div className="pos-item-img">
                                                    <img src={product.image_url} alt={product.name} loading="lazy" />
                                                </div>
                                            ) : (
                                                <div className="pos-item-img-placeholder">
                                                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1" opacity="0.3">
                                                        <rect x="3" y="3" width="18" height="18" rx="3" />
                                                        <circle cx="8.5" cy="8.5" r="1.5" />
                                                        <path d="m21 15-5-5L5 21" />
                                                    </svg>
                                                </div>
                                            )}
                                            <div className="pos-item-body">
                                                <div className="pos-item-name" title={product.name}>{product.name}</div>
                                                <div className="pos-item-meta">{product.code}</div>
                                                <span className={`pos-category-badge ${getCategoryClass(product.category)}`}>
                                                    {product.category ? product.category.charAt(0).toUpperCase() + product.category.slice(1) : 'General'}
                                                </span>
                                                <div className="pos-item-footer">
                                                    <div className="pos-item-price">JD {parseFloat(product.pre_tax_price).toFixed(2)}</div>
                                                    <div className="pos-item-stock">
                                                        {product.quantity > 5 ? (
                                                            <span className="pos-stock-available">In stock: {product.quantity}</span>
                                                        ) : product.quantity > 0 ? (
                                                            <span className="pos-stock-low">Low: {product.quantity}</span>
                                                        ) : (
                                                            <span className="pos-stock-out">Out</span>
                                                        )}
                                                    </div>
                                                </div>
                                            </div>
                                        </button>
                                    ))}
                                </div>
                            </>
                        ) : (
                            <div className="pos-empty">
                                <div className="pos-empty-icon">📦</div>
                                <div className="pos-empty-text">
                                    {search ? `No products found matching "${search}"` : 'Start typing to search for products'}
                                </div>
                                <div className="pos-empty-hint">Search by name, code, or barcode</div>
                            </div>
                        )}
                    </div>
                </div>

                {/* Right: Cart */}
                <div className="pos-cart-panel">
                    <div className="pos-cart-header">
                        <h3 className="pos-cart-title">
                            Shopping Cart
                            {cart.length > 0 && (
                                <span className="pos-cart-count">{cart.length}</span>
                            )}
                        </h3>
                        {cart.length > 0 && (
                            <button onClick={clearCart} className="ui-btn-ghost">Clear all</button>
                        )}
                    </div>

                    {/* Customer Field */}
                    <div className="pos-customer-section">
                        <label className="pos-customer-label">Customer</label>
                        <input
                            type="text"
                            value={customerName}
                            onChange={(e) => updateCustomer(e.target.value)}
                            placeholder="Walk-in Customer"
                            className="pos-customer-input"
                        />
                    </div>

                    <div className="pos-cart-body">
                        {cart.length === 0 ? (
                            <div className="pos-empty" style={{ minHeight: '100px', padding: '1.5rem 1rem' }}>
                                <div className="pos-empty-icon" style={{ fontSize: '2rem', marginBottom: '0.5rem' }}>🛒</div>
                                <div className="pos-empty-text" style={{ fontSize: '0.875rem' }}>Cart is empty</div>
                                <div className="pos-empty-hint" style={{ fontSize: '0.75rem' }}>Click a product to add it</div>
                            </div>
                        ) : (
                            cart.map((item, index) => (
                                <div key={index} className="pos-cart-row">
                                    <div className="pos-cart-row-info">
                                        <div className="pos-cart-row-name" title={item.item_name}>{item.item_name}</div>
                                        <div className="pos-cart-row-sub">JD {parseFloat(item.unit_price).toFixed(2)} × {item.quantity}</div>
                                    </div>
                                    <div className="pos-stepper">
                                        <button onClick={() => updateQuantity(index, item.quantity - 1)} className="pos-stepper-btn">−</button>
                                        <span className="pos-stepper-val">{item.quantity}</span>
                                        <button onClick={() => updateQuantity(index, item.quantity + 1)} className="pos-stepper-btn">+</button>
                                    </div>
                                    <div className="pos-cart-row-total">JD {parseFloat(item.line_total).toFixed(2)}</div>
                                    <button onClick={() => removeItem(index)} className="pos-remove-btn">×</button>
                                </div>
                            ))
                        )}
                    </div>

                    {cart.length > 0 && (
                        <>
                            {/* Payment Section */}
                            <div style={{ padding: '0.75rem 1.25rem', borderTop: '1px solid #e5e7eb', background: '#ffffff' }}>
                                <label className="pos-customer-label">Payment Method</label>
                                <select
                                    value={paymentMethod}
                                    onChange={(e) => setPaymentMethod(e.target.value)}
                                    style={{ width: '100%', border: '1.5px solid #e2e8f0', borderRadius: '8px', padding: '0.4rem 0.625rem', fontSize: '0.8125rem', fontFamily: 'var(--font-sans)', color: '#1e293b', background: '#f8fafc', outline: 'none' }}
                                >
                                    <option value="cash">Cash</option>
                                    <option value="card">Card</option>
                                    <option value="split">Split Payment</option>
                                </select>
                                {paymentMethod === 'cash' && (
                                    <>
                                        <label className="pos-customer-label" style={{ marginTop: '0.5rem' }}>Amount Paid</label>
                                        <input
                                            type="number"
                                            value={amountPaid}
                                            onChange={(e) => setAmountPaid(e.target.value)}
                                            min="0"
                                            step="0.01"
                                            placeholder="0.00"
                                            style={{ width: '100%', border: '1.5px solid #e2e8f0', borderRadius: '8px', padding: '0.4rem 0.625rem', fontSize: '0.8125rem', fontFamily: 'var(--font-sans)', color: '#1e293b', background: '#f8fafc', outline: 'none' }}
                                        />
                                    </>
                                )}
                                {paymentMethod === 'split' && (
                                    <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '0.5rem', marginTop: '0.5rem' }}>
                                        <div>
                                            <label className="pos-customer-label">Cash</label>
                                            <input type="number" value={amountPaid} onChange={(e) => setAmountPaid(e.target.value)} min="0" step="0.01" placeholder="0.00" style={{ width: '100%', border: '1.5px solid #e2e8f0', borderRadius: '8px', padding: '0.4rem 0.625rem', fontSize: '0.8125rem', fontFamily: 'var(--font-sans)', color: '#1e293b', background: '#f8fafc', outline: 'none' }} />
                                        </div>
                                        <div>
                                            <label className="pos-customer-label">Card</label>
                                            <input type="number" value={cardAmount} onChange={(e) => setCardAmount(e.target.value)} min="0" step="0.01" placeholder="0.00" style={{ width: '100%', border: '1.5px solid #e2e8f0', borderRadius: '8px', padding: '0.4rem 0.625rem', fontSize: '0.8125rem', fontFamily: 'var(--font-sans)', color: '#1e293b', background: '#f8fafc', outline: 'none' }} />
                                        </div>
                                    </div>
                                )}
                            </div>

                            {/* Summary */}
                            <div className="pos-summary-section" style={{ padding: '0.75rem 1.25rem' }}>
                                <div className="pos-summary-row">
                                    <span>Subtotal</span>
                                    <span>JD {subtotal.toFixed(2)}</span>
                                </div>
                                <div className="pos-summary-row">
                                    <span>Tax ({taxRate}%)</span>
                                    <span>JD {taxTotal.toFixed(2)}</span>
                                </div>
                                <div className="pos-summary-total">
                                    <span style={{ fontWeight: 800, fontSize: '1rem' }}>Grand Total</span>
                                    <span style={{ fontWeight: 800, fontSize: '1.05rem', color: '#1e293b' }}>JD {grandTotal.toFixed(2)}</span>
                                </div>
                                {paymentMethod === 'cash' && parseFloat(amountPaid) > 0 && (
                                    <>
                                        <div className="pos-summary-paid">
                                            <span>Paid</span>
                                            <span>JD {parseFloat(amountPaid).toFixed(2)}</span>
                                        </div>
                                        {change > 0 && (
                                            <div className="pos-summary-change">
                                                <span>Change Due</span>
                                                <span>JD {change.toFixed(2)}</span>
                                            </div>
                                        )}
                                    </>
                                )}
                            </div>

                            {/* Checkout */}
                            <div className="pos-checkout-section" style={{ padding: '0.75rem 1.25rem 1.25rem' }}>
                                <button onClick={createInvoice} className="pos-checkout">
                                    Create Invoice
                                </button>
                            </div>
                        </>
                    )}
                </div>
            </div>
        </Layout>
    );
}
