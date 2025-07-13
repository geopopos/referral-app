@extends('emails.layout')

@section('title', 'Appointment Scheduled - Volume Up Agency')

@section('header', '📅 Progress Update!')

@section('content')
<div class="greeting">
    Hi {{ $partner->name }},
</div>

<div class="highlight-box">
    <span class="emoji">📅</span>
    <h2 style="margin: 0; font-size: 20px;">Your referral is moving forward - appointment scheduled!</h2>
</div>

<p>Great news! <strong>{{ $lead->name }}</strong> from <strong>{{ $lead->company }}</strong> has scheduled a consultation appointment with our team.</p>

<p>This is a significant step forward in the sales process. Here's what this means:</p>

<ul>
    <li>✅ {{ $lead->name }} is interested in our services</li>
    <li>✅ They've committed time to learn more</li>
    <li>✅ Our sales team will present our lead generation solutions</li>
    <li>✅ You're one step closer to earning commissions!</li>
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

<div style="background: #e8f5e8; border-left: 4px solid #28a745; padding: 15px; margin: 20px 0; border-radius: 4px;">
    <h4 style="margin: 0 0 10px 0; color: #155724;">💡 Did You Know?</h4>
    <p style="margin: 0; color: #155724;">Contractors who schedule appointments have a 65% higher chance of becoming clients. Your referral is looking very promising!</p>
</div>

<p><strong>What happens next?</strong></p>
<ol>
    <li>Our sales team will conduct the consultation</li>
    <li>We'll present our lead generation services and pricing</li>
    <li>If they decide to move forward, we'll send a proposal</li>
    <li>Once they become a client, you start earning monthly commissions!</li>
</ol>

<div style="text-align: center; margin: 30px 0;">
    <h3 style="color: #00B9F1; margin-bottom: 15px;">Keep the Momentum Going!</h3>
    <p>While we work on closing {{ $lead->name }}, why not refer more contractors?</p>
    
    <div class="referral-link">
        {{ $referralUrl }}
    </div>
    
    <a href="{{ $referralUrl }}" class="cta-button">
        Share Your Referral Link
    </a>
</div>

<p>We'll keep you updated on {{ $lead->name }}'s progress. Thanks for being such a valuable partner!</p>

<p>Best regards,<br>
<strong>The Volume Up Team</strong></p>
@endsection
