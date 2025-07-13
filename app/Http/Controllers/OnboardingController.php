<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class OnboardingController extends Controller
{
    /**
     * Show the onboarding step.
     */
    public function show(Request $request, $step = 1)
    {
        $user = Auth::user();
        
        // Ensure user is a partner
        if (!$user->isPartner()) {
            return redirect()->route('dashboard');
        }

        // If already completed, redirect to dashboard
        if ($user->hasCompletedOnboarding()) {
            return redirect()->route('dashboard');
        }

        // Validate step
        $step = max(1, min(4, (int) $step));
        
        // Ensure user can access this step (can't skip ahead)
        if ($step > $user->onboarding_step) {
            return redirect()->route('onboarding.step', ['step' => $user->onboarding_step]);
        }

        return view('onboarding.step', [
            'user' => $user,
            'step' => $step,
            'progress' => ($step / 4) * 100,
        ]);
    }

    /**
     * Process the onboarding step.
     */
    public function store(Request $request, $step = 1)
    {
        $user = Auth::user();
        $step = max(1, min(4, (int) $step));

        // Validate based on step
        $validationRules = $this->getValidationRules($step);
        $request->validate($validationRules);

        // Update user data
        $updateData = $this->getUpdateData($request, $step);
        $user->update($updateData);

        // Move to next step or complete
        if ($step < 4) {
            $nextStep = $step + 1;
            $user->update(['onboarding_step' => $nextStep]);
            
            return redirect()->route('onboarding.step', ['step' => $nextStep])
                ->with('success', 'Step ' . $step . ' completed successfully!');
        } else {
            // Complete onboarding
            $user->completeOnboarding();
            
            return redirect()->route('dashboard')
                ->with('success', 'Welcome! Your profile is now complete and your referral link is ready to use.');
        }
    }

    /**
     * Get validation rules for each step.
     */
    private function getValidationRules($step)
    {
        switch ($step) {
            case 1:
                return [
                    'website_url' => 'nullable|url|max:255',
                    'business_type' => 'required|string|max:255',
                    'years_in_business' => 'required|integer|min:0|max:100',
                    'primary_services' => 'required|string|max:1000',
                ];
            case 2:
                return [
                    'current_client_count' => 'required|integer|min:0|max:10000',
                    'average_project_value' => 'required|numeric|min:0|max:1000000',
                    'biggest_challenge' => 'required|string|max:1000',
                ];
            case 3:
                return [
                    'phone' => 'required|string|max:20',
                    'paypal_email' => 'required|email|max:255',
                    'payment_method' => ['required', Rule::in(['paypal', 'ach'])],
                    'preferred_communication' => ['required', Rule::in(['email', 'phone', 'slack', 'teams'])],
                    'timezone' => 'required|string|max:50',
                ];
            case 4:
                return [
                    'wants_advertising_help' => 'boolean',
                    'referral_source' => 'nullable|string|max:255',
                ];
            default:
                return [];
        }
    }

    /**
     * Get update data for each step.
     */
    private function getUpdateData(Request $request, $step)
    {
        switch ($step) {
            case 1:
                return $request->only([
                    'website_url',
                    'business_type',
                    'years_in_business',
                    'primary_services',
                ]);
            case 2:
                return $request->only([
                    'current_client_count',
                    'average_project_value',
                    'biggest_challenge',
                ]);
            case 3:
                return $request->only([
                    'phone',
                    'paypal_email',
                    'payment_method',
                    'preferred_communication',
                    'timezone',
                ]);
            case 4:
                return [
                    'wants_advertising_help' => $request->boolean('wants_advertising_help'),
                    'referral_source' => $request->input('referral_source'),
                ];
            default:
                return [];
        }
    }

    /**
     * Skip onboarding (for testing purposes - remove in production).
     */
    public function skip()
    {
        $user = Auth::user();
        
        if ($user->isPartner()) {
            $user->completeOnboarding();
            return redirect()->route('dashboard')
                ->with('success', 'Onboarding skipped. You can complete your profile later.');
        }
        
        return redirect()->route('dashboard');
    }
}
