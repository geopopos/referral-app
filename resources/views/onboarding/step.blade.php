<x-app-layout>
    <x-slot name="header">
        <h2 class="font-poppins font-bold text-2xl text-volume-dark leading-tight">
            Complete Your Partner Profile
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Progress Bar -->
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-100 mb-8">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Step {{ $step }} of 4</h3>
                        <span class="text-sm text-gray-500">{{ number_format($progress, 0) }}% Complete</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-gradient-to-r from-volume-primary to-volume-secondary h-3 rounded-full transition-all duration-300" 
                             style="width: {{ $progress }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Step Content -->
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-100">
                <div class="p-8">
                    
                    @if (session('success'))
                        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('onboarding.store', ['step' => $step]) }}" class="space-y-6">
                        @csrf

                        @if ($step == 1)
                            <!-- Step 1: Business Basics -->
                            <div class="text-center mb-8">
                                <h3 class="text-2xl font-bold text-gray-900 mb-2">Tell us about your business</h3>
                                <p class="text-gray-600">Help us understand your business so we can provide better support.</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="md:col-span-2">
                                    <label for="website_url" class="block text-sm font-medium text-gray-700 mb-2">
                                        Website URL (Optional)
                                    </label>
                                    <input type="url" 
                                           id="website_url" 
                                           name="website_url" 
                                           value="{{ old('website_url', $user->website_url) }}"
                                           placeholder="https://yourwebsite.com"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-volume-primary focus:border-volume-primary">
                                </div>

                                <div>
                                    <label for="business_type" class="block text-sm font-medium text-gray-700 mb-2">
                                        Business Type/Industry *
                                    </label>
                                    <select id="business_type" 
                                            name="business_type" 
                                            required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-volume-primary focus:border-volume-primary">
                                        <option value="">Select your industry</option>
                                        <option value="appointment_setting" {{ old('business_type', $user->business_type) == 'appointment_setting' ? 'selected' : '' }}>Appointment Setting</option>
                                        <option value="sales_training" {{ old('business_type', $user->business_type) == 'sales_training' ? 'selected' : '' }}>Sales Training</option>
                                        <option value="ghl_consultant" {{ old('business_type', $user->business_type) == 'ghl_consultant' ? 'selected' : '' }}>GHL Consultant</option>
                                        <option value="seo_agency" {{ old('business_type', $user->business_type) == 'seo_agency' ? 'selected' : '' }}>SEO / Web Design</option>
                                        <option value="virtual_assistant_agency" {{ old('business_type', $user->business_type) == 'virtual_assistant_agency' ? 'selected' : '' }}>VA / Back Office Support</option>
                                        <option value="financing_rep" {{ old('business_type', $user->business_type) == 'financing_rep' ? 'selected' : '' }}>Financing Rep</option>
                                        <option value="other" {{ old('business_type', $user->business_type) == 'other' ? 'selected' : '' }}>Other</option>

                                    </select>
                                </div>

                                <div>
                                    <label for="years_in_business" class="block text-sm font-medium text-gray-700 mb-2">
                                        Years in Business *
                                    </label>
                                    <select id="years_in_business" 
                                            name="years_in_business" 
                                            required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-volume-primary focus:border-volume-primary">
                                        <option value="">Select years</option>
                                        <option value="0" {{ old('years_in_business', $user->years_in_business) == '0' ? 'selected' : '' }}>Just starting</option>
                                        <option value="1" {{ old('years_in_business', $user->years_in_business) == '1' ? 'selected' : '' }}>1 year</option>
                                        <option value="2" {{ old('years_in_business', $user->years_in_business) == '2' ? 'selected' : '' }}>2 years</option>
                                        <option value="3" {{ old('years_in_business', $user->years_in_business) == '3' ? 'selected' : '' }}>3 years</option>
                                        <option value="5" {{ old('years_in_business', $user->years_in_business) == '5' ? 'selected' : '' }}>4-5 years</option>
                                        <option value="10" {{ old('years_in_business', $user->years_in_business) == '10' ? 'selected' : '' }}>6-10 years</option>
                                        <option value="15" {{ old('years_in_business', $user->years_in_business) == '15' ? 'selected' : '' }}>10+ years</option>
                                    </select>
                                </div>

                                <div class="md:col-span-2">
                                    <label for="primary_services" class="block text-sm font-medium text-gray-700 mb-2">
                                        Primary Services Offered *
                                    </label>
                                    <textarea id="primary_services" 
                                              name="primary_services" 
                                              rows="4" 
                                              required
                                              placeholder="Describe the main services you offer to your clients..."
                                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-volume-primary focus:border-volume-primary">{{ old('primary_services', $user->primary_services) }}</textarea>
                                </div>
                            </div>

                        @elseif ($step == 2)
                            <!-- Step 2: Current Situation -->
                            <div class="text-center mb-8">
                                <h3 class="text-2xl font-bold text-gray-900 mb-2">Your current business situation</h3>
                                <p class="text-gray-600">This helps us understand your scale and potential opportunities.</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="current_client_count" class="block text-sm font-medium text-gray-700 mb-2">
                                        Current Number of Clients *
                                    </label>
                                    <select id="current_client_count" 
                                            name="current_client_count" 
                                            required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-volume-primary focus:border-volume-primary">
                                        <option value="">Select range</option>
                                        <option value="0" {{ old('current_client_count', $user->current_client_count) == '0' ? 'selected' : '' }}>0 (Just starting)</option>
                                        <option value="3" {{ old('current_client_count', $user->current_client_count) == '3' ? 'selected' : '' }}>1-5 clients</option>
                                        <option value="8" {{ old('current_client_count', $user->current_client_count) == '8' ? 'selected' : '' }}>6-10 clients</option>
                                        <option value="15" {{ old('current_client_count', $user->current_client_count) == '15' ? 'selected' : '' }}>11-20 clients</option>
                                        <option value="30" {{ old('current_client_count', $user->current_client_count) == '30' ? 'selected' : '' }}>21-50 clients</option>
                                        <option value="75" {{ old('current_client_count', $user->current_client_count) == '75' ? 'selected' : '' }}>50+ clients</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="average_project_value" class="block text-sm font-medium text-gray-700 mb-2">
                                        Average Project Value *
                                    </label>
                                    <select id="average_project_value" 
                                            name="average_project_value" 
                                            required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-volume-primary focus:border-volume-primary">
                                        <option value="">Select range</option>
                                        <option value="500" {{ old('average_project_value', $user->average_project_value) == '500' ? 'selected' : '' }}>$500 or less</option>
                                        <option value="1500" {{ old('average_project_value', $user->average_project_value) == '1500' ? 'selected' : '' }}>$500 - $2,500</option>
                                        <option value="5000" {{ old('average_project_value', $user->average_project_value) == '5000' ? 'selected' : '' }}>$2,500 - $7,500</option>
                                        <option value="12500" {{ old('average_project_value', $user->average_project_value) == '12500' ? 'selected' : '' }}>$7,500 - $15,000</option>
                                        <option value="25000" {{ old('average_project_value', $user->average_project_value) == '25000' ? 'selected' : '' }}>$15,000 - $35,000</option>
                                        <option value="50000" {{ old('average_project_value', $user->average_project_value) == '50000' ? 'selected' : '' }}>$35,000+</option>
                                    </select>
                                </div>

                                <div class="md:col-span-2">
                                    <label for="biggest_challenge" class="block text-sm font-medium text-gray-700 mb-2">
                                        What's your biggest business challenge right now? *
                                    </label>
                                    <textarea id="biggest_challenge" 
                                              name="biggest_challenge" 
                                              rows="4" 
                                              required
                                              placeholder="Tell us about your main challenge - finding clients, scaling operations, pricing, etc..."
                                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-volume-primary focus:border-volume-primary">{{ old('biggest_challenge', $user->biggest_challenge) }}</textarea>
                                </div>
                            </div>

                        @elseif ($step == 3)
                            <!-- Step 3: Payment & Contact -->
                            <div class="text-center mb-8">
                                <h3 class="text-2xl font-bold text-gray-900 mb-2">Payment and contact details</h3>
                                <p class="text-gray-600">We need this information to process your referral commissions.</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                        Phone Number *
                                    </label>
                                    <input type="tel" 
                                           id="phone" 
                                           name="phone" 
                                           value="{{ old('phone', $user->phone) }}"
                                           required
                                           placeholder="+1 (555) 123-4567"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-volume-primary focus:border-volume-primary">
                                </div>

                                <div>
                                    <label for="timezone" class="block text-sm font-medium text-gray-700 mb-2">
                                        Time Zone *
                                    </label>
                                    <select id="timezone" 
                                            name="timezone" 
                                            required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-volume-primary focus:border-volume-primary">
                                        <option value="">Select timezone</option>
                                        <option value="America/New_York" {{ old('timezone', $user->timezone) == 'America/New_York' ? 'selected' : '' }}>Eastern Time (ET)</option>
                                        <option value="America/Chicago" {{ old('timezone', $user->timezone) == 'America/Chicago' ? 'selected' : '' }}>Central Time (CT)</option>
                                        <option value="America/Denver" {{ old('timezone', $user->timezone) == 'America/Denver' ? 'selected' : '' }}>Mountain Time (MT)</option>
                                        <option value="America/Los_Angeles" {{ old('timezone', $user->timezone) == 'America/Los_Angeles' ? 'selected' : '' }}>Pacific Time (PT)</option>
                                        <option value="Pacific/Honolulu" {{ old('timezone', $user->timezone) == 'Pacific/Honolulu' ? 'selected' : '' }}>Hawaii Time (HST)</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="paypal_email" class="block text-sm font-medium text-gray-700 mb-2">
                                        PayPal Email *
                                    </label>
                                    <input type="email" 
                                           id="paypal_email" 
                                           name="paypal_email" 
                                           value="{{ old('paypal_email', $user->email) }}"
                                           required
                                           placeholder="your-paypal@email.com"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-volume-primary focus:border-volume-primary">
                                </div>

                                <div>
                                    <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">
                                        Preferred Payment Method *
                                    </label>
                                    <select id="payment_method" 
                                            name="payment_method" 
                                            required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-volume-primary focus:border-volume-primary">
                                        <option value="">Select method</option>
                                        <option value="paypal" {{ old('payment_method', $user->payment_method) == 'paypal' ? 'selected' : '' }}>PayPal</option>
                                        <option value="ach" {{ old('payment_method', $user->payment_method) == 'ach' ? 'selected' : '' }}>ACH Bank Transfer</option>
                                    </select>
                                </div>

                                <div class="md:col-span-2">
                                    <label for="preferred_communication" class="block text-sm font-medium text-gray-700 mb-2">
                                        Preferred Communication Method *
                                    </label>
                                    <select id="preferred_communication" 
                                            name="preferred_communication" 
                                            required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-volume-primary focus:border-volume-primary">
                                        <option value="">Select method</option>
                                        <option value="email" {{ old('preferred_communication', $user->preferred_communication) == 'email' ? 'selected' : '' }}>Email</option>
                                        <option value="phone" {{ old('preferred_communication', $user->preferred_communication) == 'phone' ? 'selected' : '' }}>Phone</option>
                                        <option value="slack" {{ old('preferred_communication', $user->preferred_communication) == 'slack' ? 'selected' : '' }}>Slack</option>
                                        <option value="teams" {{ old('preferred_communication', $user->preferred_communication) == 'teams' ? 'selected' : '' }}>Microsoft Teams</option>
                                    </select>
                                </div>
                            </div>

                        @elseif ($step == 4)
                            <!-- Step 4: Growth Opportunities -->
                            <div class="text-center mb-8">
                                <h3 class="text-2xl font-bold text-gray-900 mb-2">Growth opportunities</h3>
                                <p class="text-gray-600">Let us know how we can help you grow your business.</p>
                            </div>

                            <div class="space-y-6">
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                                    <div class="flex items-start">
                                        <input type="checkbox" 
                                               id="wants_advertising_help" 
                                               name="wants_advertising_help" 
                                               value="1"
                                               {{ old('wants_advertising_help', $user->wants_advertising_help) ? 'checked' : '' }}
                                               class="mt-1 h-5 w-5 text-volume-primary border-gray-300 rounded focus:ring-volume-primary">
                                        <div class="ml-4">
                                            <label for="wants_advertising_help" class="text-lg font-semibold text-gray-900 cursor-pointer">
                                                Yes, I'd like help increasing my client flow with paid advertising strategies
                                            </label>
                                            <p class="text-gray-600 mt-2">
                                                Our team can help you implement proven paid advertising strategies to attract more high-quality clients. 
                                                This includes Google Ads, Facebook Ads, LinkedIn campaigns, and more.
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label for="referral_source" class="block text-sm font-medium text-gray-700 mb-2">
                                        How did you hear about our referral program? (Optional)
                                    </label>
                                    <select id="referral_source" 
                                            name="referral_source"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-volume-primary focus:border-volume-primary">
                                        <option value="">Select source</option>
                                        <option value="google_search" {{ old('referral_source', $user->referral_source) == 'google_search' ? 'selected' : '' }}>Google Search</option>
                                        <option value="social_media" {{ old('referral_source', $user->referral_source) == 'social_media' ? 'selected' : '' }}>Social Media</option>
                                        <option value="friend_referral" {{ old('referral_source', $user->referral_source) == 'friend_referral' ? 'selected' : '' }}>Friend/Colleague Referral</option>
                                        <option value="existing_client" {{ old('referral_source', $user->referral_source) == 'existing_client' ? 'selected' : '' }}>Existing Client</option>
                                        <option value="industry_event" {{ old('referral_source', $user->referral_source) == 'industry_event' ? 'selected' : '' }}>Industry Event</option>
                                        <option value="other" {{ old('referral_source', $user->referral_source) == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                            </div>
                        @endif

                        <!-- Navigation Buttons -->
                        <div class="flex justify-between items-center pt-8 border-t border-gray-200">
                            @if ($step > 1)
                                <a href="{{ route('onboarding.step', ['step' => $step - 1]) }}" 
                                   class="inline-flex items-center px-6 py-3 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-volume-primary transition duration-150 ease-in-out">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                    Previous
                                </a>
                            @else
                                <div></div>
                            @endif

                            <button type="submit" 
                                    class="inline-flex items-center px-8 py-3 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-volume-primary to-volume-secondary hover:from-volume-secondary hover:to-volume-primary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-volume-primary transition duration-150 ease-in-out shadow-lg">
                                @if ($step < 4)
                                    Continue
                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                @else
                                    Complete Setup
                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                @endif
                            </button>
                        </div>
                    </form>

                    <!-- Skip Option (for testing) -->
                    @if (config('app.debug'))
                        <div class="mt-6 text-center">
                            <a href="{{ route('onboarding.skip') }}" 
                               class="text-sm text-gray-500 hover:text-gray-700">
                                Skip onboarding (for testing)
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
