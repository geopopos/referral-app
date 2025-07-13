@extends('emails.layout')

@section('title', 'Proposal Sent - Volume Up Agency')

@section('header', '💼 Proposal Sent!')

@section('content')
<div class="greeting">
    Hi {{ $partner->name }},
</div>

<div class="highlight-box">
    <span class="emoji">💼</span>
    <h2 style="margin: 0; font-size: 20px;">We've sent a proposal to your referral!</h2>
</div>

<p>Excellent progress! After a successful consultation, we've sent <strong>{{ $lead->name }}</strong> from <strong>{{ $lead->company }}</strong> a detailed proposal for our lead generation services.</p>

<p>This is a major milestone in the sales process:</p>

<ul>
    <li>✅ Consultation completed successfully</li>
    <li>✅ {{ $lead->name }} showed strong interest</li>
    <li>✅ Custom proposal sent with pricing</li>
    <li>✅ You're very close to earning commissions!</li>
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

<div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0; border-radius: 4px;">
    <h4 style="margin: 0 0 10px 0; color: #856404;">🎯 Success Rate Alert!</h4>
    <p style="margin: 0; color: #856404;">Contractors who receive proposals have an 80% chance of becoming clients within 30 days. This is looking very promising!</p>
</div>

<p><strong>What's in the proposal?</strong></p>
<ul>
    <li>Customized lead generation strategy for {{ $lead->company }}</li>
    <li>Monthly lead targets and pricing</li>
    <li>Case studies from similar contractors</li>
    <li>Clear next steps to get started</li>
</ul>

<p><strong>What happens next?</strong></p>
<ol>
    <li>{{ $lead->name }} reviews the proposal (typically 3-7 days)</li>
    <li>Our team follows up to answer any questions</li>
    <li>Contract signing and onboarding begins</li>
    <li>You start earning monthly recurring commissions!</li>
</ol>

<div style="text-align: center; margin: 30px 0;">
    <h3 style="color: #00B9F1; margin-bottom: 15px;">Almost There!</h3>
    <p>While {{ $lead->name }} reviews the proposal, keep building your referral pipeline!</p>
    
    <div class="referral-link">
        {{ $referralUrl }}
    </div>
    
    <a href="{{ $referralUrl }}" class="cta-button">
        Refer More Contractors
    </a>
</div>

<p>We'll notify you immediately when {{ $lead->name }} makes a decision. Keep your fingers crossed!</p>

<p>Best regards,<br>
<strong>The Volume Up Team</strong></p>
@endsection
