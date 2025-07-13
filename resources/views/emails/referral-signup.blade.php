@extends('emails.layout')

@section('title', 'New Referral Signup - Volume Up Agency')

@section('header', '🎉 Great News!')

@section('content')
<div class="greeting">
    Hi {{ $partner->name }},
</div>

<div class="highlight-box">
    <span class="emoji">🎉</span>
    <h2 style="margin: 0; font-size: 20px;">A contractor just signed up through your referral link!</h2>
</div>

<p><strong>{{ $lead->name }}</strong> from <strong>{{ $lead->company }}</strong> has submitted their information and is now in our system.</p>

<p>Here are the details:</p>
<ul>
    <li><strong>Name:</strong> {{ $lead->name }}</li>
    <li><strong>Company:</strong> {{ $lead->company }}</li>
    <li><strong>Signed up:</strong> {{ $lead->created_at->format('M j, Y \a\t g:i A') }}</li>
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
</div>

<p>Our team will now reach out to {{ $lead->name }} to schedule a consultation. We'll keep you updated on their progress through the pipeline!</p>

<div style="text-align: center; margin: 30px 0;">
    <h3 style="color: #00B9F1; margin-bottom: 15px;">Ready to Earn More?</h3>
    <p>Share your referral link with other contractors and keep building your passive income!</p>
    
    <div class="referral-link">
        {{ $referralUrl }}
    </div>
    
    <a href="{{ $referralUrl }}" class="cta-button">
        Share Your Referral Link
    </a>
</div>

<p style="margin-top: 30px;">
    <strong>What's Next?</strong><br>
    1. We'll contact {{ $lead->name }} within 24 hours<br>
    2. Schedule a consultation call<br>
    3. Present our lead generation services<br>
    4. You earn commissions when they become a client!
</p>

<p>Thanks for being an amazing partner!</p>

<p>Best regards,<br>
<strong>The Volume Up Team</strong></p>
@endsection
