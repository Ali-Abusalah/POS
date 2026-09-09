<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Setting;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class InvoicePrintController extends Controller
{
    private array $currencyData;

    public function __construct()
    {
        $this->currencyData = [
            'primarySymbol' => Setting::getValue('primary_symbol') ?? '$',
            'primaryCurrency' => Setting::getValue('primary_currency') ?? 'USD',
            'secondarySymbol' => Setting::getValue('secondary_symbol') ?? '',
            'secondaryCurrency' => Setting::getValue('secondary_currency') ?? '',
            'exchangeRate' => (float) (Setting::getValue('exchange_rate') ?? 0),
        ];
    }

    public function printThermal(Invoice $invoice)
    {
        abort_unless($invoice->exists, 404);
        $invoice->load('items', 'creator');
        $taxRate = Setting::getValue('tax_rate') ?? '16';
        $baseUrl = Setting::getValue('site_url') ?? request()->getSchemeAndHttpHost();
        $invoiceUrl = rtrim($baseUrl, '/') . '/admin/invoices/' . $invoice->id;

        return view('invoices.thermal', array_merge(compact('invoice', 'taxRate', 'invoiceUrl'), $this->currencyData));
    }

    public function printA4(Invoice $invoice)
    {
        abort_unless($invoice->exists, 404);
        $invoice->load('items', 'creator');
        $taxRate = Setting::getValue('tax_rate') ?? '16';
        $baseUrl = Setting::getValue('site_url') ?? request()->getSchemeAndHttpHost();
        $invoiceUrl = rtrim($baseUrl, '/') . '/admin/invoices/' . $invoice->id;

        return view('invoices.a4', array_merge(compact('invoice', 'taxRate', 'invoiceUrl'), $this->currencyData));
    }
}
