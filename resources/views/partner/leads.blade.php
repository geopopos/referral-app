<x-partner-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Leads') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 overflow-hidden">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Your Referred Leads ({{ $leads->total() }})</h3>
                    
                    @if($leads->count() > 0)
                        <div class="w-full overflow-x-auto">
                            <div class="min-w-full">
                                <table class="w-full divide-y divide-gray-200 border border-gray-200 rounded-lg">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lead Info</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Commission</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($leads as $lead)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">{{ $lead->name }}</div>
                                                    <div class="text-sm text-gray-500">{{ $lead->company }}</div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $lead->email }}</div>
                                                <div class="text-sm text-gray-500">{{ $lead->phone }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                                    @if($lead->status === 'new') bg-blue-100 text-blue-800
                                                    @elseif($lead->status === 'qualified') bg-yellow-100 text-yellow-800
                                                    @else bg-green-100 text-green-800 @endif">
                                                    {{ ucfirst($lead->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                @if($lead->commissions->count() > 0)
                                                    ${{ number_format($lead->commissions->sum('amount'), 2) }}
                                                    <div class="text-xs text-gray-500">
                                                        {{ $lead->commissions->first()->status }}
                                                    </div>
                                                @else
                                                    <span class="text-gray-400">Pending</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $lead->created_at->format('M j, Y') }}
                                                <div class="text-xs text-gray-400">{{ $lead->created_at->format('g:i A') }}</div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $leads->links() }}
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No leads yet</h3>
                            <p class="mt-1 text-sm text-gray-500">Start referring contractors to earn commissions.</p>
                            <div class="mt-6">
                                <a href="{{ route('partner.tools') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-volume-primary hover:bg-volume-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-volume-primary">
                                    Get Referral Tools
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-partner-layout>
