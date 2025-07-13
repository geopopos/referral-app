@extends('emails.layout')

@section('title', 'Commission Approved - Volume Up Agency')

@section('header', '✅ Commission Approved!')

@section('content')
<div class="greeting">
    Hi {{ $partner->name }},
</div>

<div class="highlight-box">
    <span class="emoji">✅</span>
    <h2 style="margin: 0; font-size: 20px;">Your commission has been approved!</h2>
    <p style="margin: 10px 0 0 0; font-size: 16px;">Payment will be processed in the next payout cycle.</p>
</div>

<p>Great news! Your commission for <strong>${{ number_format($commission->amount, 2) }}</strong> has been reviewed and approved by our team.</p>

<div style="background: #d4edda; border-left: 4px solid #28a745; padding: 20px; margin: 20px 0; border-radius: 4px;">
    <h3 style="margin: 0 0 15px 0; color: #155724;">💰 Commission Details</h3>
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
        <div>
            <strong style="color: #155724;">Amount:</strong><br>
            <span style="font-size: 24px; color: #28a745; font-weight: bold;">${{ number_format($commission->amount, 2) }}</span>
        </div>
        <div>
            <strong style="color: #155724;">Type:</strong><br>
            <span style="font-size: 18px; color: #155724;">{{ ucfirst($commission->type) }} Commission</span>
        </div>
    </div>
    @if($commission->lead)
    <div style="margin-top: 15px;">
        <strong style="color: #155724;">Related to:</strong> {{ $commission->lead->name }} ({{ $commission->lead->company }})
    </div>
    @endif
</div>

<p><strong>What happens next?</strong></p>
<ul>
    <li>💳 Payment will be processed in our next monthly payout cycle</li>
    <li>📧 You'll receive a payment confirmation email when it's sent</li>
    <li>🏦 Funds will be sent to your preferred payout method</li>
    <li>📊 This commission will appear in your earnings history</li>
</ul>

<div class="stats-grid">
    <div class="stat-item">
        <div class="stat-value">{{ $totalLeads }}</div>
        <div class="stat-label">Total Referrals</div>
    </div>
    <div class="stat-item">
        <div class="stat-value">${{ number_format($totalEarnings, 0) }}</div>
        <div class="stat-label">Total Earned</div>
    </div>
    <div class="stat-item">
        <div class="stat-value">${{ number_format($approvedCommissions, 0) }}</div>
        <div class="stat-label">Approved This Month</div>
    </div>
</div>

<div style="background: #e7f3ff; border-left: 4px solid #00B9F1; padding: 15px; margin: 20px 0; border-radius: 4px;">
    <h4 style="margin: 0 0 10px 0; color: #004085;">💡 Pro Tip</h4>
    <p style="margin: 0; color: #004085;">Commissions are typically processed between the 1st and 5th of each month. Make sure your payout details are up to date in your partner dashboard!</p>
</div>

<div style="text-align: center; margin: 30px 0;">
    <h3 style="color: #00B9F1; margin-bottom: 15px;">Keep Building Your Income!</h3>
    <p>Every referral adds to your monthly recurring revenue. Keep sharing!</p>
    
    <div class="referral-link">
        {{ $referralUrl }}
    </div>
    
    <a href="{{ $referralUrl }}" class="cta-button">
        Share Your Referral Link
    </a>
</div>

<p>Thank you for being such a valuable partner. Your commission is well-deserved!</p>

<p>Best regards,<br>
<strong>The Volume Up Team</strong></p>
@endsection
