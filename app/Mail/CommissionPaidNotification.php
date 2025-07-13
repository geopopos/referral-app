<?php

namespace App\Mail;

use App\Models\Commission;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CommissionPaidNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $partner,
        public Commission $commission
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '💰 Payment is on the Way - Commission Paid!',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.commission-paid',
            with: [
                'partner' => $this->partner,
                'commission' => $this->commission,
                'referralUrl' => $this->partner->referral_url,
                'totalLeads' => $this->partner->leads()->count(),
                'totalEarnings' => $this->partner->commissions()->where('status', 'paid')->sum('amount'),
                'pendingCommissions' => $this->partner->commissions()->where('status', 'approved')->sum('amount'),
            ]
        );
    }
}
