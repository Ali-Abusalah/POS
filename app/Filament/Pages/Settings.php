<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class Settings extends Page
{
    protected static ?int $navigationSort = 10;

    protected string $view = 'filament.pages.settings';

    protected static ?string $title = 'System Settings';

    public ?string $tax_rate = null;

    public ?string $site_url = null;
    public ?string $primary_currency = 'JOD';
    public ?string $primary_symbol = 'JD';
    public ?string $secondary_currency = 'IQD';
    public ?string $secondary_symbol = 'IQD';
    public ?string $exchange_rate = '1460';
    public ?string $target_income = '999999';
    public ?string $target_expenses = '999999';
    public ?string $target_sales = '999999';
    public ?string $target_net_income = '999999';

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-cog-6-tooth';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Management';
    }

    public static function canAccess(): bool
    {
        $user = auth()->user();
        return $user && $user->role === 'admin';
    }

    public function mount(): void
    {
        $this->tax_rate = Setting::getValue('tax_rate') ?? '16';
        $this->site_url = Setting::getValue('site_url') ?? request()->getSchemeAndHttpHost();
        $this->primary_currency = Setting::getValue('primary_currency') ?? 'JOD';
        $this->primary_symbol = Setting::getValue('primary_symbol') ?? 'JD';
        $this->secondary_currency = Setting::getValue('secondary_currency') ?? 'IQD';
        $this->secondary_symbol = Setting::getValue('secondary_symbol') ?? 'IQD';
        $this->exchange_rate = Setting::getValue('exchange_rate') ?? '1460';
        $this->target_income = Setting::getValue('target_income') ?? '999999';
        $this->target_expenses = Setting::getValue('target_expenses') ?? '999999';
        $this->target_sales = Setting::getValue('target_sales') ?? '999999';
        $this->target_net_income = Setting::getValue('target_net_income') ?? '999999';
    }

    public function save(): void
    {
        Setting::setValue('tax_rate', $this->tax_rate);
        Setting::setValue('site_url', $this->site_url);
        Setting::setValue('primary_currency', $this->primary_currency);
        Setting::setValue('primary_symbol', $this->primary_symbol);
        Setting::setValue('secondary_currency', $this->secondary_currency);
        Setting::setValue('secondary_symbol', $this->secondary_symbol);
        Setting::setValue('exchange_rate', $this->exchange_rate);
        Setting::setValue('target_income', $this->target_income);
        Setting::setValue('target_expenses', $this->target_expenses);
        Setting::setValue('target_sales', $this->target_sales);
        Setting::setValue('target_net_income', $this->target_net_income);

        Notification::make()
            ->title('Settings saved successfully')
            ->success()
            ->send();
    }
}
