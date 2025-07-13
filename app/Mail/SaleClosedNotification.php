<?php

namespace App\Mail;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SaleClosedNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $partner,
        public Lead $lead
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🎉 Congratulations! Your Referral Became a Client',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.sale-closed',
            with: [
                'partner' => $this->partner,
                'lead' => $this->lead,
                'referralUrl' => $this->partner->referral_url,
                'totalLeads' => $this->partner->leads()->count(),
                'totalEarnings' => $this->partner->commissions()->where('status', '!=', 'pending')->sum('amount'),
                'pendingCommission' => $this->lead->commissions()->where('user_id', $this->partner->id)->sum('amount'),
            ]
        );
    }
}
