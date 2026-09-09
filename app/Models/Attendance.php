<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    protected $fillable = [
        'user_id',
        'branch_id',
        'clock_in',
        'clock_out',
        'status', // active, completed
        'notes',
    ];

    protected $casts = [
        'clock_in' => 'datetime',
        'clock_out' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Check if the given user is currently clocked in.
     */
    public static function isClockedIn(int $userId): bool
    {
        return self::where('user_id', $userId)
            ->where('status', 'active')
            ->exists();
    }

    /**
     * Get the active attendance record for a user.
     */
    public static function getActive(int $userId): ?self
    {
        return self::where('user_id', $userId)
            ->where('status', 'active')
            ->first();
    }

    /**
     * Clock in a user.
     */
    public static function clockIn(int $userId, ?int $branchId = null): self
    {
        // If already clocked in, return existing
        $existing = self::getActive($userId);
        if ($existing) {
            return $existing;
        }

        return self::create([
            'user_id' => $userId,
            'branch_id' => $branchId ?? auth()->user()->branch_id,
            'clock_in' => now(),
            'status' => 'active',
        ]);
    }

    /**
     * Clock out a user.
     */
    public static function clockOut(int $userId): bool
    {
        $active = self::getActive($userId);
        if (!$active) {
            return false;
        }

        $active->update([
            'clock_out' => now(),
            'status' => 'completed',
        ]);

        return true;
    }
}
