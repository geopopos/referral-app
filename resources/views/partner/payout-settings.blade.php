<x-partner-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Payout Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Payment Information</h3>
                    <p class="text-sm text-gray-600 mb-6">Configure how you'd like to receive your commission payments.</p>

                    @if(session('success'))
                        <div class="mb-6 bg-green-50 border border-green-200 rounded-md p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('partner.payout-settings.update') }}">
                        @csrf
                        
                        <!-- Payout Method -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">Payout Method</label>
                            <div class="space-y-3">
                                <div class="flex items-center">
                                    <input id="paypal" name="payout_method" type="radio" value="paypal" 
                                           class="focus:ring-volume-primary h-4 w-4 text-volume-primary border-gray-300"
                                           {{ old('payout_method', $user->payout_method) === 'paypal' ? 'checked' : '' }}>
                                    <label for="paypal" class="ml-3 block text-sm font-medium text-gray-700">
                                        PayPal
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input id="bank_transfer" name="payout_method" type="radio" value="bank_transfer" 
                                           class="focus:ring-volume-primary h-4 w-4 text-volume-primary border-gray-300"
                                           {{ old('payout_method', $user->payout_method) === 'bank_transfer' ? 'checked' : '' }}>
                                    <label for="bank_transfer" class="ml-3 block text-sm font-medium text-gray-700">
                                        Bank Transfer (ACH)
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input id="check" name="payout_method" type="radio" value="check" 
                                           class="focus:ring-volume-primary h-4 w-4 text-volume-primary border-gray-300"
                                           {{ old('payout_method', $user->payout_method) === 'check' ? 'checked' : '' }}>
                                    <label for="check" class="ml-3 block text-sm font-medium text-gray-700">
                                        Check (Mail)
                                    </label>
                                </div>
                            </div>
                            @error('payout_method')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Payout Details -->
                        <div class="mb-6">
                            <label for="payout_details" class="block text-sm font-medium text-gray-700 mb-2">
                                Payment Details
                            </label>
                            <textarea id="payout_details" 
                                      name="payout_details" 
                                      rows="4" 
                                      class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-volume-primary focus:border-volume-primary sm:text-sm"
                                      placeholder="Enter your payment details based on selected method..."
                                      required>{{ old('payout_details', $user->payout_details) }}</textarea>
                            
                            <div class="mt-2 text-sm text-gray-500">
                                <div id="paypal-help" class="hidden">
                                    <strong>PayPal:</strong> Enter your PayPal email address.
                                </div>
                                <div id="bank-help" class="hidden">
                                    <strong>Bank Transfer:</strong> Provide your bank name, routing number, account number, and account holder name.
                                </div>
                                <div id="check-help" class="hidden">
                                    <strong>Check:</strong> Enter your full mailing address where checks should be sent.
                                </div>
                            </div>
                            
                            @error('payout_details')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-volume-primary hover:bg-volume-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-volume-primary">
                                Save Settings
                            </button>
                        </div>
                    </form>

                    <!-- Payout Schedule Info -->
                    <div class="mt-8 p-4 bg-gray-50 rounded-md">
                        <h4 class="text-sm font-medium text-gray-900 mb-2">Payout Schedule</h4>
                        <ul class="text-sm text-gray-600 space-y-1">
                            <li>• Commissions are paid monthly on the 15th</li>
                            <li>• Minimum payout threshold: $100</li>
                            <li>• Processing time: 3-5 business days</li>
                            <li>• You'll receive an email confirmation when payment is sent</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Show/hide help text based on selected payout method
        document.addEventListener('DOMContentLoaded', function() {
            const payoutMethods = document.querySelectorAll('input[name="payout_method"]');
            const helpTexts = {
                'paypal': document.getElementById('paypal-help'),
                'bank_transfer': document.getElementById('bank-help'),
                'check': document.getElementById('check-help')
            };

            function updateHelpText() {
                // Hide all help texts
                Object.values(helpTexts).forEach(help => help.classList.add('hidden'));
                
                // Show relevant help text
                const selectedMethod = document.querySelector('input[name="payout_method"]:checked');
                if (selectedMethod && helpTexts[selectedMethod.value]) {
                    helpTexts[selectedMethod.value].classList.remove('hidden');
                }
            }

            // Update help text on page load
            updateHelpText();

            // Update help text when selection changes
            payoutMethods.forEach(method => {
                method.addEventListener('change', updateHelpText);
            });
        });
    </script>
</x-partner-layout>
