<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the partner dashboard.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        
        // Get user's leads and commissions
        $leads = $user->leads()->latest()->get();
        $totalLeads = $leads->count();
        
        // Calculate commission totals
        $pendingCommissions = $user->getTotalCommissions('pending');
        $approvedCommissions = $user->getTotalCommissions('approved');
        $paidCommissions = $user->getTotalCommissions('paid');
        $totalCommissions = $user->getTotalCommissions();
        
        // Get fast-close bonuses (bonus type commissions)
        $fastCloseBonuses = $user->commissions()
            ->where('type', 'bonus')
            ->sum('amount');
        
        return view('dashboard', compact(
            'user',
            'leads',
            'totalLeads',
            'pendingCommissions',
            'approvedCommissions',
            'paidCommissions',
            'totalCommissions',
            'fastCloseBonuses'
        ));
    }

    /**
     * Display partner's leads.
     */
    public function leads(Request $request): View
    {
        $user = $request->user();
        $leads = $user->leads()->with('commissions')->latest()->paginate(15);
        
        return view('partner.leads', compact('leads'));
    }

    /**
     * Display partner's commissions.
     */
    public function commissions(Request $request): View
    {
        $user = $request->user();
        $commissions = $user->commissions()->with('lead')->latest()->paginate(15);
        
        return view('partner.commissions', compact('commissions'));
    }

    /**
     * Display partner's referral tools.
     */
    public function tools(Request $request): View
    {
        $user = $request->user();
        $referralCode = $user->referral_code;
        $referralUrl = url('/referral?ref=' . $referralCode);
        
        return view('partner.tools', compact('referralCode', 'referralUrl'));
    }

    /**
     * Display partner's payout settings.
     */
    public function payoutSettings(Request $request): View
    {
        $user = $request->user();
        
        return view('partner.payout-settings', compact('user'));
    }

    /**
     * Update partner's payout settings.
     */
    public function updatePayoutSettings(Request $request)
    {
        $request->validate([
            'payout_method' => 'required|in:paypal,bank_transfer,check',
            'payout_details' => 'required|string|max:500',
        ]);

        $user = $request->user();
        $user->update([
            'payout_method' => $request->payout_method,
            'payout_details' => $request->payout_details,
        ]);

        return redirect()->route('partner.payout-settings')
            ->with('success', 'Payout settings updated successfully.');
    }
}
