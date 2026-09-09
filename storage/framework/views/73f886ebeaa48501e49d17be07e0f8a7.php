<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e($title ?? config('app.name', 'POS')); ?></title>
    <style>
        :root {
            --background: #f5f5f5;
            --foreground: #000080;
            --accent: #000080;
            --accent-hover: #000060;
            --success: #10b981;
            --danger: #dc2626;
            --warning: #f59e0b;
            --border: #e0e0e0;
            --border-default: #e0e0e0;
            --surface: #ffffff;
            --muted: #f5f5f5;
            --bg-muted: #f5f5f5;
            --text-secondary: #555555;
            --text-muted: #888888;
            --navy: #000080;
            --card-bg: #ffffff;
            --card-radius: 12px;
        }
        body { background: var(--background); color: var(--foreground); font-family: system-ui, -apple-system, sans-serif; margin: 0; }
        .app-shell { display: flex; flex-direction: column; min-height: 100vh; }
        .top-navbar { display: flex; align-items: center; justify-content: space-between; background: var(--navy); padding: 0 1.25rem; height: 56px; flex-shrink: 0; z-index: 50; }
        .top-nav-left { display: flex; align-items: center; gap: 0.625rem; }
        .top-nav-center { display: flex; align-items: center; justify-content: center; }
        .top-nav-right { display: flex; align-items: center; gap: 0.625rem; }
        .top-nav-icon-btn { background: transparent; border: none; color: rgba(255,255,255,0.7); cursor: pointer; width: 34px; height: 34px; border-radius: 8px; display: flex; align-items: center; justify-content: center; transition: all 0.15s ease; position: relative; }
        .top-nav-icon-btn:hover { background: rgba(255,255,255,0.1); color: #ffffff; }
        .pos-badge-btn { display: inline-flex; align-items: center; gap: 0.375rem; background: rgba(255,255,255,0.15); color: #fff; padding: 0.375rem 0.875rem; border-radius: 9999px; font-size: 0.8125rem; font-weight: 700; text-decoration: none; letter-spacing: 0.02em; transition: all 0.15s ease; border: 1px solid rgba(255,255,255,0.2); }
        .pos-badge-btn:hover { opacity: 0.9; transform: translateY(-1px); background: rgba(255,255,255,0.2); }
        .company-logo-box { display: flex; align-items: center; gap: 0.625rem; background: #ffffff; border-radius: 10px; padding: 0.375rem 1rem; }
        .company-logo-icon { display: flex; align-items: center; }
        .company-logo-text { display: flex; flex-direction: column; line-height: 1.15; }
        .company-name-bold { font-size: 0.8125rem; font-weight: 800; color: #000080; }
        .company-name-sub { font-size: 0.6875rem; font-weight: 600; color: #000080; opacity: 0.7; }
        .online-badge { display: inline-flex; align-items: center; gap: 0.375rem; background: #000080; color: #ffffff; padding: 0.375rem 0.75rem; border-radius: 9999px; font-size: 0.6875rem; font-weight: 600; white-space: nowrap; }
        .online-dot { width: 7px; height: 7px; border-radius: 50%; background: #34d399; box-shadow: 0 0 6px #34d399; }
        .account-btn { display: flex; align-items: center; gap: 0.375rem; background: transparent; border: none; color: rgba(255,255,255,0.85); cursor: pointer; padding: 0.25rem; border-radius: 8px; transition: all 0.15s ease; }
        .account-btn:hover { background: rgba(255,255,255,0.1); }
        .account-avatar { width: 32px; height: 32px; border-radius: 50%; background: #ffffff; color: #000080; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 700; }
        .account-label { font-size: 0.8125rem; font-weight: 500; }
        .dropdown-wrap { position: relative; }
        .nav-dropdown-btn { display: flex; align-items: center; gap: 0.375rem; background: transparent; border: none; color: rgba(255,255,255,0.85); cursor: pointer; font-size: 0.8125rem; font-weight: 500; padding: 0.375rem 0.625rem; border-radius: 8px; transition: all 0.15s ease; white-space: nowrap; }
        .nav-dropdown-btn:hover { background: rgba(255,255,255,0.1); }
        .nav-dropdown-menu { position: absolute; top: 100%; right: 0; margin-top: 0.375rem; background: #ffffff; border-radius: 10px; box-shadow: 0 10px 40px rgba(0,0,0,0.12); border: 1px solid #e0e0e0; min-width: 180px; padding: 0.375rem; z-index: 100; display: none; }
        .nav-dropdown-menu.show { display: block; }
        .nav-dropdown-item { display: block; padding: 0.5rem 0.75rem; border-radius: 6px; font-size: 0.8125rem; font-weight: 500; color: #555555; text-decoration: none; cursor: pointer; border: none; background: none; width: 100%; text-align: left; transition: all 0.1s ease; }
        .nav-dropdown-item:hover { background: #f5f5f5; color: #000080; }
        .nav-dropdown-header { padding: 0.625rem 0.75rem; font-size: 0.8125rem; font-weight: 600; color: #000080; border-bottom: 1px solid #f5f5f5; margin-bottom: 0.25rem; }
        .secondary-navbar { display: flex; align-items: center; gap: 0.125rem; background: #ffffff; border-bottom: 1px solid #e0e0e0; padding: 0 1.25rem; height: 44px; flex-shrink: 0; overflow-x: auto; }
        .secondary-nav-item { display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.5rem 0.875rem; font-size: 0.8125rem; font-weight: 500; color: #888888; text-decoration: none; border-radius: 6px; white-space: nowrap; transition: all 0.15s ease; background: none; border: none; cursor: pointer; }
        .secondary-nav-item:hover { color: #000080; background: #f5f5f5; }
        .secondary-nav-item.active { color: #000080; font-weight: 600; background: #f0f0ff; }
        .secondary-nav-dropdown-wrap { position: relative; }
        .sales-dropdown { position: fixed; background: #fff; border-radius: 12px; box-shadow: 0 10px 40px rgba(0,0,0,0.12), 0 2px 8px rgba(0,0,0,0.06); border: 1px solid #e0e0e0; min-width: 220px; padding: 0.375rem; z-index: 1000; display: none; }
        .sales-dropdown.show { display: block; }
        .sales-dropdown-item { display: flex; align-items: center; gap: 0.625rem; padding: 0.5rem 0.75rem; border-radius: 8px; font-size: 0.8125rem; font-weight: 500; color: #555555; text-decoration: none; transition: all 0.1s ease; cursor: pointer; }
        .sales-dropdown-item:hover { background: #f5f5f5; color: #000080; }
        .sales-dropdown-item.active { background: #f0f0ff; color: #000080; font-weight: 600; }
        .sales-dropdown-icon { width: 28px; height: 28px; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; background: currentColor; position: relative; }
        .sales-dropdown-icon > svg { stroke: #fff; position: relative; z-index: 1; }
        .sales-dropdown-item:nth-child(1) .sales-dropdown-icon { background: #000080; }
        .sales-dropdown-item:nth-child(2) .sales-dropdown-icon { background: #000080; }
        .sales-dropdown-item:nth-child(3) .sales-dropdown-icon { background: #000080; }
        .sales-dropdown-item:nth-child(4) .sales-dropdown-icon { background: #000080; }
        .sales-dropdown-label { flex: 1; }
        .sales-dropdown-item-wrap { position: relative; }
        .sales-submenu { position: absolute; left: 100%; top: -0.25rem; background: #fff; border: 1px solid #e0e0e0; border-radius: 8px; box-shadow: 0 8px 24px rgba(0,0,0,0.12); padding: 0.25rem 0; min-width: 160px; z-index: 100; display: none; }
        .sales-submenu.show { display: block; }
        .sales-submenu-item { display: block; padding: 0.5rem 1rem; font-size: 0.8125rem; font-weight: 500; color: #555555; text-decoration: none; transition: background 0.15s, color 0.15s; white-space: nowrap; }
        .sales-submenu-item:hover { background: #f0f0ff; color: #000080; }
        .sales-submenu-item.active { background: #f0f0ff; color: #000080; font-weight: 600; }
        .sales-dropdown-divider { height: 1px; background: #f5f5f5; margin: 0.375rem 0.5rem; }
        .main-content { flex: 1; overflow: auto; }
        .sidebar-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.4); z-index: 200; display: none; }
        .sidebar-overlay.show { display: block; }
        .sidebar-panel { position: fixed; top: 0; left: 0; width: 260px; height: 100vh; background: #ffffff; z-index: 201; box-shadow: 4px 0 20px rgba(0,0,0,0.1); transform: translateX(-100%); transition: transform 0.25s ease; display: flex; flex-direction: column; border-right: 1px solid #e0e0e0; }
        .sidebar-panel.open { transform: translateX(0); }
        .sidebar-header { display: flex; align-items: center; justify-content: space-between; padding: 1rem 1.25rem; border-bottom: 1px solid #f5f5f5; }
        .sidebar-close { background: none; border: none; color: #888888; cursor: pointer; padding: 4px; border-radius: 6px; }
        .sidebar-close:hover { background: #f5f5f5; }
        .sidebar-nav { flex: 1; padding: 0.75rem 0; overflow-y: auto; }
        .sidebar-link { display: flex; align-items: center; gap: 0.75rem; padding: 0.625rem 1.25rem; color: #555555; text-decoration: none; font-size: 0.875rem; font-weight: 500; transition: all 0.15s ease; }
        .sidebar-link:hover { background: #f5f5f5; color: #000080; }
        .sidebar-link.active { background: #f0f0ff; color: #000080; font-weight: 600; }
        .sidebar-divider { height: 1px; background: #e0e0e0; margin: 0.5rem 1.25rem; }
        .search-modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 300; display: none; align-items: flex-start; justify-content: center; padding-top: 15vh; }
        .search-modal-overlay.show { display: flex; }
        .search-modal { width: 100%; max-width: 520px; background: #fff; border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,0.2); overflow: hidden; border: 1px solid #e0e0e0; }
        .search-modal-input-wrap { display: flex; align-items: center; gap: 0.75rem; padding: 0.875rem 1.25rem; border-bottom: 1px solid #e0e0e0; color: #888888; }
        .search-modal-input { flex: 1; border: none; outline: none; font-size: 0.9375rem; color: #000080; background: transparent; }
        .search-modal-input::placeholder { color: #888888; }
        .search-modal-close { background: none; border: 1px solid #e0e0e0; border-radius: 6px; padding: 2px 6px; cursor: pointer; }
        .search-modal-close kbd { font-size: 0.6875rem; color: #888888; font-family: inherit; }
        .search-modal-results { max-height: 320px; overflow-y: auto; padding: 0.5rem; }
        .search-modal-section { font-size: 0.6875rem; font-weight: 700; color: #888888; text-transform: uppercase; letter-spacing: 0.05em; padding: 0.5rem 0.75rem 0.375rem; }
        .search-modal-result { display: flex; align-items: center; gap: 0.625rem; padding: 0.5rem 0.75rem; border-radius: 8px; color: #555555; text-decoration: none; font-size: 0.8125rem; font-weight: 500; transition: background 0.1s; }
        .search-modal-result:hover { background: #f5f5f5; color: #000080; }
        .notif-badge { position: absolute; top: 4px; right: 4px; width: 8px; height: 8px; background: #dc2626; border-radius: 50%; border: 2px solid #000080; }
        .notif-dropdown { width: 320px; max-height: 360px; overflow-y: auto; padding: 0; }
        .notif-header { font-size: 0.75rem; font-weight: 700; color: #000080; padding: 0.75rem 1rem 0.5rem; border-bottom: 1px solid #f5f5f5; }
        .notif-item { display: flex; align-items: flex-start; gap: 0.625rem; padding: 0.625rem 1rem; border-bottom: 1px solid #f5f5f5; cursor: pointer; transition: background 0.1s; }
        .notif-item:hover { background: #f5f5f5; }
        .notif-item.unread { background: #f0f0ff; }
        .notif-item.unread:hover { background: #e8e8ff; }
        .notif-dot { width: 8px; height: 8px; border-radius: 50%; background: #000080; flex-shrink: 0; margin-top: 4px; }
        .notif-title { font-size: 0.8125rem; color: #000080; font-weight: 500; line-height: 1.3; }
        .notif-time { font-size: 0.6875rem; color: #888888; margin-top: 0.125rem; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes slideUp { from { transform: translateY(100%); } to { transform: translateY(0); } }
        .main-content { flex: 1; overflow: auto; padding: 1.5rem 2rem 2.5rem; max-width: 1440px; margin: 0 auto; width: 100%; box-sizing: border-box; }
        .ui-page-title { font-size: 1.5rem; font-weight: 700; color: #000080; margin: 0 0 0.25rem; }
        .ui-page-subtitle { font-size: 0.8125rem; color: #555555; margin: 0 0 0; }
        .ui-grid-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; }
        .ui-grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; }
        .ui-grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; }
        .ui-card { background: #fff; border: 1px solid #e0e0e0; border-radius: 12px; overflow: hidden; }
        .ui-card-header { display: flex; align-items: center; justify-content: space-between; padding: 0.875rem 1.25rem; border-bottom: 1px solid #e0e0e0; }
        .ui-card-body { padding: 1.25rem; }
        .ui-section-title { font-size: 0.875rem; font-weight: 700; color: #000080; }
        .ui-stat { padding: 1rem; }
        .ui-stat-label { font-size: 0.6875rem; font-weight: 500; color: #888888; text-transform: uppercase; letter-spacing: 0.05em; }
        .ui-stat-value { font-size: 1.5rem; font-weight: 800; color: #000080; margin-top: 0.25rem; line-height: 1.1; }
        .ui-stat-value.accent { color: #000080; }
        .ui-stat-value.success { color: #10b981; }
        .ui-stat-value.danger { color: #dc2626; }
        .ui-badge { display: inline-flex; align-items: center; padding: 0.125rem 0.625rem; border-radius: 9999px; font-size: 0.6875rem; font-weight: 600; white-space: nowrap; }
        .ui-badge-success { background: #ecfdf5; color: #10b981; }
        .ui-badge-danger { background: #fef2f2; color: #dc2626; }
        .ui-badge-warning { background: #fffbeb; color: #f59e0b; }
        .ui-badge-info { background: #f0f0ff; color: #000080; }
        .ui-btn { display: inline-flex; align-items: center; justify-content: center; gap: 0.375rem; padding: 0.5rem 1rem; border-radius: 8px; font-size: 0.8125rem; font-weight: 500; text-decoration: none; cursor: pointer; border: none; transition: all 0.15s ease; }
        .ui-btn-primary { background: #000080; color: #fff; }
        .ui-btn-primary:hover { background: #000060; }
        .ui-btn-ghost { background: transparent; border: 1px solid #e0e0e0; color: #555555; }
        .ui-btn-ghost:hover { background: #f5f5f5; color: #000080; }
        .ui-btn-sm { padding: 0.375rem 0.75rem; font-size: 0.8125rem; }
        .ui-btn-danger { background: #dc2626; color: #fff; }
        .ui-btn-danger:hover { background: #b91c1c; }
        .ui-table { width: 100%; border-collapse: collapse; }
        .ui-table th { text-align: left; padding: 0.625rem 1rem; font-size: 0.6875rem; font-weight: 700; color: #888888; text-transform: uppercase; letter-spacing: 0.06em; border-bottom: 1px solid #e0e0e0; background: #f5f5f5; }
        .ui-table td { padding: 0.625rem 1rem; font-size: 0.8125rem; color: #555555; border-bottom: 1px solid #f5f5f5; }
        .ui-table tr:hover td { background: #f5f5f5; }
        .ui-table .r { text-align: right; }
        .ui-table .bold { font-weight: 700; color: #000080; }
        .ui-table-empty { text-align: center; padding: 3rem 1rem; color: #888888; font-size: 0.8125rem; }
        .ui-form-group { margin-bottom: 0.75rem; }
        .ui-form-label { display: block; font-size: 0.75rem; font-weight: 600; color: #555555; margin-bottom: 0.3rem; }
        .ui-input { width: 100%; border: 1.5px solid #e0e0e0; border-radius: 8px; padding: 0.5rem 0.75rem; font-size: 0.8125rem; color: #000080; background: #fff; outline: none; box-sizing: border-box; font-family: inherit; }
        .ui-input:focus { border-color: #000080; box-shadow: 0 0 0 3px rgba(0,0,128,0.1); }
        .ui-select { width: 100%; border: 1.5px solid #e0e0e0; border-radius: 8px; padding: 0.5rem 0.75rem; font-size: 0.8125rem; color: #000080; background: #fff; outline: none; }
        .ui-empty { padding: 3rem 1rem; text-align: center; color: #888888; font-size: 0.8125rem; }
        @media (max-width: 768px) { .ui-grid-2, .ui-grid-3, .ui-grid-4 { grid-template-columns: 1fr; } }
        @media (max-width: 1024px) { .ui-grid-4 { grid-template-columns: repeat(2, 1fr); } }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
    <div class="app-shell">
        <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

        <aside class="sidebar-panel" id="sidebarPanel">
            <div class="sidebar-header">
                <div class="company-logo-box" style="padding:0">
                    <div class="company-logo-icon">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none">
                            <rect width="24" height="24" rx="4" fill="#000080"/>
                            <path d="M6 16V8l6 4-6 4z" fill="#fff"/>
                            <path d="M12 16V8l6 4-6 4z" fill="#6666cc"/>
                        </svg>
                    </div>
                    <div class="company-logo-text">
                        <span class="company-name-bold">Digital Creativity</span>
                        <span class="company-name-sub">Tech Shop</span>
                    </div>
                </div>
                <button class="sidebar-close" onclick="toggleSidebar()">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
            <nav class="sidebar-nav">
                <a href="<?php echo e(route('dashboard')); ?>" class="sidebar-link <?php echo e(request()->is('dashboard') ? 'active' : ''); ?>" onclick="toggleSidebar()">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                    Dashboard
                </a>
                <a href="<?php echo e(route('pos.index')); ?>" class="sidebar-link <?php echo e(request()->is('/') ? 'active' : ''); ?>" onclick="toggleSidebar()">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                    POS Sales
                </a>
                <a href="<?php echo e(route('sales.index')); ?>" class="sidebar-link <?php echo e(request()->is('sales') && !request()->is('sales/*') ? 'active' : ''); ?>" onclick="toggleSidebar()">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                    Sales
                </a>
                <a href="<?php echo e(route('quotes.index')); ?>" class="sidebar-link <?php echo e(request()->is('sales/quotes') ? 'active' : ''); ?>" onclick="toggleSidebar()">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    Quotes
                </a>
                <a href="<?php echo e(route('subscriptions.index')); ?>" class="sidebar-link <?php echo e(request()->is('sales/subscriptions') ? 'active' : ''); ?>" onclick="toggleSidebar()">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg>
                    Subscriptions
                </a>
                <a href="<?php echo e(route('credit-notes.index')); ?>" class="sidebar-link <?php echo e(request()->is('sales/credit-notes') ? 'active' : ''); ?>" onclick="toggleSidebar()">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="9" y1="15" x2="15" y2="15"/></svg>
                    Credit Notes
                </a>
                <div class="sidebar-divider"></div>
                <a href="<?php echo e(route('items.index')); ?>" class="sidebar-link <?php echo e(request()->is('items') ? 'active' : ''); ?>" onclick="toggleSidebar()">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                    Stock
                </a>
                <a href="<?php echo e(route('customers.index')); ?>" class="sidebar-link <?php echo e(request()->is('customers') ? 'active' : ''); ?>" onclick="toggleSidebar()">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    CRM
                </a>
                <a href="<?php echo e(route('reports.index')); ?>" class="sidebar-link <?php echo e(request()->is('reports') ? 'active' : ''); ?>" onclick="toggleSidebar()">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                    Data &amp; Reports
                </a>
                <a href="<?php echo e(route('settings.index')); ?>" class="sidebar-link <?php echo e(request()->is('settings') ? 'active' : ''); ?>" onclick="toggleSidebar()">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    Settings
                </a>
            </nav>
        </aside>

        <div class="search-modal-overlay" id="searchModal" onclick="closeSearch(event)">
            <div class="search-modal" onclick="event.stopPropagation()">
                <div class="search-modal-input-wrap">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input type="text" placeholder="Search pages, actions... (Ctrl+K)" class="search-modal-input" id="searchInput" oninput="filterSearch(this.value)">
                    <button class="search-modal-close" onclick="closeSearch()"><kbd>Esc</kbd></button>
                </div>
                <div class="search-modal-results" id="searchResults">
                    <div class="search-modal-section">Quick Links</div>
                    <a href="<?php echo e(route('dashboard')); ?>" class="search-modal-result">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                        <span>Dashboard</span>
                    </a>
                    <a href="<?php echo e(route('pos.index')); ?>" class="search-modal-result">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                        <span>POS Sales</span>
                    </a>
                    <a href="<?php echo e(route('sales.create')); ?>" class="search-modal-result">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                        <span>New Invoice</span>
                    </a>
                    <a href="<?php echo e(route('sales.index')); ?>" class="search-modal-result">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                        <span>Sales</span>
                    </a>
                    <a href="<?php echo e(route('quotes.index')); ?>" class="search-modal-result">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                        <span>Quotes</span>
                    </a>
                    <a href="<?php echo e(route('subscriptions.index')); ?>" class="search-modal-result">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg>
                        <span>Subscriptions</span>
                    </a>
                    <a href="<?php echo e(route('credit-notes.index')); ?>" class="search-modal-result">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="9" y1="15" x2="15" y2="15"/></svg>
                        <span>Credit Notes</span>
                    </a>
                    <a href="<?php echo e(route('items.index')); ?>" class="search-modal-result">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                        <span>Items (Stock)</span>
                    </a>
                    <a href="<?php echo e(route('customers.index')); ?>" class="search-modal-result">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        <span>Customers (CRM)</span>
                    </a>
                    <a href="<?php echo e(route('reports.index')); ?>" class="search-modal-result">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                        <span>Reports</span>
                    </a>
                    <a href="<?php echo e(route('settings.index')); ?>" class="search-modal-result">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        <span>Settings</span>
                    </a>
                </div>
            </div>
        </div>

        <nav class="top-navbar">
            <div class="top-nav-left">
                <button class="top-nav-icon-btn" onclick="toggleSidebar()">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                </button>
                <button class="top-nav-icon-btn">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                </button>
                <a href="<?php echo e(route('pos.index')); ?>" class="pos-badge-btn">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    POS
                </a>
                <button class="top-nav-icon-btn" onclick="openSearch()">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                </button>
            </div>

            <div class="top-nav-center">
                <div class="company-logo-box">
                    <div class="company-logo-icon">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none">
                            <rect width="24" height="24" rx="4" fill="#000080"/>
                            <path d="M6 16V8l6 4-6 4z" fill="#fff"/>
                            <path d="M12 16V8l6 4-6 4z" fill="#6666cc"/>
                        </svg>
                    </div>
                    <div class="company-logo-text">
                        <span class="company-name-bold">Digital Creativity</span>
                        <span class="company-name-sub">Tech Shop</span>
                    </div>
                </div>
            </div>

            <div class="top-nav-right">
                <div class="dropdown-wrap">
                    <button class="nav-dropdown-btn" onclick="toggleDropdown('settingsDrop')">
                        Admin Settings
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    <div class="nav-dropdown-menu" id="settingsDrop">
                        <a href="<?php echo e(route('settings.index')); ?>" class="nav-dropdown-item">General Settings</a>
                        <a href="<?php echo e(route('settings.index')); ?>" class="nav-dropdown-item">Tax Configuration</a>
                        <a href="<?php echo e(route('reports.index')); ?>" class="nav-dropdown-item">Reports</a>
                    </div>
                </div>

                <div class="dropdown-wrap">
                    <button class="top-nav-icon-btn" onclick="toggleDropdown('bellDrop')">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                        <span class="notif-badge"></span>
                    </button>
                    <div class="nav-dropdown-menu notif-dropdown" id="bellDrop">
                        <div class="notif-header">Notifications</div>
                        <div class="notif-item unread">
                            <div class="notif-dot"></div>
                            <div>
                                <div class="notif-title">New invoice created</div>
                                <div class="notif-time">5 min ago</div>
                            </div>
                        </div>
                        <div class="notif-item unread">
                            <div class="notif-dot"></div>
                            <div>
                                <div class="notif-title">Payment received - JD 150.00</div>
                                <div class="notif-time">1 hour ago</div>
                            </div>
                        </div>
                        <div class="notif-item">
                            <div class="notif-dot"></div>
                            <div>
                                <div class="notif-title">Low stock alert: Item #105</div>
                                <div class="notif-time">3 hours ago</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="dropdown-wrap">
                    <button class="top-nav-icon-btn" onclick="toggleDropdown('mailDrop')">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        <span class="notif-badge"></span>
                    </button>
                    <div class="nav-dropdown-menu notif-dropdown" id="mailDrop">
                        <div class="notif-header">Messages</div>
                        <div class="notif-item unread">
                            <div class="notif-dot"></div>
                            <div>
                                <div class="notif-title">System: Welcome to POS System</div>
                                <div class="notif-time">Today</div>
                            </div>
                        </div>
                        <div class="notif-item unread">
                            <div class="notif-dot"></div>
                            <div>
                                <div class="notif-title">Admin: Monthly report ready</div>
                                <div class="notif-time">Yesterday</div>
                            </div>
                        </div>
                        <div class="notif-item">
                            <div class="notif-dot"></div>
                            <div>
                                <div class="notif-title">Support: Your ticket #42 is resolved</div>
                                <div class="notif-time">2 days ago</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="online-badge">
                    <span class="online-dot"></span>
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    On since <span id="onlineTime"></span>
                </div>

                <div class="dropdown-wrap">
                    <button class="account-btn" onclick="toggleDropdown('accountDrop')">
                        <div class="account-avatar"><?php echo e(substr(Auth::user()->name, 0, 1)); ?></div>
                        <span class="account-label">Account</span>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    <div class="nav-dropdown-menu" id="accountDrop" style="right:0;left:auto">
                        <div class="nav-dropdown-header">
                            <?php echo e(Auth::user()->name); ?><br>
                            <span style="font-size:0.6875rem;color:#888888;font-weight:400"><?php echo e(Auth::user()->role ?? 'User'); ?></span>
                        </div>
                        <form method="POST" action="<?php echo e(route('logout')); ?>">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="nav-dropdown-item">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <nav class="secondary-navbar">
            <a href="<?php echo e(route('dashboard')); ?>" class="secondary-nav-item <?php echo e(request()->is('dashboard') ? 'active' : ''); ?>">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                Dashboard
            </a>

            <div class="secondary-nav-dropdown-wrap">
                <button class="secondary-nav-item <?php echo e(request()->is('sales*') || request()->is('/') ? 'active' : ''); ?>" onclick="toggleSalesDropdown(event)">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                    Sales
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div class="sales-dropdown" id="salesDrop">
                    <div class="sales-dropdown-item-wrap" onmouseenter="showSubmenu('subPos')" onmouseleave="hideSubmenu('subPos')">
                        <a href="<?php echo e(route('pos.index')); ?>" class="sales-dropdown-item <?php echo e(request()->is('/') ? 'active' : ''); ?>">
                            <span class="sales-dropdown-icon" style="background:#000080"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg></span>
                            <span class="sales-dropdown-label">POS Sales</span>
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                        </a>
                        <div class="sales-submenu" id="subPos">
                            <a href="<?php echo e(route('sales.create')); ?>" class="sales-submenu-item">New Invoice</a>
                            <a href="<?php echo e(route('pos.index')); ?>" class="sales-submenu-item">New Invoice V2 - Mobile</a>
                            <a href="<?php echo e(route('sales.index')); ?>" class="sales-submenu-item">Manage Invoices</a>
                        </div>
                    </div>
                    <div class="sales-dropdown-item-wrap" onmouseenter="showSubmenu('subSales')" onmouseleave="hideSubmenu('subSales')">
                        <a href="<?php echo e(route('sales.index')); ?>" class="sales-dropdown-item <?php echo e(request()->is('sales') && !request()->is('sales/*') ? 'active' : ''); ?>">
                            <span class="sales-dropdown-icon" style="background:#000080"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg></span>
                            <span class="sales-dropdown-label">Sales</span>
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                        </a>
                        <div class="sales-submenu" id="subSales">
                            <a href="<?php echo e(route('sales.create')); ?>" class="sales-submenu-item">New Invoice</a>
                            <a href="<?php echo e(route('sales.index')); ?>" class="sales-submenu-item">Manage Invoices</a>
                        </div>
                    </div>
                    <div class="sales-dropdown-item-wrap" onmouseenter="showSubmenu('subQuotes')" onmouseleave="hideSubmenu('subQuotes')">
                        <a href="<?php echo e(route('quotes.index')); ?>" class="sales-dropdown-item <?php echo e(request()->is('sales/quotes') ? 'active' : ''); ?>">
                            <span class="sales-dropdown-icon" style="background:#000080"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg></span>
                            <span class="sales-dropdown-label">Quotes</span>
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                        </a>
                        <div class="sales-submenu" id="subQuotes">
                            <a href="<?php echo e(route('quotes.index')); ?>" class="sales-submenu-item">New Quote</a>
                            <a href="<?php echo e(route('quotes.index')); ?>" class="sales-submenu-item">Manage Quotes</a>
                        </div>
                    </div>
                    <div class="sales-dropdown-item-wrap" onmouseenter="showSubmenu('subSubs')" onmouseleave="hideSubmenu('subSubs')">
                        <a href="<?php echo e(route('subscriptions.index')); ?>" class="sales-dropdown-item <?php echo e(request()->is('sales/subscriptions') ? 'active' : ''); ?>">
                            <span class="sales-dropdown-icon" style="background:#000080"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg></span>
                            <span class="sales-dropdown-label">Subscriptions</span>
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                        </a>
                        <div class="sales-submenu" id="subSubs">
                            <a href="<?php echo e(route('subscriptions.index')); ?>" class="sales-submenu-item">New Subscription</a>
                            <a href="<?php echo e(route('subscriptions.index')); ?>" class="sales-submenu-item">Manage Subscriptions</a>
                        </div>
                    </div>
                    <div class="sales-dropdown-divider"></div>
                    <div class="sales-dropdown-item-wrap" onmouseenter="showSubmenu('subCredit')" onmouseleave="hideSubmenu('subCredit')">
                        <a href="<?php echo e(route('credit-notes.index')); ?>" class="sales-dropdown-item <?php echo e(request()->is('sales/credit-notes') ? 'active' : ''); ?>">
                            <span class="sales-dropdown-icon" style="background:#dc2626"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="9" y1="15" x2="15" y2="15"/></svg></span>
                            <span class="sales-dropdown-label">Credit Notes</span>
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                        </a>
                        <div class="sales-submenu" id="subCredit">
                            <a href="<?php echo e(route('credit-notes.index')); ?>" class="sales-submenu-item">New Credit Note</a>
                            <a href="<?php echo e(route('credit-notes.index')); ?>" class="sales-submenu-item">Manage Credit Notes</a>
                        </div>
                    </div>
                </div>
            </div>

            <a href="<?php echo e(route('items.index')); ?>" class="secondary-nav-item <?php echo e(request()->is('items') ? 'active' : ''); ?>">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                Stock
            </a>
            <a href="<?php echo e(route('customers.index')); ?>" class="secondary-nav-item <?php echo e(request()->is('customers') ? 'active' : ''); ?>">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                CRM
            </a>
            <a href="<?php echo e(route('reports.index')); ?>" class="secondary-nav-item <?php echo e(request()->is('reports') ? 'active' : ''); ?>">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                Data &amp; Reports
            </a>
            <a href="<?php echo e(route('settings.index')); ?>" class="secondary-nav-item <?php echo e(request()->is('settings') ? 'active' : ''); ?>">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Data Export Import
            </a>
        </nav>

        <main class="main-content">
            <?php echo $__env->yieldContent('content'); ?>
        </main>
    </div>

    <script>
        function toggleSidebar() {
            var panel = document.getElementById('sidebarPanel');
            var overlay = document.getElementById('sidebarOverlay');
            panel.classList.toggle('open');
            overlay.classList.toggle('show');
        }

        function toggleDropdown(id) {
            var el = document.getElementById(id);
            var isOpen = el.classList.contains('show');
            document.querySelectorAll('.nav-dropdown-menu').forEach(function(d) { d.classList.remove('show'); });
            if (!isOpen) el.classList.add('show');
        }

        function toggleSalesDropdown(e) {
            e.stopPropagation();
            var drop = document.getElementById('salesDrop');
            drop.classList.toggle('show');
        }

        function showSubmenu(id) {
            document.getElementById(id).classList.add('show');
        }

        function hideSubmenu(id) {
            document.getElementById(id).classList.remove('show');
        }

        function openSearch() {
            document.getElementById('searchModal').classList.add('show');
            document.getElementById('searchInput').focus();
        }

        function closeSearch(e) {
            if (!e || e.target === document.getElementById('searchModal')) {
                document.getElementById('searchModal').classList.remove('show');
                document.getElementById('searchInput').value = '';
                filterSearch('');
            }
        }

        function filterSearch(q) {
            var items = document.querySelectorAll('#searchResults .search-modal-result');
            var lower = q.toLowerCase();
            items.forEach(function(item) {
                var text = item.textContent.toLowerCase();
                item.style.display = (!q || text.indexOf(lower) !== -1) ? '' : 'none';
            });
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.getElementById('searchModal').classList.remove('show');
                document.getElementById('sidebarPanel').classList.remove('open');
                document.getElementById('sidebarOverlay').classList.remove('show');
                document.querySelectorAll('.nav-dropdown-menu').forEach(function(d) { d.classList.remove('show'); });
                document.getElementById('salesDrop').classList.remove('show');
            }
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                openSearch();
            }
        });

        document.addEventListener('click', function(e) {
            if (!e.target.closest('.dropdown-wrap')) {
                document.querySelectorAll('.nav-dropdown-menu').forEach(function(d) { d.classList.remove('show'); });
            }
            if (!e.target.closest('.secondary-nav-dropdown-wrap')) {
                document.getElementById('salesDrop').classList.remove('show');
                document.querySelectorAll('.sales-submenu').forEach(function(s) { s.classList.remove('show'); });
            }
        });

        function updateTime() {
            var now = new Date();
            var h = now.getHours();
            var m = now.getMinutes();
            var s = now.getSeconds();
            var ampm = h >= 12 ? 'PM' : 'AM';
            h = h % 12;
            h = h ? h : 12;
            m = m < 10 ? '0' + m : m;
            s = s < 10 ? '0' + s : s;
            document.getElementById('onlineTime').textContent = h + ':' + m + ':' + s + ' ' + ampm;
        }
        updateTime();
        setInterval(updateTime, 1000);
    </script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\Users\ALI.A.SALAH\Desktop\pos\resources\views/layouts/app.blade.php ENDPATH**/ ?>