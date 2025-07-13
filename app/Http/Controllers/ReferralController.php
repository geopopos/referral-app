<?php

namespace App\Http\Controllers;

use App\Mail\ReferralSignupNotification;
use App\Models\Lead;
use App\Models\LandingPageContent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ReferralController extends Controller
{
    /**
     * Display the referral lead capture form.
     */
    public function index(Request $request): View
    {
        $referralCode = $request->query('ref');
        $referrer = null;
        
        if ($referralCode) {
            $referrer = User::where('referral_code', $referralCode)->first();
        }
        
        // Load all referral page content
        $content = LandingPageContent::where('key', 'LIKE', 'referral_%')
            ->orWhere('key', 'LIKE', 'process_%')
            ->orWhere('key', 'LIKE', 'video_testimonial_%')
            ->orWhere('key', 'LIKE', 'testimonials_%')
            ->orWhere('key', 'LIKE', 'reviews_%')
            ->orWhere('key', 'LIKE', 'review_%')
            ->orWhere('key', 'LIKE', 'overall_%')
            ->orWhere('key', 'LIKE', 'total_%')
            ->orWhere('key', 'LIKE', 'rating_%')
            ->orWhere('key', 'LIKE', 'proof_%')
            ->orWhere('key', 'LIKE', 'qualification_%')
            ->orWhere('key', 'LIKE', 'footer_%')
            ->get()
            ->keyBy('key');
        
        return view('referral.form', compact('referralCode', 'referrer', 'content'));
    }

    /**
     * Store a new lead from the referral form.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'company' => 'required|string|max:255',
            'how_heard_about_us' => 'nullable|string|max:1000',
            'referral_code' => 'nullable|string|exists:users,referral_code',
        ]);

        // Create the lead
        $lead = Lead::create($validated);

        // If there's a valid referral code, create a commission and send notification
        if ($validated['referral_code']) {
            $referrer = User::where('referral_code', $validated['referral_code'])->first();
            
            if ($referrer) {
                // Create a pending commission (you can adjust the amount as needed)
                $referrer->commissions()->create([
                    'lead_id' => $lead->id,
                    'type' => 'rev_share',
                    'amount' => 500.00, // Default commission amount
                    'status' => 'pending',
                ]);

                // Send email notification to the referrer
                Mail::to($referrer->email)->send(new ReferralSignupNotification($referrer, $lead));
            }
        }

        return redirect()->route('referral.thank-you')
            ->with('success', 'Thank you for your interest! We will be in touch soon.');
    }

    /**
     * Display the thank you page.
     */
    public function thankYou(): View
    {
        return view('referral.thank-you');
    }
}
