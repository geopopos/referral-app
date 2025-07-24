<x-partner-layout>
    <x-slot name="header">
        <h2 class="font-poppins font-bold text-xl text-volume-dark leading-tight">
            {{ __('Partner Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Welcome Section -->
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-100">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-poppins text-xl font-bold text-volume-dark mb-1">Welcome back, {{ $user->name }}!</h3>
                            <p class="text-volume-gray text-sm">Here's your referral performance overview.</p>
                        </div>
                        <div class="hidden md:block">
                            <div class="w-12 h-12 bg-gradient-to-br from-volume-primary to-volume-secondary rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Two Ways to Refer Section -->
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-100">
                <div class="p-6">
                    <div class="text-center mb-6">
                        <h3 class="font-poppins text-2xl font-bold text-volume-dark mb-2">2 Ways to Refer & Earn</h3>
                        <p class="text-volume-gray">Choose the method that works best for you and your referrals</p>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Method 1: Referral Link -->
                        <div class="relative">
                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-6 rounded-xl border-2 border-blue-200">
                                <div class="flex items-center mb-4">
                                    <div class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center font-bold text-sm mr-3">1</div>
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.102m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                        </svg>
                                        <h4 class="font-poppins text-lg font-bold text-volume-dark">Share Your Referral Link</h4>
                                    </div>
                                </div>
                                <p class="text-sm text-volume-gray mb-4">Send your unique link directly to potential clients. Perfect for social media, email, or text messages.</p>
                                
                                <div class="flex items-center space-x-3 mb-3">
                                    <input 
                                        type="text" 
                                        value="{{ $user->referral_url }}" 
                                        readonly 
                                        class="flex-1 px-3 py-2.5 border-2 border-gray-200 rounded-lg bg-white text-xs font-mono focus:border-volume-primary focus:ring-volume-primary text-volume-dark"
                                        id="referral-link"
                                    >
                                    <button 
                                        onclick="copyReferralLink()" 
                                        class="px-4 py-2.5 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors font-semibold shadow-lg text-sm"
                                    >
                                        Copy
                                    </button>
                                </div>
                                <div class="flex items-start text-xs text-blue-700 bg-blue-100 p-3 rounded-lg">
                                    <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span><strong>How it works:</strong> When someone clicks your link and submits their info, you'll automatically get credit for the referral!</span>
                                </div>
                            </div>
                        </div>

                        <!-- Method 2: 3-Way Email -->
                        <div class="relative">
                            <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-6 rounded-xl border-2 border-green-200">
                                <div class="flex items-center mb-4">
                                    <div class="w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center font-bold text-sm mr-3">2</div>
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                        <h4 class="font-poppins text-lg font-bold text-volume-dark">3-Way Email Introduction</h4>
                                    </div>
                                </div>
                                <p class="text-sm text-volume-gray mb-4">Personally introduce your referral via email. Great for warm introductions and building trust.</p>
                                
                                <div class="flex items-center space-x-3 mb-3">
                                    <input 
                                        type="text" 
                                        value="partners+{{ $user->referral_code }}@volumeup.agency" 
                                        readonly 
                                        class="flex-1 px-3 py-2.5 border-2 border-gray-200 rounded-lg bg-white text-xs font-mono focus:border-volume-primary focus:ring-volume-primary text-volume-dark"
                                        id="referral-email"
                                    >
                                    <button 
                                        onclick="copyReferralEmail()" 
                                        class="px-4 py-2.5 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors font-semibold shadow-lg text-sm"
                                    >
                                        Copy
                                    </button>
                                </div>
                                <div class="text-xs text-green-700 bg-green-100 p-3 rounded-lg space-y-2">
                                    <div class="flex items-start">
                                        <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <div>
                                            <div class="font-semibold mb-1">How to send a 3-way email:</div>
                                            <div class="space-y-1">
                                                <div><strong>From:</strong> {{ $user->email }} (your account email)</div>
                                                <div><strong>To:</strong> partners+{{ $user->referral_code }}@volumeup.agency, [Your referral's email address]</div>
                                                <div class="mt-2 text-green-600">This creates a 3-way introduction connecting you, us, and your referral!</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-100 hover:shadow-xl transition-shadow">
                    <div class="p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs font-medium text-volume-gray">Total Leads</p>
                                <p class="text-2xl font-bold text-volume-dark">{{ $totalLeads }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-100 hover:shadow-xl transition-shadow">
                    <div class="p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs font-medium text-volume-gray">Pending Commissions</p>
                                <p class="text-2xl font-bold text-volume-dark">${{ number_format($pendingCommissions, 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-100 hover:shadow-xl transition-shadow">
                    <div class="p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs font-medium text-volume-gray">Approved Commissions</p>
                                <p class="text-2xl font-bold text-volume-dark">${{ number_format($approvedCommissions, 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-100 hover:shadow-xl transition-shadow">
                    <div class="p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-gradient-to-br from-volume-primary to-volume-secondary rounded-xl flex items-center justify-center shadow-lg">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs font-medium text-volume-gray">Fast-Close Bonuses</p>
                                <p class="text-2xl font-bold text-volume-dark">${{ number_format($fastCloseBonuses, 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Leads -->
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-100">
                <div class="p-6">
                    <h3 class="font-poppins text-lg font-bold text-volume-dark mb-4">Recent Leads</h3>
                    @if($leads->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-volume-light">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-volume-dark uppercase tracking-wider">Name</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-volume-dark uppercase tracking-wider">Company</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-volume-dark uppercase tracking-wider">Status</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-volume-dark uppercase tracking-wider">Date</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @foreach($leads as $lead)
                                        <tr class="hover:bg-volume-light transition-colors">
                                            <td class="px-4 py-3 whitespace-nowrap text-sm font-semibold text-volume-dark">{{ $lead->name }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-volume-gray">{{ $lead->company }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <span class="inline-flex px-2 py-1 text-xs font-bold rounded-full 
                                                    @if($lead->status === 'new') bg-blue-100 text-blue-800
                                                    @elseif($lead->status === 'qualified') bg-yellow-100 text-yellow-800
                                                    @else bg-green-100 text-green-800 @endif">
                                                    {{ ucfirst($lead->status) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-volume-gray">{{ $lead->created_at->format('M j, Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-10 w-10 text-volume-gray" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <h3 class="mt-3 text-base font-semibold text-volume-dark">No leads yet</h3>
                            <p class="mt-1 text-sm text-volume-gray">Start sharing your referral link to see leads here!</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <script>
        function copyReferralLink() {
            const input = document.getElementById('referral-link');
            input.select();
            input.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(input.value);
            
            // Show feedback
            const button = event.target;
            const originalText = button.textContent;
            button.textContent = 'Copied!';
            button.classList.remove('bg-blue-500', 'hover:bg-blue-600');
            button.classList.add('bg-green-500');
            
            setTimeout(() => {
                button.textContent = originalText;
                button.classList.remove('bg-green-500');
                button.classList.add('bg-blue-500', 'hover:bg-blue-600');
            }, 2000);
        }

        function copyReferralEmail() {
            const input = document.getElementById('referral-email');
            input.select();
            input.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(input.value);
            
            // Show feedback
            const button = event.target;
            const originalText = button.textContent;
            button.textContent = 'Copied!';
            button.classList.remove('bg-green-500', 'hover:bg-green-600');
            button.classList.add('bg-emerald-600');
            
            setTimeout(() => {
                button.textContent = originalText;
                button.classList.remove('bg-emerald-600');
                button.classList.add('bg-green-500', 'hover:bg-green-600');
            }, 2000);
        }
    </script>
</x-partner-layout>
