<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'subscription_number',
        'customer_name',
        'customer_contact',
        'plan_name',
        'amount',
        'billing_cycle',
        'status',
        'start_date',
        'next_billing_date',
        'end_date',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'start_date' => 'date',
        'next_billing_date' => 'date',
        'end_date' => 'date',
    ];

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'active' => 'success',
            'paused' => 'warning',
            'cancelled' => 'danger',
            'expired' => 'gray',
            default => 'gray',
        };
    }
}
