<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SettingApiController extends Controller
{
    private const KEYS = [
        'tax_rate', 'site_url', 'primary_currency', 'primary_symbol',
        'secondary_currency', 'secondary_symbol', 'exchange_rate',
        'target_income', 'target_expenses', 'target_sales', 'target_net_income',
    ];

    public function index(): JsonResponse
    {
        $settings = [];
        foreach (self::KEYS as $key) {
            $settings[$key] = Setting::getValue($key);
        }

        return response()->json(['settings' => $settings]);
    }

    public function publicSettings(): JsonResponse
    {
        return response()->json([
            'tax_rate' => (float) (Setting::getValue('tax_rate') ?? 16),
            'primary_symbol' => Setting::getValue('primary_symbol') ?? 'JD',
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $user = $request->user();
        if ($user?->role !== 'admin') {
            return response()->json(['error' => 'Only admins can change settings.'], 403);
        }

        $data = $request->validate([
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'site_url' => 'nullable|url',
            'primary_currency' => 'nullable|string|max:10',
            'primary_symbol' => 'nullable|string|max:10',
            'secondary_currency' => 'nullable|string|max:10',
            'secondary_symbol' => 'nullable|string|max:10',
            'exchange_rate' => 'nullable|numeric|min:0',
            'target_income' => 'nullable|numeric|min:0',
            'target_expenses' => 'nullable|numeric|min:0',
            'target_sales' => 'nullable|numeric|min:0',
            'target_net_income' => 'nullable|numeric|min:0',
        ]);

        foreach ($data as $key => $value) {
            Setting::setValue($key, (string) $value);
        }

        return response()->json(['success' => true]);
    }
}
