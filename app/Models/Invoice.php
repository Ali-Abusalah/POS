<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_number',
        'customer_id',
        'customer_name',
        'customer_contact',
        'subtotal',
        'tax_total',
        'discount_value',
        'discount_type',
        'discount_amount',
        'grand_total',
        'status',
        'payment_method',
        'amount_paid',
        'card_amount',
        'other_amount',
        'other_payment_method',
        'change_amount',
        'void_reason',
        'refund_amount',
        'created_by',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_total' => 'decimal:2',
        'discount_value' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'card_amount' => 'decimal:2',
        'other_amount' => 'decimal:2',
        'change_amount' => 'decimal:2',
        'refund_amount' => 'decimal:2',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function generateInvoiceNumber(): string
    {
        $year = date('Y');
        $prefix = "INV-{$year}-";

        return DB::transaction(function () use ($prefix, $year) {
            $lastInvoice = static::query()
                ->where('invoice_number', 'like', "{$prefix}%")
                ->orderByDesc('invoice_number')
                ->first();

            if ($lastInvoice) {
                $lastNumber = (int) substr($lastInvoice->invoice_number, -4);
                $nextNumber = $lastNumber + 1;
            } else {
                $nextNumber = 1;
            }

            return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        });
    }

    public function scopeSearch($query, ?string $search, ?string $customerName, ?string $invoiceNumber, ?string $dateFrom, ?string $dateTo)
    {
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%");
            });
        }

        if ($customerName) {
            $query->where('customer_name', 'like', "%{$customerName}%");
        }

        if ($invoiceNumber) {
            $query->where('invoice_number', 'like', "%{$invoiceNumber}%");
        }

        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        return $query;
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'paid' => 'success',
            'void' => 'danger',
            'refunded' => 'warning',
            default => 'gray',
        };
    }
}
