<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CreditNote extends Model
{
    protected $fillable = [
        'credit_note_number',
        'invoice_id',
        'customer_name',
        'customer_contact',
        'amount',
        'reason',
        'status',
        'created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'issued' => 'success',
            'applied' => 'info',
            'voided' => 'danger',
            default => 'gray',
        };
    }
}
