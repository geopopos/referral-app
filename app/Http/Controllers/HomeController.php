<?php

namespace App\Http\Controllers;

use App\Models\LandingPageContent;
use App\Models\CommissionSetting;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the landing page.
     */
    public function index()
    {
        // Get all landing page content
        $content = collect();
        
        // Get active commission settings
        $commissionSettings = CommissionSetting::getActive();
        
        // Get content with fallbacks
        $contentKeys = [
            'hero_title' => 'Earn Monthly Revenue Referring Roofing & HVAC Contractors',
            'hero_subtitle' => 'Earn 10% monthly recurring revenue — every month your referral stays with us.',
            'commission_percentage' => $commissionSettings ? $commissionSettings->commission_percentage . '%' : '10%',
            'quick_close_bonus' => $commissionSettings ? '$' . number_format($commissionSettings->quick_close_bonus, 0) : '$250',
            'testimonial_video_1' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
            'testimonial_name_1' => 'Sarah Johnson',
            'testimonial_title_1' => 'Marketing Agency Owner',
            'testimonial_video_2' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
            'testimonial_name_2' => 'Mike Rodriguez',
            'testimonial_title_2' => 'Industry Consultant',
            'testimonial_video_3' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
            'testimonial_name_3' => 'Jennifer Chen',
            'testimonial_title_3' => 'Sales Professional',
            'example_contractor_fee' => '$3,000',
            'example_monthly_earning' => '$300',
            'example_annual_potential' => '$18,000+',
        ];

        foreach ($contentKeys as $key => $default) {
            $content[$key] = LandingPageContent::get($key, $default);
        }

        // Pass commission settings for the calculator
        $calculatorData = [
            'commission_percentage' => $commissionSettings ? $commissionSettings->commission_percentage : 10,
            'quick_close_bonus' => $commissionSettings ? $commissionSettings->quick_close_bonus : 250,
            'quick_close_days' => $commissionSettings ? $commissionSettings->quick_close_days : 7,
        ];

        return view('welcome', compact('content', 'calculatorData'));
    }
}
