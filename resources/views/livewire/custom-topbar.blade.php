<div>
    <style>
        /* Hide scrollbar on secondary menu and page */
        .secondary-nav::-webkit-scrollbar { display: none; }
        .secondary-nav { -ms-overflow-style: none; scrollbar-width: none; }
        html { overflow-y: scroll; scrollbar-width: none; }
        html::-webkit-scrollbar { display: none; }
    </style>
    {{-- TOP BAR - Dark Navy --}}
    <div style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); height: 56px; display: flex; align-items: center; justify-content: space-between; padding: 0 1.25rem; position: sticky; top: 0; z-index: 50; box-shadow: 0 2px 8px rgba(0,0,0,0.15);">
        {{-- Left --}}
        <div style="display: flex; align-items: center; gap: 1rem;">
            {{-- Hamburger --}}
            <button wire:click="$dispatch('toggle-sidebar')" style="background:none;border:none;color:#94a3b8;cursor:pointer;padding:4px;display:flex;align-items:center;">
                <svg style="width:22px;height:22px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            {{-- Location --}}
            <svg style="width:18px;height:18px;color:#6ee7b7;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            {{-- POS Button --}}
            <a href="{{ url('/admin/p-o-s') }}" style="background: linear-gradient(135deg, #0ea5e9, #06b6d4); color: #fff; padding: 0.45rem 1.25rem; border-radius: 9999px; font-size: 0.8rem; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 0.4rem; box-shadow: 0 2px 8px rgba(14,165,233,0.4);">
                <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                POS
            </a>
            {{-- Search --}}
            <svg style="width:18px;height:18px;color:#94a3b8;cursor:pointer;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>

        {{-- Center - Logo --}}
        <div style="position: absolute; left: 50%; transform: translateX(-50%);">
            <img src="/logo.png" alt="Logo" style="height: 32px; width: auto; filter: brightness(1.2);" />
        </div>

        {{-- Right --}}
        <div style="display: flex; align-items: center; gap: 1rem;">
            {{-- Admin Settings --}}
            <div style="position: relative;" x-data="{ open: false }" @click.outside="open = false">
                <button @click="open = !open" style="background: none; border: none; color: #e2e8f0; font-size: 0.8rem; font-weight: 500; cursor: pointer; display: flex; align-items: center; gap: 0.3rem;">
                    Admin Settings
                    <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                @if(auth()->user()?->role === 'admin')
                <div x-show="open" @click.outside="open = false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" style="position: absolute; right: 0; top: 100%; margin-top: 0.5rem; background: white; border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.15); min-width: 180px; z-index: 100; padding: 0.5rem 0;">
                    <a href="{{ url('/admin/settings') }}" style="display: block; padding: 0.5rem 1rem; color: #334155; text-decoration: none; font-size: 0.85rem;">System Settings</a>
                    <a href="{{ url('/admin/branches') }}" style="display: block; padding: 0.5rem 1rem; color: #334155; text-decoration: none; font-size: 0.85rem;">Branches</a>
                </div>
                @endif
            </div>

            {{-- Notification Bell --}}
            <div style="position: relative;" x-data="{ notifOpen: false }" @click.outside="notifOpen = false">
                <button @click="notifOpen = !notifOpen" style="position: relative; background: none; border: none; cursor: pointer; padding: 4px; display: flex; align-items: center;">
                    <svg style="width:20px;height:20px;color:#94a3b8;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    @if($notificationCount > 0)
                        <span style="position: absolute; top: -4px; right: -6px; background: #ef4444; color: white; font-size: 0.6rem; font-weight: 700; min-width: 16px; height: 16px; border-radius: 9999px; display: flex; align-items: center; justify-content: center; padding: 0 4px;">{{ $notificationCount > 99 ? '99+' : $notificationCount }}</span>
                    @endif
                </button>
                <div x-show="notifOpen" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click.stop style="position: absolute; right: 0; top: 100%; margin-top: 0.5rem; background: white; border-radius: 12px; box-shadow: 0 4px 24px rgba(0,0,0,0.15); width: 320px; z-index: 100;">
                    <div style="padding: 0.75rem 1rem; border-bottom: 1px solid #e2e8f0; font-weight: 700; font-size: 0.85rem; color: #1e293b;">Notifications</div>
                    <div style="max-height: 320px; overflow-y: auto;">
                        @forelse($recentNotifications as $notif)
                            <div style="padding: 0.6rem 1rem; border-bottom: 1px solid #f1f5f9; display: flex; gap: 0.5rem; align-items: flex-start;">
                                <div style="width: 8px; height: 8px; border-radius: 50%; margin-top: 6px; flex-shrink: 0; background: {{ $notif['type'] === 'create' ? '#22c55e' : ($notif['type'] === 'delete' ? '#ef4444' : '#3b82f6') }};"></div>
                                <div style="flex: 1; min-width: 0;">
                                    <div style="font-size: 0.8rem; color: #334155; line-height: 1.4;">{{ $notif['message'] }}</div>
                                    <div style="font-size: 0.7rem; color: #94a3b8; margin-top: 2px;">{{ $notif['time'] }}</div>
                                </div>
                            </div>
                        @empty
                            <div style="padding: 2rem 1rem; text-align: center; color: #94a3b8; font-size: 0.85rem;">No notifications</div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Messages (placeholder) --}}
            <div style="position: relative; cursor: pointer;">
                <svg style="width:20px;height:20px;color:#94a3b8;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </div>

            {{-- Clock Status Toggle --}}
            <div wire:click="toggleClock" style="display: flex; align-items: center; gap: 0.4rem; padding: 0.25rem 0.6rem; background: {{ $isClockedIn ? 'rgba(34,197,94,0.2)' : 'rgba(255,255,255,0.1)' }}; border-radius: 9999px; cursor: pointer; transition: all 0.2s;">
                <svg style="width:16px;height:16px;color:{{ $isClockedIn ? '#22c55e' : '#94a3b8' }};" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span style="color: {{ $isClockedIn ? '#22c55e' : '#94a3b8' }}; font-size: 0.7rem; font-weight: 600;">{{ $isClockedIn ? 'On' : 'Off' }}</span>
                @if($clockInTime)
                    <span style="color: #6ee7b7; font-size: 0.65rem;">since {{ $clockInTime }}</span>
                @endif
            </div>

            {{-- Account --}}
            <div style="position: relative; cursor: pointer;" x-data="{ open: false }" @click.outside="open = false">
                <div @click="open = !open" style="display: flex; align-items: center; gap: 0.5rem;">
                    <div style="width: 30px; height: 30px; border-radius: 50%; background: linear-gradient(135deg, #0ea5e9, #06b6d4); display: flex; align-items: center; justify-content: center; color: white; font-size: 0.75rem; font-weight: 700;">
                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <span style="color: #e2e8f0; font-size: 0.8rem; font-weight: 500;">Account</span>
                    <svg style="width:14px;height:14px;color:#94a3b8;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>
                <div x-show="open" @click.outside="open = false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" style="position: absolute; right: 0; top: 100%; margin-top: 0.5rem; background: white; border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.15); min-width: 180px; z-index: 100; padding: 0.5rem 0;">
                    <a href="{{ url('/admin/profile') }}" style="display: block; padding: 0.5rem 1rem; color: #334155; text-decoration: none; font-size: 0.85rem;">My Profile</a>
                    <form method="POST" action="{{ url('/admin/logout') }}">
                        @csrf
                        <button type="submit" style="display: block; width: 100%; text-align: left; padding: 0.5rem 1rem; color: #ef4444; text-decoration: none; font-size: 0.85rem; background: none; border: none; cursor: pointer;">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- SECONDARY MENU - White --}}
    <div class="secondary-nav" style="background: #fff; border-bottom: 1px solid #e2e8f0; padding: 0 1.25rem; display: flex; align-items: center; gap: 0; white-space: nowrap; position: sticky; top: 56px; z-index: 40; box-shadow: 0 1px 3px rgba(0,0,0,0.04); min-height: 42px;">
        @php
            $currentUrl = request()->url();
            $currentPath = request()->path();
        @endphp

        {{-- Dashboard --}}
        @php $isActive = $currentPath === 'admin' || $currentPath === 'admin/'; @endphp
        <a href="{{ url('/admin/admin-dashboard') }}" style="display: flex; align-items: center; gap: 0.35rem; padding: 0.75rem 0.875rem; font-size: 0.8rem; font-weight: {{ $isActive ? '600' : '400' }}; color: {{ $isActive ? '#0ea5e9' : '#64748b' }}; text-decoration: none; border-bottom: {{ $isActive ? '2px solid #0ea5e9' : '2px solid transparent' }}; transition: all 0.2s; white-space: nowrap;">
            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            Dashboard
        </a>

        {{-- Sales Dropdown (Click to open) --}}
        <div style="position: relative;" x-data="{ open: false, subOpen: false }" @click.outside="open = false; subOpen = false">
            <button @click="open = !open; subOpen = false" style="display: flex; align-items: center; gap: 0.35rem; padding: 0.75rem 0.875rem; font-size: 0.8rem; font-weight: {{ str_contains($currentPath, 'pos') || str_contains($currentPath, 'invoice') || str_contains($currentPath, 'quote') || str_contains($currentPath, 'subscription') || str_contains($currentPath, 'credit') ? '600' : '500' }}; color: {{ str_contains($currentPath, 'pos') || str_contains($currentPath, 'invoice') || str_contains($currentPath, 'quote') || str_contains($currentPath, 'subscription') || str_contains($currentPath, 'credit') ? '#0ea5e9' : '#64748b' }}; text-decoration: none; border-bottom: {{ str_contains($currentPath, 'pos') || str_contains($currentPath, 'invoice') || str_contains($currentPath, 'quote') || str_contains($currentPath, 'subscription') || str_contains($currentPath, 'credit') ? '2px solid #0ea5e9' : '2px solid transparent' }}; transition: all 0.2s; white-space: nowrap; background: none; border-left: none; border-right: none; border-top: none; cursor: pointer;">
                <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                Sales
                <span style="display:inline-block; width:5px; height:5px; border-right:1.5px solid #94a3b8; border-bottom:1.5px solid #94a3b8; transform: rotate(45deg); transition: transform 0.2s; margin-top:-2px;" :style="open ? 'transform: rotate(-135deg)' : ''"></span>
            </button>
            <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click.stop style="position: absolute; left: 0; top: 100%; margin-top: 4px; background: white; border-radius: 10px; box-shadow: 0 8px 30px rgba(0,0,0,0.12), 0 2px 8px rgba(0,0,0,0.08); min-width: 180px; z-index: 9999; padding: 0.25rem 0; border: 1px solid #e2e8f0;">

                <a href="{{ url('/admin/p-o-s-sales-history') }}" @click="open = false" style="display: flex; align-items: center; gap: 0.5rem; padding: 0.4rem 0.75rem; margin: 0 0.2rem; color: #334155; text-decoration: none; font-size: 0.75rem; font-weight: 500; border-radius: 6px; transition: all 0.15s;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">
                    <svg style="width: 13px; height: 13px; color: #6366f1;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    POS Sales
                </a>

                <div style="position: relative;">
                    <div @click="subOpen = !subOpen" style="display: flex; align-items: center; justify-content: space-between; padding: 0.4rem 0.75rem; margin: 0 0.2rem; color: #334155; font-size: 0.75rem; font-weight: 500; cursor: pointer; border-radius: 6px; transition: all 0.15s;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">
                        <span style="display: flex; align-items: center; gap: 0.5rem;">
                            <svg style="width: 13px; height: 13px; color: #6366f1;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            Sales
                        </span>
                        <span style="display:inline-block; width:6px; height:6px; border-right:1.5px solid #94a3b8; border-bottom:1.5px solid #94a3b8; transform: rotate(-45deg); transition: transform 0.15s;" :style="subOpen ? 'transform: rotate(45deg)' : ''"></span>
                    </div>
                    <div x-show="subOpen" x-transition:enter="transition ease-out duration-75" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-50" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click.stop style="position: absolute; left: 100%; top: -2px; margin-left: 4px; background: white; border-radius: 10px; box-shadow: 0 8px 30px rgba(0,0,0,0.12), 0 2px 8px rgba(0,0,0,0.08); min-width: 170px; z-index: 100; padding: 0.25rem 0; border: 1px solid #e2e8f0;">
                        <a href="{{ url('/admin/p-o-s') }}" @click="open = false; subOpen = false" style="display: flex; align-items: center; gap: 0.5rem; padding: 0.4rem 0.75rem; margin: 0 0.2rem; color: #334155; text-decoration: none; font-size: 0.75rem; font-weight: 500; border-radius: 6px; transition: all 0.15s;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">
                            <svg style="width: 13px; height: 13px; color: #22c55e;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                            New Invoice
                        </a>
                        <a href="{{ url('/admin/invoices') }}" @click="open = false; subOpen = false" style="display: flex; align-items: center; gap: 0.5rem; padding: 0.4rem 0.75rem; margin: 0 0.2rem; color: #334155; text-decoration: none; font-size: 0.75rem; font-weight: 500; border-radius: 6px; transition: all 0.15s;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">
                            <svg style="width: 13px; height: 13px; color: #3b82f6;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                            Manage Invoices
                        </a>
                    </div>
                </div>

                <div style="height: 1px; background: #f1f5f9; margin: 0.2rem 0.5rem;"></div>

                <a href="{{ url('/admin/quotes') }}" @click="open = false" style="display: flex; align-items: center; gap: 0.5rem; padding: 0.4rem 0.75rem; margin: 0 0.2rem; color: #334155; text-decoration: none; font-size: 0.75rem; font-weight: 500; border-radius: 6px; transition: all 0.15s;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">
                    <svg style="width: 13px; height: 13px; color: #f59e0b;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Quotes
                </a>

                <a href="{{ url('/admin/subscriptions') }}" @click="open = false" style="display: flex; align-items: center; gap: 0.5rem; padding: 0.4rem 0.75rem; margin: 0 0.2rem; color: #334155; text-decoration: none; font-size: 0.75rem; font-weight: 500; border-radius: 6px; transition: all 0.15s;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">
                    <svg style="width: 13px; height: 13px; color: #8b5cf6;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Subscriptions
                </a>

                <a href="{{ url('/admin/credit-notes') }}" @click="open = false" style="display: flex; align-items: center; gap: 0.5rem; padding: 0.4rem 0.75rem; margin: 0 0.2rem; color: #334155; text-decoration: none; font-size: 0.75rem; font-weight: 500; border-radius: 6px; transition: all 0.15s;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">
                    <svg style="width: 13px; height: 13px; color: #ef4444;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                    Credit Notes
                </a>
            </div>
        </div>

        {{-- Stock --}}
        @php $isActive = str_contains($currentPath, 'items'); @endphp
        <a href="{{ url('/admin/items') }}" style="display: flex; align-items: center; gap: 0.35rem; padding: 0.75rem 0.875rem; font-size: 0.8rem; font-weight: {{ $isActive ? '600' : '400' }}; color: {{ $isActive ? '#0ea5e9' : '#64748b' }}; text-decoration: none; border-bottom: {{ $isActive ? '2px solid #0ea5e9' : '2px solid transparent' }}; transition: all 0.2s; white-space: nowrap;">
            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            Stock
        </a>

        {{-- CRM --}}
        @php $isActive = str_contains($currentPath, 'customers'); @endphp
        <a href="{{ url('/admin/customers') }}" style="display: flex; align-items: center; gap: 0.35rem; padding: 0.75rem 0.875rem; font-size: 0.8rem; font-weight: {{ $isActive ? '600' : '400' }}; color: {{ $isActive ? '#0ea5e9' : '#64748b' }}; text-decoration: none; border-bottom: {{ $isActive ? '2px solid #0ea5e9' : '2px solid transparent' }}; transition: all 0.2s; white-space: nowrap;">
            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            CRM
        </a>

        {{-- Data & Reports --}}
        @php $isActive = str_contains($currentPath, 'reports'); @endphp
        <a href="{{ url('/admin/reports') }}" style="display: flex; align-items: center; gap: 0.35rem; padding: 0.75rem 0.875rem; font-size: 0.8rem; font-weight: {{ $isActive ? '600' : '400' }}; color: {{ $isActive ? '#0ea5e9' : '#64748b' }}; text-decoration: none; border-bottom: {{ $isActive ? '2px solid #0ea5e9' : '2px solid transparent' }}; transition: all 0.2s; white-space: nowrap;">
            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            Data & Reports
        </a>

        {{-- Data Export Import --}}
        @php $isActive = str_contains($currentPath, 'reports-export'); @endphp
        <a href="{{ url('/admin/reports-export') }}" style="display: flex; align-items: center; gap: 0.35rem; padding: 0.75rem 0.875rem; font-size: 0.8rem; font-weight: {{ $isActive ? '600' : '400' }}; color: {{ $isActive ? '#0ea5e9' : '#64748b' }}; text-decoration: none; border-bottom: {{ $isActive ? '2px solid #0ea5e9' : '2px solid transparent' }}; transition: all 0.2s; white-space: nowrap;">
            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
            Data Export Import
        </a>
    </div>
</div>
