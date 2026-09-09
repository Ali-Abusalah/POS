<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'balance',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function totalPurchases(): float
    {
        return (float) $this->invoices()
            ->where('status', 'paid')
            ->sum('grand_total');
    }

    public function totalInvoices(): int
    {
        return $this->invoices()->count();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
