<x-partner-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Referral Tools') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Referral Link Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Your Referral Link</h3>
                    <p class="text-sm text-gray-600 mb-4">Share this link with contractors to earn commissions on successful referrals.</p>
                    
                    <div class="flex items-center space-x-2">
                        <input type="text" 
                               value="{{ $referralUrl }}" 
                               readonly 
                               class="flex-1 px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-sm"
                               id="referral-url">
                        <button onclick="copyToClipboard('referral-url')" 
                                class="px-4 py-2 bg-volume-primary text-white text-sm font-medium rounded-md hover:bg-volume-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-volume-primary">
                            Copy
                        </button>
                    </div>
                    
                    <div class="mt-4 p-4 bg-blue-50 rounded-md">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">Your Referral Code: {{ $referralCode }}</h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <p>When contractors use this link or enter your referral code, you'll earn commissions on their successful projects.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Marketing Materials -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Marketing Materials</h3>
                    <p class="text-sm text-gray-600 mb-6">Use these materials to promote Volume Up Agency to contractors.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Email Template -->
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h4 class="font-medium text-gray-900 mb-2">Email Template</h4>
                            <p class="text-sm text-gray-600 mb-3">Ready-to-use email template for reaching out to contractors.</p>
                            <textarea readonly 
                                      class="w-full h-32 px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-sm resize-none"
                                      id="email-template">Subject: Grow Your Roofing Business with Volume Up Agency

Hi [Contractor Name],

I wanted to share an opportunity that could help grow your roofing business. Volume Up Agency specializes in generating high-quality leads for contractors like yourself.

They offer:
• Qualified leads in your area
• No upfront costs
• Pay only for results
• Professional marketing support

I've partnered with them and thought you might be interested. You can learn more and get started here: {{ $referralUrl }}

Best regards,
[Your Name]</textarea>
                            <button onclick="copyToClipboard('email-template')" 
                                    class="mt-2 px-3 py-1 bg-gray-100 text-gray-700 text-sm rounded hover:bg-gray-200">
                                Copy Template
                            </button>
                        </div>

                        <!-- Social Media Post -->
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h4 class="font-medium text-gray-900 mb-2">Social Media Post</h4>
                            <p class="text-sm text-gray-600 mb-3">Share this on your social media channels.</p>
                            <textarea readonly 
                                      class="w-full h-32 px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-sm resize-none"
                                      id="social-template">🏠 Attention Roofing Contractors! 🏠

Looking to grow your business with qualified leads? Volume Up Agency has helped contractors across the country scale their operations.

✅ High-quality leads
✅ No upfront costs
✅ Pay for results only
✅ Professional support

Ready to take your roofing business to the next level? 

Learn more: {{ $referralUrl }}

#RoofingContractor #BusinessGrowth #Leads #VolumeUpAgency</textarea>
                            <button onclick="copyToClipboard('social-template')" 
                                    class="mt-2 px-3 py-1 bg-gray-100 text-gray-700 text-sm rounded hover:bg-gray-200">
                                Copy Post
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Commission Structure -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Commission Structure</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="text-center p-4 bg-blue-50 rounded-lg">
                            <div class="text-2xl font-bold text-blue-600">$500</div>
                            <div class="text-sm text-gray-600">Base Commission</div>
                            <div class="text-xs text-gray-500 mt-1">Per qualified lead</div>
                        </div>
                        <div class="text-center p-4 bg-green-50 rounded-lg">
                            <div class="text-2xl font-bold text-green-600">$200</div>
                            <div class="text-sm text-gray-600">Fast Close Bonus</div>
                            <div class="text-xs text-gray-500 mt-1">If closed within 30 days</div>
                        </div>
                        <div class="text-center p-4 bg-purple-50 rounded-lg">
                            <div class="text-2xl font-bold text-purple-600">Monthly</div>
                            <div class="text-sm text-gray-600">Payouts</div>
                            <div class="text-xs text-gray-500 mt-1">Paid on the 15th</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function copyToClipboard(elementId) {
            const element = document.getElementById(elementId);
            element.select();
            element.setSelectionRange(0, 99999);
            document.execCommand('copy');
            
            // Show feedback
            const button = event.target;
            const originalText = button.textContent;
            button.textContent = 'Copied!';
            button.classList.add('bg-green-500', 'text-white');
            button.classList.remove('bg-volume-primary', 'bg-gray-100', 'text-gray-700');
            
            setTimeout(() => {
                button.textContent = originalText;
                button.classList.remove('bg-green-500', 'text-white');
                if (elementId === 'referral-url') {
                    button.classList.add('bg-volume-primary', 'text-white');
                } else {
                    button.classList.add('bg-gray-100', 'text-gray-700');
                }
            }, 2000);
        }
    </script>
</x-partner-layout>
