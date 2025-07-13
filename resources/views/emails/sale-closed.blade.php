@extends('emails.layout')

@section('title', 'Sale Closed - Congratulations!')

@section('header', '🎉 Congratulations!')

@section('content')
<div class="greeting">
    Hi {{ $partner->name }},
</div>

<div class="highlight-box">
    <span class="emoji">🎉</span>
    <h2 style="margin: 0; font-size: 20px;">Your referral became a client!</h2>
    <p style="margin: 10px 0 0 0; font-size: 16px;">You're now earning monthly recurring revenue!</p>
</div>

<p><strong>{{ $lead->name }}</strong> from <strong>{{ $lead->company }}</strong> has officially signed up for our lead generation services!</p>

<div style="background: #d4edda; border-left: 4px solid #28a745; padding: 20px; margin: 20px 0; border-radius: 4px;">
    <h3 style="margin: 0 0 15px 0; color: #155724;">💰 Your Commission Details</h3>
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
        <div>
            <strong style="color: #155724;">Monthly Commission:</strong><br>
            <span style="font-size: 24px; color: #28a745; font-weight: bold;">${{ number_format($pendingCommission, 2) }}</span>
        </div>
        <div>
            <strong style="color: #155724;">Commission Rate:</strong><br>
            <span style="font-size: 18px; color: #155724;">10% of monthly retainer</span>
        </div>
    </div>
</div>

<p><strong>What this means for you:</strong></p>
<ul>
    <li>🎯 You'll earn <strong>${{ number_format($pendingCommission, 2) }}</strong> every month {{ $lead->company }} stays with us</li>
    <li>💰 Payments are processed monthly via your preferred method</li>
    <li>📈 This is recurring revenue - it compounds over time</li>
    <li>🚀 Plus you earned any applicable fast-close bonuses!</li>
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
        <div class="stat-value">${{ number_format($pendingCommission, 0) }}</div>
        <div class="stat-label">New Monthly Income</div>
    </div>
</div>

<div style="background: #e7f3ff; border-left: 4px solid #00B9F1; padding: 15px; margin: 20px 0; border-radius: 4px;">
    <h4 style="margin: 0 0 10px 0; color: #004085;">🔄 Recurring Revenue Power</h4>
    <p style="margin: 0; color: #004085;">If {{ $lead->company }} stays with us for just 12 months, you'll earn <strong>${{ number_format($pendingCommission * 12, 0) }}</strong> from this single referral!</p>
</div>

<p><strong>Next Steps:</strong></p>
<ol>
    <li>Your commission will be processed in the next monthly payout cycle</li>
    <li>You'll receive monthly payments as long as {{ $lead->company }} remains a client</li>
    <li>Track your earnings in your partner dashboard</li>
    <li>Keep referring more contractors to build your income!</li>
</ol>

<div style="text-align: center; margin: 30px 0;">
    <h3 style="color: #00B9F1; margin-bottom: 15px;">Ready to Multiply Your Success?</h3>
    <p>You've proven the system works. Now let's scale it up!</p>
    
    <div class="referral-link">
        {{ $referralUrl }}
    </div>
    
    <a href="{{ $referralUrl }}" class="cta-button">
        Refer More Contractors
    </a>
</div>

<p>Thank you for being an incredible partner. This is just the beginning of your passive income journey with Volume Up!</p>

<p>Celebrate this win - you've earned it! 🥳</p>

<p>Best regards,<br>
<strong>The Volume Up Team</strong></p>
@endsection
