@extends('emails.layout')

@section('title', 'Commission Paid - Volume Up Agency')

@section('header', '💰 Payment Sent!')

@section('content')
<div class="greeting">
    Hi {{ $partner->name }},
</div>

<div class="highlight-box">
    <span class="emoji">💰</span>
    <h2 style="margin: 0; font-size: 20px;">Your commission payment is on the way!</h2>
    <p style="margin: 10px 0 0 0; font-size: 16px;">Payment has been processed and sent to your account.</p>
</div>

<p>Excellent news! Your commission payment of <strong>${{ number_format($commission->amount, 2) }}</strong> has been processed and is on its way to your account.</p>

<div style="background: #d4edda; border-left: 4px solid #28a745; padding: 20px; margin: 20px 0; border-radius: 4px;">
    <h3 style="margin: 0 0 15px 0; color: #155724;">💰 Payment Details</h3>
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
        <div>
            <strong style="color: #155724;">Amount Paid:</strong><br>
            <span style="font-size: 24px; color: #28a745; font-weight: bold;">${{ number_format($commission->amount, 2) }}</span>
        </div>
        <div>
            <strong style="color: #155724;">Payment Date:</strong><br>
            <span style="font-size: 18px; color: #155724;">{{ $commission->updated_at->format('M j, Y') }}</span>
        </div>
    </div>
    @if($commission->lead)
    <div style="margin-top: 15px;">
        <strong style="color: #155724;">Commission for:</strong> {{ $commission->lead->name }} ({{ $commission->lead->company }})
    </div>
    @endif
</div>

<p><strong>Payment Information:</strong></p>
<ul>
    <li>💳 Payment method: {{ ucfirst($partner->payout_method ?? 'Bank Transfer') }}</li>
    <li>⏰ Processing time: 1-3 business days for most payment methods</li>
    <li>📧 You'll receive a separate payment confirmation from our payment processor</li>
    <li>📊 This payment is now reflected in your earnings history</li>
</ul>

<div class="stats-grid">
    <div class="stat-item">
        <div class="stat-value">{{ $totalLeads }}</div>
        <div class="stat-label">Total Referrals</div>
    </div>
    <div class="stat-item">
        <div class="stat-value">${{ number_format($totalEarnings, 0) }}</div>
        <div class="stat-label">Total Paid Out</div>
    </div>
    <div class="stat-item">
        <div class="stat-value">${{ number_format($pendingCommissions, 0) }}</div>
        <div class="stat-label">Pending Commissions</div>
    </div>
</div>

<div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0; border-radius: 4px;">
    <h4 style="margin: 0 0 10px 0; color: #856404;">🎯 Keep the Momentum Going!</h4>
    <p style="margin: 0; color: #856404;">You've proven that referrals work. Each new contractor you refer could become another monthly recurring payment like this one!</p>
</div>

<p><strong>Questions about your payment?</strong></p>
<ul>
    <li>Check your bank account or payment method in 1-3 business days</li>
    <li>Contact our support team if you don't see the payment within 5 business days</li>
    <li>Update your payout preferences anytime in your partner dashboard</li>
</ul>

<div style="text-align: center; margin: 30px 0;">
    <h3 style="color: #00B9F1; margin-bottom: 15px;">Ready for Your Next Payment?</h3>
    <p>The more contractors you refer, the more monthly payments you'll receive!</p>
    
    <div class="referral-link">
        {{ $referralUrl }}
    </div>
    
    <a href="{{ $referralUrl }}" class="cta-button">
        Refer More Contractors
    </a>
</div>

<p>Congratulations on another successful commission payment! This is what passive income looks like. 🎉</p>

<p>Keep up the great work!</p>

<p>Best regards,<br>
<strong>The Volume Up Team</strong></p>
@endsection
