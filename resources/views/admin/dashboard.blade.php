<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-poppins font-bold text-xl text-volume-dark leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-100 hover:shadow-xl transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-gradient-to-br from-volume-primary to-volume-secondary rounded-xl flex items-center justify-center shadow-lg">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-xs font-medium text-volume-gray">Total Partners</p>
                                <p class="text-2xl font-bold text-volume-dark">{{ $partners->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-100 hover:shadow-xl transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-xs font-medium text-volume-gray">Total Leads</p>
                                <p class="text-2xl font-bold text-volume-dark">{{ $totalLeads }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-100 hover:shadow-xl transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-xs font-medium text-volume-gray">Total Commissions</p>
                                <p class="text-2xl font-bold text-volume-dark">${{ number_format($totalCommissions, 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-100 hover:shadow-xl transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-xs font-medium text-volume-gray">Pending Commissions</p>
                                <p class="text-2xl font-bold text-volume-dark">${{ number_format($pendingCommissions, 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Partners Overview -->
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-100">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="font-poppins text-lg font-bold text-volume-dark">Partner Overview</h3>
                        <a href="{{ route('admin.export.leads') }}" class="bg-volume-primary hover:bg-volume-secondary text-white font-semibold py-2.5 px-4 rounded-lg text-sm transition-colors shadow-lg">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Export Leads CSV
                        </a>
                    </div>
                    
                    @if($partners->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-volume-light">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-volume-dark uppercase tracking-wider">Partner</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-volume-dark uppercase tracking-wider">Email</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-volume-dark uppercase tracking-wider">Referral Code</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-volume-dark uppercase tracking-wider">Total Leads</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-volume-dark uppercase tracking-wider">Payout Method</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-volume-dark uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @foreach($partners as $partner)
                                        <tr class="hover:bg-volume-light transition-colors">
                                            <td class="px-4 py-3 whitespace-nowrap text-sm font-semibold text-volume-dark">{{ $partner->name }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-volume-gray">{{ $partner->email }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-volume-gray font-mono bg-gray-50 rounded px-2 py-1">{{ $partner->referral_code }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm font-semibold text-volume-dark">{{ $partner->leads_count }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-volume-gray">
                                                <span class="inline-flex px-2 py-1 text-xs font-bold rounded-full 
                                                    @if($partner->payout_method === 'ach') bg-blue-100 text-blue-800
                                                    @elseif($partner->payout_method === 'paypal') bg-yellow-100 text-yellow-800
                                                    @else bg-gray-100 text-gray-800 @endif">
                                                    {{ $partner->payout_method ? ucfirst($partner->payout_method) : 'Not Set' }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('admin.partners.show', $partner) }}" class="text-volume-primary hover:text-volume-secondary font-semibold transition-colors">View Details</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-volume-gray" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <h3 class="mt-4 text-lg font-semibold text-volume-dark">No partners yet</h3>
                            <p class="mt-2 text-sm text-volume-gray">Partners will appear here once they register for the program.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-admin-layout>
