<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-poppins font-bold text-xl text-volume-dark leading-tight">
            {{ __('Pipeline Analytics') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Pipeline Overview -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-100">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-xs font-medium text-volume-gray">Total Leads</p>
                                <p class="text-2xl font-bold text-volume-dark">{{ $analytics['total_leads'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-100">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-xs font-medium text-volume-gray">Appointments</p>
                                <p class="text-2xl font-bold text-volume-dark">{{ $analytics['appointments_scheduled'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-100">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-xs font-medium text-volume-gray">Sales Closed</p>
                                <p class="text-2xl font-bold text-volume-dark">{{ $analytics['sales_closed'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-100">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-gradient-to-br from-volume-primary to-volume-secondary rounded-xl flex items-center justify-center shadow-lg">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-xs font-medium text-volume-gray">Conversion Rate</p>
                                <p class="text-2xl font-bold text-volume-dark">{{ number_format($analytics['conversion_rate'], 1) }}%</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pipeline Funnel -->
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-100 mb-6">
                <div class="p-6">
                    <h3 class="font-poppins text-lg font-bold text-volume-dark mb-6">Sales Pipeline Funnel</h3>
                    
                    <div class="space-y-4">
                        @foreach($analytics['pipeline_stages'] as $stage => $data)
                            <div class="flex items-center">
                                <div class="w-32 text-sm font-semibold text-volume-dark">
                                    {{ $data['label'] }}
                                </div>
                                <div class="flex-1 mx-4">
                                    <div class="bg-gray-200 rounded-full h-8">
                                        <div class="bg-gradient-to-r from-volume-primary to-volume-secondary h-8 rounded-full flex items-center justify-center text-white text-sm font-semibold" 
                                             style="width: {{ $data['percentage'] }}%">
                                            {{ $data['count'] }} ({{ number_format($data['percentage'], 1) }}%)
                                        </div>
                                    </div>
                                </div>
                                <div class="w-20 text-right text-sm font-medium text-volume-gray">
                                    {{ $data['count'] }} leads
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Revenue Analytics -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-100">
                    <div class="p-6">
                        <h3 class="font-poppins text-lg font-bold text-volume-dark mb-6">Revenue Analytics</h3>
                        
                        <div class="space-y-4">
                            <div class="flex justify-between items-center p-3 bg-volume-light rounded-lg">
                                <span class="text-sm font-semibold text-volume-dark">Total Revenue</span>
                                <span class="text-lg font-bold text-green-600">
                                    ${{ number_format($analytics['total_revenue'], 2) }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-volume-light rounded-lg">
                                <span class="text-sm font-semibold text-volume-dark">Average Deal Size</span>
                                <span class="text-lg font-bold text-blue-600">
                                    ${{ number_format($analytics['average_deal_size'], 2) }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-volume-light rounded-lg">
                                <span class="text-sm font-semibold text-volume-dark">Total Commissions Paid</span>
                                <span class="text-lg font-bold text-volume-primary">
                                    ${{ number_format($analytics['total_commissions'], 2) }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-volume-light rounded-lg">
                                <span class="text-sm font-semibold text-volume-dark">Quick Close Bonuses</span>
                                <span class="text-lg font-bold text-orange-600">
                                    ${{ number_format($analytics['quick_close_bonuses'], 2) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-100">
                    <div class="p-6">
                        <h3 class="font-poppins text-lg font-bold text-volume-dark mb-6">Time to Close Analytics</h3>
                        
                        <div class="space-y-4">
                            <div class="flex justify-between items-center p-3 bg-volume-light rounded-lg">
                                <span class="text-sm font-semibold text-volume-dark">Average Time to Close</span>
                                <span class="text-lg font-bold text-volume-dark">
                                    {{ $analytics['average_time_to_close'] }} days
                                </span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-volume-light rounded-lg">
                                <span class="text-sm font-semibold text-volume-dark">Quick Closes (≤{{ $analytics['quick_close_threshold'] }} days)</span>
                                <span class="text-lg font-bold text-green-600">
                                    {{ $analytics['quick_closes'] }} deals
                                </span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-volume-light rounded-lg">
                                <span class="text-sm font-semibold text-volume-dark">Quick Close Rate</span>
                                <span class="text-lg font-bold text-green-600">
                                    {{ number_format($analytics['quick_close_rate'], 1) }}%
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Performers -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-100">
                    <div class="p-6">
                        <h3 class="font-poppins text-lg font-bold text-volume-dark mb-6">Top Performing Partners</h3>
                        
                        @if(count($analytics['top_partners']) > 0)
                            <div class="space-y-3">
                                @foreach($analytics['top_partners'] as $partner)
                                    <div class="flex items-center justify-between p-4 bg-volume-light rounded-lg border border-gray-100">
                                        <div>
                                            <p class="font-semibold text-volume-dark">{{ $partner['name'] }}</p>
                                            <p class="text-sm text-volume-gray">{{ $partner['leads_count'] }} leads • {{ $partner['sales_count'] }} sales</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-bold text-green-600">${{ number_format($partner['total_commissions'], 2) }}</p>
                                            <p class="text-xs text-volume-gray">{{ number_format($partner['conversion_rate'], 1) }}% conversion</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="mx-auto h-10 w-10 text-volume-gray" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <p class="mt-3 text-base font-semibold text-volume-dark">No partner data available yet</p>
                                <p class="mt-1 text-sm text-volume-gray">Partner performance will appear here once leads are generated.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-100">
                    <div class="p-6">
                        <h3 class="font-poppins text-lg font-bold text-volume-dark mb-6">Recent Activity</h3>
                        
                        @if(count($analytics['recent_activity']) > 0)
                            <div class="space-y-4">
                                @foreach($analytics['recent_activity'] as $activity)
                                    <div class="flex items-start space-x-3 p-3 bg-volume-light rounded-lg">
                                        <div class="flex-shrink-0">
                                            <div class="w-3 h-3 bg-{{ $activity['color'] }}-500 rounded-full mt-2"></div>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-volume-dark">{{ $activity['description'] }}</p>
                                            <p class="text-xs text-volume-gray">{{ $activity['time'] }}</p>
                                        </div>
                                        @if(isset($activity['amount']))
                                            <div class="text-sm font-bold text-green-600">
                                                ${{ number_format($activity['amount'], 2) }}
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="mx-auto h-10 w-10 text-volume-gray" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="mt-3 text-base font-semibold text-volume-dark">No recent activity</p>
                                <p class="mt-1 text-sm text-volume-gray">Activity will appear here as leads progress through the pipeline.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Monthly Trends -->
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-100">
                <div class="p-6">
                    <h3 class="font-poppins text-lg font-bold text-volume-dark mb-6">Monthly Trends (Last 6 Months)</h3>
                    
                    @if(count($analytics['monthly_trends']) > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-volume-light">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-volume-dark uppercase tracking-wider">Month</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-volume-dark uppercase tracking-wider">Leads</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-volume-dark uppercase tracking-wider">Sales</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-volume-dark uppercase tracking-wider">Conversion</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-volume-dark uppercase tracking-wider">Revenue</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-volume-dark uppercase tracking-wider">Commissions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @foreach($analytics['monthly_trends'] as $month)
                                        <tr class="hover:bg-volume-light transition-colors">
                                            <td class="px-4 py-3 whitespace-nowrap text-sm font-semibold text-volume-dark">
                                                {{ $month['month'] }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-volume-gray">
                                                {{ $month['leads'] }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-volume-gray">
                                                {{ $month['sales'] }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-volume-gray">
                                                {{ number_format($month['conversion_rate'], 1) }}%
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm font-semibold text-green-600">
                                                ${{ number_format($month['revenue'], 2) }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm font-semibold text-volume-primary">
                                                ${{ number_format($month['commissions'], 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-volume-gray" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <h3 class="mt-4 text-lg font-semibold text-volume-dark">No historical data available yet</h3>
                            <p class="mt-2 text-sm text-volume-gray">Monthly trends will appear here as your referral program grows.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
