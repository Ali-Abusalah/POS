@push('styles')
<style>
.stg-layout { max-width: 520px; }
</style>
@endpush

<x-ui.layout title="System Settings">
    <div class="stg-layout">
        <form wire:submit="save">
            {{-- Site URL (for QR codes) --}}
            <div class="ui-card" style="margin-bottom:1rem;">
                <div class="ui-card-header">
                    <span style="font-weight:700;font-size:0.9375rem;">Site URL (for QR codes)</span>
                </div>
                <div class="ui-card-body">
                    <div style="margin-bottom:1rem;">
                        <label class="ui-input-label">Base URL</label>
                        <input type="url" wire:model="site_url" class="ui-input" placeholder="e.g. http://192.168.1.100:8000" />
                        <p style="font-size:0.75rem; color:var(--text-muted); margin-top:0.375rem;">Used in QR codes on invoices. Must be accessible from mobile devices on your network.</p>
                    </div>
                </div>
            </div>

            {{-- Tax Configuration --}}
            <div class="ui-card" style="margin-bottom:1rem;">
                <div class="ui-card-header">
                    <span style="font-weight:700;font-size:0.9375rem;">Tax Configuration</span>
                </div>
                <div class="ui-card-body">
                    <div style="margin-bottom:1rem;">
                        <label class="ui-input-label">Tax Rate</label>
                        <div class="ui-input-group">
                            <input type="number" wire:model="tax_rate" min="0" max="100" step="0.01" class="ui-input" />
                            <span class="ui-input-suffix">%</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Primary Currency --}}
            <div class="ui-card" style="margin-bottom:1rem;">
                <div class="ui-card-header">
                    <span style="font-weight:700;font-size:0.9375rem;">Primary Currency</span>
                </div>
                <div class="ui-card-body">
                    <div class="ui-grid-2">
                        <div>
                            <label class="ui-input-label">Currency Code</label>
                            <select wire:model="primary_currency" class="ui-select">
                                <option value="JOD">JOD - Jordanian Dinar</option>
                                <option value="USD">USD - US Dollar</option>
                                <option value="EUR">EUR - Euro</option>
                                <option value="IQD">IQD - Iraqi Dinar</option>
                                <option value="SAR">SAR - Saudi Riyal</option>
                                <option value="AED">AED - UAE Dirham</option>
                                <option value="GBP">GBP - British Pound</option>
                                <option value="TRY">TRY - Turkish Lira</option>
                            </select>
                        </div>
                        <div>
                            <label class="ui-input-label">Symbol</label>
                            <input type="text" wire:model="primary_symbol" class="ui-input" placeholder="e.g. $" />
                        </div>
                    </div>
                </div>
            </div>

            {{-- Secondary Currency --}}
            <div class="ui-card" style="margin-bottom:1rem;">
                <div class="ui-card-header">
                    <span style="font-weight:700;font-size:0.9375rem;">Secondary Currency (for printing)</span>
                </div>
                <div class="ui-card-body">
                    <div class="ui-grid-2" style="margin-bottom:1rem;">
                        <div>
                            <label class="ui-input-label">Currency Code</label>
                            <select wire:model="secondary_currency" class="ui-select">
                                <option value="IQD">IQD - Iraqi Dinar</option>
                                <option value="USD">USD - US Dollar</option>
                                <option value="EUR">EUR - Euro</option>
                                <option value="SAR">SAR - Saudi Riyal</option>
                                <option value="AED">AED - UAE Dirham</option>
                                <option value="GBP">GBP - British Pound</option>
                                <option value="TRY">TRY - Turkish Lira</option>
                            </select>
                        </div>
                        <div>
                            <label class="ui-input-label">Symbol</label>
                            <input type="text" wire:model="secondary_symbol" class="ui-input" placeholder="e.g. IQD" />
                        </div>
                    </div>
                    <div>
                        <label class="ui-input-label">Exchange Rate (1 {{ $primary_currency }} = ? {{ $secondary_currency }})</label>
                        <div class="ui-input-group">
                            <input type="number" wire:model="exchange_rate" min="0" step="0.01" class="ui-input" />
                            <span class="ui-input-suffix">{{ $secondary_currency }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Monthly Targets --}}
            <div class="ui-card" style="margin-bottom:1rem;">
                <div class="ui-card-header">
                    <span style="font-weight:700;font-size:0.9375rem;">Monthly Targets</span>
                </div>
                <div class="ui-card-body">
                    <div class="ui-grid-2" style="margin-bottom:1rem;">
                        <div>
                            <label class="ui-input-label">Income Target</label>
                            <input type="number" wire:model="target_income" min="0" step="0.01" class="ui-input" placeholder="999999" />
                        </div>
                        <div>
                            <label class="ui-input-label">Expenses Target</label>
                            <input type="number" wire:model="target_expenses" min="0" step="0.01" class="ui-input" placeholder="999999" />
                        </div>
                    </div>
                    <div class="ui-grid-2">
                        <div>
                            <label class="ui-input-label">Sales Target</label>
                            <input type="number" wire:model="target_sales" min="0" step="0.01" class="ui-input" placeholder="999999" />
                        </div>
                        <div>
                            <label class="ui-input-label">Net Income Target</label>
                            <input type="number" wire:model="target_net_income" min="0" step="0.01" class="ui-input" placeholder="999999" />
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="ui-btn ui-btn-primary">Save Settings</button>
        </form>
    </div>
</x-ui.layout>
