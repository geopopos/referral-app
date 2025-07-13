<x-partner-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Commissions') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 overflow-hidden">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Commission History ({{ $commissions->total() }})</h3>
                    
                    @if($commissions->count() > 0)
                        <div class="w-full overflow-x-auto">
                            <div class="min-w-full">
                                <table class="w-full divide-y divide-gray-200 border border-gray-200 rounded-lg">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lead</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($commissions as $commission)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">{{ $commission->lead->name }}</div>
                                                    <div class="text-sm text-gray-500">{{ $commission->lead->company }}</div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                                    @if($commission->type === 'base') bg-blue-100 text-blue-800
                                                    @else bg-green-100 text-green-800 @endif">
                                                    {{ ucfirst($commission->type) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                ${{ number_format($commission->amount, 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                                    @if($commission->status === 'pending') bg-yellow-100 text-yellow-800
                                                    @elseif($commission->status === 'approved') bg-green-100 text-green-800
                                                    @else bg-blue-100 text-blue-800 @endif">
                                                    {{ ucfirst($commission->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $commission->created_at->format('M j, Y') }}
                                                <div class="text-xs text-gray-400">{{ $commission->created_at->format('g:i A') }}</div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $commissions->links() }}
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No commissions yet</h3>
                            <p class="mt-1 text-sm text-gray-500">Commissions will appear here once your leads are qualified.</p>
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
