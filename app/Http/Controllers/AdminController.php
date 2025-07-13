<?php

namespace App\Http\Controllers;

use App\Mail\AppointmentBookedNotification;
use App\Mail\CommissionApprovedNotification;
use App\Mail\CommissionPaidNotification;
use App\Mail\OfferMadeNotification;
use App\Mail\SaleClosedNotification;
use App\Models\Commission;
use App\Models\CommissionSetting;
use App\Models\LandingPageContent;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index(): View
    {
        $partners = User::where('role', 'partner')->withCount('leads')->get();
        $totalLeads = Lead::count();
        $totalCommissions = Commission::sum('amount');
        $pendingCommissions = Commission::where('status', 'pending')->sum('amount');
        
        return view('admin.dashboard', compact(
            'partners',
            'totalLeads',
            'totalCommissions',
            'pendingCommissions'
        ));
    }

    /**
     * Display all leads.
     */
    public function leads(): View
    {
        $leads = Lead::with('referrer')->latest()->paginate(20);
        
        return view('admin.leads', compact('leads'));
    }

    /**
     * Update lead status and pipeline data.
     */
    public function updateLeadStatus(Request $request, Lead $lead): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:lead,appointment_scheduled,appointment_completed,offer_made,sale,lost',
            'appointment_scheduled_at' => 'nullable|date',
            'appointment_completed_at' => 'nullable|date',
            'appointment_notes' => 'nullable|string|max:1000',
            'offer_amount' => 'nullable|numeric|min:0',
            'offer_made_at' => 'nullable|date',
            'offer_notes' => 'nullable|string|max:1000',
            'sale_amount' => 'nullable|numeric|min:0',
            'sale_closed_at' => 'nullable|date',
            'sale_notes' => 'nullable|string|max:1000',
            'lost_reason' => 'nullable|string|max:500',
            'commission_override_percentage' => 'nullable|numeric|min:0|max:100',
            'commission_override_amount' => 'nullable|numeric|min:0',
            'commission_override_reason' => 'nullable|string|max:500',
        ]);

        $oldStatus = $lead->status;
        $newStatus = $validated['status'];

        // Use the Lead model's moveToStatus method for proper pipeline management
        $success = $lead->moveToStatus($newStatus, $validated);

        if (!$success) {
            return back()->with('error', 'Invalid status transition.');
        }

        // Send email notifications to referrer if status changed and there's a referrer
        if ($oldStatus !== $newStatus && $lead->referrer) {
            $this->sendStatusChangeNotification($lead, $newStatus);
        }

        return back()->with('success', 'Lead updated successfully.');
    }

    /**
     * Send email notification when lead status changes.
     */
    private function sendStatusChangeNotification(Lead $lead, string $newStatus): void
    {
        $referrer = $lead->referrer;
        
        switch ($newStatus) {
            case 'appointment_scheduled':
                Mail::to($referrer->email)->send(new AppointmentBookedNotification($referrer, $lead));
                break;
                
            case 'offer_made':
                Mail::to($referrer->email)->send(new OfferMadeNotification($referrer, $lead));
                break;
                
            case 'sale':
                Mail::to($referrer->email)->send(new SaleClosedNotification($referrer, $lead));
                break;
        }
    }

    /**
     * Show lead details for editing.
     */
    public function showLead(Lead $lead): View
    {
        $lead->load('referrer', 'commissions');
        $commissionSetting = CommissionSetting::getActive();
        
        return view('admin.lead-details', compact('lead', 'commissionSetting'));
    }

    /**
     * Display all commissions.
     */
    public function commissions(): View
    {
        $commissions = Commission::with(['user', 'lead'])->latest()->paginate(20);
        
        return view('admin.commissions', compact('commissions'));
    }

    /**
     * Update commission status.
     */
    public function updateCommissionStatus(Request $request, Commission $commission): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,paid',
        ]);

        $oldStatus = $commission->status;
        $newStatus = $validated['status'];

        if ($newStatus === 'paid') {
            $commission->markAsPaid();
        } elseif ($newStatus === 'approved') {
            $commission->markAsApproved();
        } else {
            $commission->update($validated);
        }

        // Send email notification if status changed
        if ($oldStatus !== $newStatus) {
            $this->sendCommissionStatusNotification($commission, $newStatus);
        }

        return back()->with('success', 'Commission status updated successfully.');
    }

    /**
     * Send email notification when commission status changes.
     */
    private function sendCommissionStatusNotification(Commission $commission, string $newStatus): void
    {
        $partner = $commission->user;
        
        switch ($newStatus) {
            case 'approved':
                Mail::to($partner->email)->send(new CommissionApprovedNotification($partner, $commission));
                break;
                
            case 'paid':
                Mail::to($partner->email)->send(new CommissionPaidNotification($partner, $commission));
                break;
        }
    }

    /**
     * Display partner details.
     */
    public function partnerDetails(User $partner): View
    {
        $partner->load(['leads', 'commissions.lead']);
        
        return view('admin.partner-details', compact('partner'));
    }

    /**
     * Export leads to CSV.
     */
    public function exportLeads()
    {
        $leads = Lead::with('referrer')->get();
        
        $filename = 'leads_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($leads) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'ID',
                'Name',
                'Email',
                'Phone',
                'Company',
                'Status',
                'Referrer',
                'Referral Code',
                'Created At'
            ]);
            
            // CSV data
            foreach ($leads as $lead) {
                fputcsv($file, [
                    $lead->id,
                    $lead->name,
                    $lead->email,
                    $lead->phone,
                    $lead->company,
                    $lead->status,
                    $lead->referrer ? $lead->referrer->name : 'Direct',
                    $lead->referral_code,
                    $lead->created_at->format('Y-m-d H:i:s')
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Display landing page content management.
     */
    public function landingPage(): View
    {
        $contents = LandingPageContent::getAllOrdered();
        
        return view('admin.landing-page', compact('contents'));
    }

    /**
     * Update landing page content.
     */
    public function updateLandingPage(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'content' => 'required|array',
            'content.*' => 'required|string|max:1000',
        ]);

        foreach ($validated['content'] as $key => $value) {
            $content = LandingPageContent::where('key', $key)->first();
            if ($content) {
                $content->update(['value' => $value]);
            }
        }

        return back()->with('success', 'Landing page content updated successfully.');
    }

    /**
     * Display referral page content management.
     */
    public function referralPage(): View
    {
        $contents = LandingPageContent::where('key', 'LIKE', 'referral_%')
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
            ->orderBy('sort_order')
            ->orderBy('label')
            ->get();
        
        return view('admin.referral-page', compact('contents'));
    }

    /**
     * Update referral page content.
     */
    public function updateReferralPage(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'content' => 'required|array',
            'content.*' => 'required|string|max:2000', // Increased for longer testimonials
        ]);

        foreach ($validated['content'] as $key => $value) {
            $content = LandingPageContent::where('key', $key)->first();
            if ($content) {
                $content->update(['value' => $value]);
            }
        }

        return back()->with('success', 'Referral page content updated successfully.');
    }

    /**
     * Display commission settings management.
     */
    public function commissionSettings(): View
    {
        $settings = CommissionSetting::with('creator')->latest()->paginate(10);
        $activeSetting = CommissionSetting::getActive();
        
        return view('admin.commission-settings', compact('settings', 'activeSetting'));
    }

    /**
     * Store new commission setting.
     */
    public function storeCommissionSetting(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'commission_percentage' => 'required|numeric|min:0|max:100',
            'quick_close_bonus' => 'required|numeric|min:0',
            'quick_close_days' => 'required|integer|min:1|max:365',
            'description' => 'nullable|string|max:500',
            'activate_immediately' => 'boolean',
        ]);

        $setting = CommissionSetting::create([
            'commission_percentage' => $validated['commission_percentage'],
            'quick_close_bonus' => $validated['quick_close_bonus'],
            'quick_close_days' => $validated['quick_close_days'],
            'description' => $validated['description'],
            'is_active' => false,
            'created_by' => Auth::id(),
        ]);

        if ($request->boolean('activate_immediately')) {
            $setting->activate();
        }

        return back()->with('success', 'Commission setting created successfully.');
    }

    /**
     * Activate a commission setting.
     */
    public function activateCommissionSetting(CommissionSetting $setting): RedirectResponse
    {
        $setting->activate();
        
        return back()->with('success', 'Commission setting activated successfully.');
    }

    /**
     * Get pipeline analytics data.
     */
    public function pipelineAnalytics(): View
    {
        // Basic pipeline stats
        $pipelineStats = [
            'lead' => Lead::where('status', 'lead')->count(),
            'appointment_scheduled' => Lead::where('status', 'appointment_scheduled')->count(),
            'appointment_completed' => Lead::where('status', 'appointment_completed')->count(),
            'offer_made' => Lead::where('status', 'offer_made')->count(),
            'sale' => Lead::where('status', 'sale')->count(),
            'lost' => Lead::where('status', 'lost')->count(),
        ];

        $totalLeads = array_sum($pipelineStats);
        $salesClosed = $pipelineStats['sale'];
        $appointmentsScheduled = $pipelineStats['appointment_scheduled'] + $pipelineStats['appointment_completed'];

        // Conversion rate calculation
        $conversionRate = $totalLeads > 0 ? ($salesClosed / $totalLeads) * 100 : 0;

        // Pipeline stages for funnel visualization
        $pipelineStages = [];
        $stageLabels = [
            'lead' => 'New Leads',
            'appointment_scheduled' => 'Appointments Scheduled',
            'appointment_completed' => 'Appointments Completed',
            'offer_made' => 'Offers Made',
            'sale' => 'Sales Closed',
            'lost' => 'Lost Leads'
        ];

        foreach ($pipelineStats as $stage => $count) {
            $percentage = $totalLeads > 0 ? ($count / $totalLeads) * 100 : 0;
            $pipelineStages[$stage] = [
                'label' => $stageLabels[$stage],
                'count' => $count,
                'percentage' => $percentage
            ];
        }

        // Revenue analytics
        $totalRevenue = Lead::where('status', 'sale')->sum('sale_amount') ?? 0;
        $avgDealSize = Lead::where('status', 'sale')->avg('sale_amount') ?? 0;
        $totalCommissions = Commission::where('status', 'paid')->sum('amount') ?? 0;

        // Quick close analytics
        $activeSetting = CommissionSetting::getActive();
        $quickCloseThreshold = $activeSetting ? $activeSetting->quick_close_days : 30;
        $quickCloseCount = 0;
        $quickCloseBonuses = 0;
        $avgTimeToClose = 0;

        if ($activeSetting) {
            $salesWithDates = Lead::where('status', 'sale')
                ->whereNotNull('sale_closed_at')
                ->get();

            $quickCloses = $salesWithDates->filter(function ($lead) use ($activeSetting) {
                return $activeSetting->isQuickClose(
                    $lead->created_at->toDateTime(),
                    $lead->sale_closed_at->toDateTime()
                );
            });

            $quickCloseCount = $quickCloses->count();
            $quickCloseBonuses = $quickCloses->sum(function ($lead) use ($activeSetting) {
                return $activeSetting->quick_close_bonus;
            });

            // Calculate average time to close
            if ($salesWithDates->count() > 0) {
                $totalDays = $salesWithDates->sum(function ($lead) {
                    return $lead->created_at->diffInDays($lead->sale_closed_at);
                });
                $avgTimeToClose = round($totalDays / $salesWithDates->count(), 1);
            }
        }

        $quickCloseRate = $salesClosed > 0 ? ($quickCloseCount / $salesClosed) * 100 : 0;

        // Top performing partners
        $topPartners = User::where('role', 'partner')
            ->withCount(['leads', 'leads as sales_count' => function ($query) {
                $query->where('status', 'sale');
            }])
            ->with(['commissions' => function ($query) {
                $query->where('status', 'paid');
            }])
            ->orderByDesc('sales_count')
            ->limit(5)
            ->get()
            ->filter(function ($partner) {
                return $partner->leads_count > 0;
            })
            ->map(function ($partner) {
                $totalCommissions = $partner->commissions->sum('amount');
                $conversionRate = $partner->leads_count > 0 ? ($partner->sales_count / $partner->leads_count) * 100 : 0;
                
                return [
                    'name' => $partner->name,
                    'leads_count' => $partner->leads_count,
                    'sales_count' => $partner->sales_count,
                    'total_commissions' => $totalCommissions,
                    'conversion_rate' => $conversionRate
                ];
            })
            ->values();

        // Recent activity
        $recentActivity = collect();
        
        // Recent sales
        $recentSales = Lead::where('status', 'sale')
            ->whereNotNull('sale_closed_at')
            ->with('referrer')
            ->orderBy('sale_closed_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($lead) {
                return [
                    'description' => "Sale closed: {$lead->name}" . ($lead->referrer ? " (referred by {$lead->referrer->name})" : ''),
                    'time' => $lead->sale_closed_at->diffForHumans(),
                    'color' => 'green',
                    'amount' => $lead->sale_amount
                ];
            });

        // Recent leads
        $recentLeads = Lead::where('status', 'lead')
            ->with('referrer')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get()
            ->map(function ($lead) {
                return [
                    'description' => "New lead: {$lead->name}" . ($lead->referrer ? " (referred by {$lead->referrer->name})" : ''),
                    'time' => $lead->created_at->diffForHumans(),
                    'color' => 'blue'
                ];
            });

        $recentActivity = $recentSales->concat($recentLeads)->sortByDesc('time')->take(8)->values();

        // Monthly trends (last 6 months)
        $monthlyTrends = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();

            $monthLeads = Lead::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
            $monthSales = Lead::where('status', 'sale')
                ->whereBetween('sale_closed_at', [$startOfMonth, $endOfMonth])
                ->count();
            $monthRevenue = Lead::where('status', 'sale')
                ->whereBetween('sale_closed_at', [$startOfMonth, $endOfMonth])
                ->sum('sale_amount') ?? 0;
            $monthCommissions = Commission::where('status', 'paid')
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->sum('amount') ?? 0;

            $monthConversionRate = $monthLeads > 0 ? ($monthSales / $monthLeads) * 100 : 0;

            $monthlyTrends[] = [
                'month' => $date->format('M Y'),
                'leads' => $monthLeads,
                'sales' => $monthSales,
                'conversion_rate' => $monthConversionRate,
                'revenue' => $monthRevenue,
                'commissions' => $monthCommissions
            ];
        }

        // Compile all analytics data
        $analytics = [
            'total_leads' => $totalLeads,
            'appointments_scheduled' => $appointmentsScheduled,
            'sales_closed' => $salesClosed,
            'conversion_rate' => $conversionRate,
            'pipeline_stages' => $pipelineStages,
            'total_revenue' => $totalRevenue,
            'average_deal_size' => $avgDealSize,
            'total_commissions' => $totalCommissions,
            'quick_close_bonuses' => $quickCloseBonuses,
            'average_time_to_close' => $avgTimeToClose,
            'quick_close_threshold' => $quickCloseThreshold,
            'quick_closes' => $quickCloseCount,
            'quick_close_rate' => $quickCloseRate,
            'top_partners' => $topPartners,
            'recent_activity' => $recentActivity,
            'monthly_trends' => $monthlyTrends
        ];

        return view('admin.pipeline-analytics', compact('analytics'));
    }
}
