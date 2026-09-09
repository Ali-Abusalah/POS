<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class Item extends Model
{
    protected $fillable = [
        'name',
        'image',
        'code',
        'barcode',
        'category',
        'description',
        'unit',
        'pre_tax_price',
        'quantity',
        'low_stock_threshold',
        'is_active',
    ];

    protected $casts = [
        'pre_tax_price' => 'decimal:2',
        'quantity' => 'integer',
        'low_stock_threshold' => 'integer',
        'is_active' => 'boolean',
    ];

    protected $appends = ['image_url'];

    public function invoiceItems(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function getPriceIncludingTaxAttribute(): float
    {
        $taxRate = (float) (Setting::getValue('tax_rate') ?? 16);

        return round($this->pre_tax_price * (1 + $taxRate / 100), 2);
    }

    public function getTaxAmountAttribute(): float
    {
        $taxRate = (float) (Setting::getValue('tax_rate') ?? 16);

        return round($this->pre_tax_price * $taxRate / 100, 2);
    }

    public function getImageUrlAttribute(): ?string
    {
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            return Storage::disk('public')->url($this->image);
        }

        return null;
    }

    public function isLowStock(): bool
    {
        return $this->quantity <= $this->low_stock_threshold;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeLowStock($query)
    {
        return $query->whereColumn('quantity', '<=', 'low_stock_threshold');
    }

    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('code', 'like', "%{$search}%")
                ->orWhere('barcode', 'like', "%{$search}%")
                ->orWhere('category', 'like', "%{$search}%");
        });
    }
}
