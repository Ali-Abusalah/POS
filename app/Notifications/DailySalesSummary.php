<?php

namespace App\Notifications;

use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DailySalesSummary extends Notification implements ShouldQueue
{
    use Queueable;

    public array $summary;

    public function __construct()
    {
        $today = Carbon::today();

        $this->summary = [
            'date' => $today->format('M d, Y'),
            'total_invoices' => Invoice::where('status', 'paid')->whereDate('created_at', $today)->count(),
            'total_revenue' => Invoice::where('status', 'paid')->whereDate('created_at', $today)->sum('grand_total'),
            'total_voided' => Invoice::where('status', 'void')->whereDate('created_at', $today)->count(),
            'top_item' => Invoice::where('status', 'paid')
                ->whereDate('created_at', $today)
                ->with('items')
                ->get()
                ->pluck('items')
                ->flatten()
                ->groupBy('item_name')
                ->map(fn ($items) => $items->sum('quantity'))
                ->sortDesc()
                ->keys()
                ->first() ?? 'N/A',
        ];
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Daily Sales Summary - {$this->summary['date']}")
            ->greeting("Sales Report for {$this->summary['date']}")
            ->line("**Total Invoices:** {$this->summary['total_invoices']}")
            ->line("**Total Revenue:** JD " . number_format($this->summary['total_revenue'], 2))
            ->line("**Voided Invoices:** {$this->summary['total_voided']}")
            ->line("**Top Selling Item:** {$this->summary['top_item']}")
            ->line('---')
            ->action('View Dashboard', url('/admin'))
            ->line('Thank you for using our POS system!');
    }
}
