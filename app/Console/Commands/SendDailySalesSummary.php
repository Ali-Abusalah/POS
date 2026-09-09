<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\DailySalesSummary;
use Illuminate\Console\Command;

class SendDailySalesSummary extends Command
{
    protected $signature = 'sales:daily-summary';

    protected $description = 'Send daily sales summary to admin users';

    public function handle(): int
    {
        $adminUsers = User::where('role', 'admin')->get();

        if ($adminUsers->isEmpty()) {
            $this->warn('No admin users found. Skipping daily summary.');
            return self::SUCCESS;
        }

        foreach ($adminUsers as $admin) {
            $admin->notify(new DailySalesSummary());
            $this->info("Daily summary sent to: {$admin->email}");
        }

        return self::SUCCESS;
    }
}
