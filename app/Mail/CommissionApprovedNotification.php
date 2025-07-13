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

class CommissionApprovedNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $partner,
        public Commission $commission
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '✅ Your Commission Has Been Approved!',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.commission-approved',
            with: [
                'partner' => $this->partner,
                'commission' => $this->commission,
                'referralUrl' => $this->partner->referral_url,
                'totalLeads' => $this->partner->leads()->count(),
                'totalEarnings' => $this->partner->commissions()->where('status', '!=', 'pending')->sum('amount'),
                'approvedCommissions' => $this->partner->commissions()->where('status', 'approved')->sum('amount'),
            ]
        );
    }
}
