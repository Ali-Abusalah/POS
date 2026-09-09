<?php

namespace App\Livewire;

use App\Models\ActivityLog;
use App\Models\Attendance;
use Livewire\Component;

class CustomTopbar extends Component
{
    public int $notificationCount = 0;
    public int $messageCount = 0;
    public bool $isClockedIn = false;
    public ?string $clockInTime = null;
    public array $recentNotifications = [];

    protected $listeners = [
        'attendance-updated' => 'refreshAttendance',
    ];

    public function mount(): void
    {
        $this->refreshData();
    }

    public function render()
    {
        return view('livewire.custom-topbar');
    }

    public function refreshData(): void
    {
        if (!auth()->check()) {
            return;
        }

        $this->refreshAttendance();
        $this->refreshNotifications();
    }

    public function refreshAttendance(): void
    {
        if (!auth()->check()) {
            return;
        }

        $userId = auth()->id();
        $this->isClockedIn = Attendance::isClockedIn($userId);

        $active = Attendance::getActive($userId);
        $this->clockInTime = $active?->clock_in?->format('h:i A') ?? null;
    }

    public function refreshNotifications(): void
    {
        if (!auth()->check()) {
            return;
        }

        $this->notificationCount = ActivityLog::where('created_at', '>=', now()->subDay())->count();

        $this->recentNotifications = ActivityLog::latest()
            ->limit(10)
            ->get()
            ->map(fn ($log) => [
                'id' => $log->id,
                'message' => $log->description ?? ucfirst($log->action) . ' ' . class_basename($log->model_type),
                'time' => $log->created_at->diffForHumans(),
                'type' => $log->action,
            ])
            ->toArray();
    }

    public function toggleClock(): void
    {
        if (!auth()->check()) {
            return;
        }

        $userId = auth()->id();

        if ($this->isClockedIn) {
            Attendance::clockOut($userId);
        } else {
            Attendance::clockIn($userId);
        }

        $this->refreshAttendance();
    }
}
