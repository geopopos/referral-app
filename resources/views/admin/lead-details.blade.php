<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Lead Details: {{ $lead->name }}
            </h2>
            <a href="{{ route('admin.leads') }}" class="text-indigo-600 hover:text-indigo-900">
                ← Back to Leads
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success/Error Messages -->
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Lead Information -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Lead Information</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Name</span>
                                    <p class="text-sm text-gray-900">{{ $lead->name }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Email</span>
                                    <p class="text-sm text-gray-900">{{ $lead->email }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Phone</span>
                                    <p class="text-sm text-gray-900">{{ $lead->phone }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Company</span>
                                    <p class="text-sm text-gray-900">{{ $lead->company }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">How They Heard About Us</span>
                                    <p class="text-sm text-gray-900">{{ $lead->how_heard_about_us ?: 'Not specified' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Referrer</span>
                                    <p class="text-sm text-gray-900">
                                        @if($lead->referrer)
                                            <a href="{{ route('admin.partners.show', $lead->referrer) }}" class="text-indigo-600 hover:text-indigo-900">
                                                {{ $lead->referrer->name }}
                                            </a>
                                            ({{ $lead->referral_code }})
                                        @else
                                            Direct Lead
                                        @endif
                                    </p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Created</span>
                                    <p class="text-sm text-gray-900">{{ $lead->created_at->format('M j, Y g:i A') }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Current Status</span>
                                    <p class="text-sm">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            @if($lead->status === 'sale') bg-green-100 text-green-800
                                            @elseif($lead->status === 'lost') bg-red-100 text-red-800
                                            @elseif($lead->status === 'offer_made') bg-yellow-100 text-yellow-800
                                            @else bg-blue-100 text-blue-800
                                            @endif">
                                            {{ $lead->status_display }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pipeline Management -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Pipeline Management</h3>
                            
                            <form method="POST" action="{{ route('admin.leads.update-status', $lead) }}">
                                @csrf
                                @method('PATCH')
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Status Selection -->
                                    <div class="md:col-span-2">
                                        <label for="status" class="block text-sm font-medium text-gray-700">Lead Status</label>
                                        <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                            <option value="lead" {{ $lead->status === 'lead' ? 'selected' : '' }}>New Lead</option>
                                            <option value="appointment_scheduled" {{ $lead->status === 'appointment_scheduled' ? 'selected' : '' }}>Appointment Scheduled</option>
                                            <option value="appointment_completed" {{ $lead->status === 'appointment_completed' ? 'selected' : '' }}>Appointment Completed</option>
                                            <option value="offer_made" {{ $lead->status === 'offer_made' ? 'selected' : '' }}>Offer Made</option>
                                            <option value="sale" {{ $lead->status === 'sale' ? 'selected' : '' }}>Sale Closed</option>
                                            <option value="lost" {{ $lead->status === 'lost' ? 'selected' : '' }}>Lost</option>
                                        </select>
                                    </div>

                                    <!-- Appointment Fields -->
                                    <div id="appointment-fields" class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4" style="display: none;">
                                        <div>
                                            <label for="appointment_scheduled_at" class="block text-sm font-medium text-gray-700">Appointment Date</label>
                                            <input type="datetime-local" 
                                                   name="appointment_scheduled_at" 
                                                   id="appointment_scheduled_at" 
                                                   value="{{ $lead->appointment_scheduled_at ? $lead->appointment_scheduled_at->format('Y-m-d\TH:i') : '' }}"
                                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        </div>
                                        <div>
                                            <label for="appointment_completed_at" class="block text-sm font-medium text-gray-700">Appointment Completed</label>
                                            <input type="datetime-local" 
                                                   name="appointment_completed_at" 
                                                   id="appointment_completed_at" 
                                                   value="{{ $lead->appointment_completed_at ? $lead->appointment_completed_at->format('Y-m-d\TH:i') : '' }}"
                                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        </div>
                                        <div class="md:col-span-2">
                                            <label for="appointment_notes" class="block text-sm font-medium text-gray-700">Appointment Notes</label>
                                            <textarea name="appointment_notes" 
                                                      id="appointment_notes" 
                                                      rows="3" 
                                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                      placeholder="Notes about the appointment...">{{ $lead->appointment_notes }}</textarea>
                                        </div>
                                    </div>

                                    <!-- Offer Fields -->
                                    <div id="offer-fields" class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4" style="display: none;">
                                        <div>
                                            <label for="offer_amount" class="block text-sm font-medium text-gray-700">Offer Amount ($)</label>
                                            <input type="number" 
                                                   name="offer_amount" 
                                                   id="offer_amount" 
                                                   step="0.01" 
                                                   min="0" 
                                                   value="{{ $lead->offer_amount }}"
                                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        </div>
                                        <div>
                                            <label for="offer_made_at" class="block text-sm font-medium text-gray-700">Offer Date</label>
                                            <input type="datetime-local" 
                                                   name="offer_made_at" 
                                                   id="offer_made_at" 
                                                   value="{{ $lead->offer_made_at ? $lead->offer_made_at->format('Y-m-d\TH:i') : '' }}"
                                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        </div>
                                        <div class="md:col-span-2">
                                            <label for="offer_notes" class="block text-sm font-medium text-gray-700">Offer Notes</label>
                                            <textarea name="offer_notes" 
                                                      id="offer_notes" 
                                                      rows="3" 
                                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                      placeholder="Details about the offer...">{{ $lead->offer_notes }}</textarea>
                                        </div>
                                    </div>

                                    <!-- Sale Fields -->
                                    <div id="sale-fields" class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4" style="display: none;">
                                        <div>
                                            <label for="sale_amount" class="block text-sm font-medium text-gray-700">Sale Amount ($)</label>
                                            <input type="number" 
                                                   name="sale_amount" 
                                                   id="sale_amount" 
                                                   step="0.01" 
                                                   min="0" 
                                                   value="{{ $lead->sale_amount }}"
                                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        </div>
                                        <div>
                                            <label for="sale_closed_at" class="block text-sm font-medium text-gray-700">Sale Closed Date</label>
                                            <input type="datetime-local" 
                                                   name="sale_closed_at" 
                                                   id="sale_closed_at" 
                                                   value="{{ $lead->sale_closed_at ? $lead->sale_closed_at->format('Y-m-d\TH:i') : '' }}"
                                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        </div>
                                        <div class="md:col-span-2">
                                            <label for="sale_notes" class="block text-sm font-medium text-gray-700">Sale Notes</label>
                                            <textarea name="sale_notes" 
                                                      id="sale_notes" 
                                                      rows="3" 
                                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                      placeholder="Notes about the sale...">{{ $lead->sale_notes }}</textarea>
                                        </div>
                                    </div>

                                    <!-- Lost Fields -->
                                    <div id="lost-fields" class="md:col-span-2" style="display: none;">
                                        <label for="lost_reason" class="block text-sm font-medium text-gray-700">Reason for Loss</label>
                                        <textarea name="lost_reason" 
                                                  id="lost_reason" 
                                                  rows="3" 
                                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                  placeholder="Why was this lead lost?">{{ $lead->lost_reason }}</textarea>
                                    </div>
                                </div>

                                <div class="mt-6">
                                    <button type="submit" 
                                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        Update Lead
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <!-- Commission Information -->
                    @if($lead->referrer && $commissionSetting)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                            <div class="p-6 bg-white border-b border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Commission Information</h3>
                                
                                <div class="space-y-3">
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Current Rate</span>
                                        <p class="text-sm text-gray-900">{{ $commissionSetting->commission_percentage }}%</p>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Quick Close Bonus</span>
                                        <p class="text-sm text-gray-900">${{ number_format($commissionSetting->quick_close_bonus, 2) }}</p>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Quick Close Window</span>
                                        <p class="text-sm text-gray-900">{{ $commissionSetting->quick_close_days }} days</p>
                                    </div>
                                    @if($lead->status === 'sale' && $lead->sale_amount)
                                        <div class="pt-3 border-t">
                                            <span class="text-sm font-medium text-gray-500">Estimated Commission</span>
                                            <p class="text-lg font-semibold text-green-600">
                                                ${{ number_format($commissionSetting->calculateCommission($lead->sale_amount, $lead->isQuickClose()), 2) }}
                                                @if($lead->isQuickClose())
                                                    <span class="text-xs text-green-500">(includes quick close bonus)</span>
                                                @endif
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Commission Override -->
                    @if($lead->referrer)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                            <div class="p-6 bg-white border-b border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Commission Override</h3>
                                
                                <form method="POST" action="{{ route('admin.leads.update-status', $lead) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="{{ $lead->status }}">
                                    
                                    <div class="space-y-4">
                                        <div>
                                            <label for="commission_override_percentage" class="block text-sm font-medium text-gray-700">Override Percentage (%)</label>
                                            <input type="number" 
                                                   name="commission_override_percentage" 
                                                   id="commission_override_percentage" 
                                                   step="0.01" 
                                                   min="0" 
                                                   max="100" 
                                                   value="{{ $lead->commission_override_percentage }}"
                                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        </div>
                                        
                                        <div>
                                            <label for="commission_override_amount" class="block text-sm font-medium text-gray-700">Override Amount ($)</label>
                                            <input type="number" 
                                                   name="commission_override_amount" 
                                                   id="commission_override_amount" 
                                                   step="0.01" 
                                                   min="0" 
                                                   value="{{ $lead->commission_override_amount }}"
                                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        </div>
                                        
                                        <div>
                                            <label for="commission_override_reason" class="block text-sm font-medium text-gray-700">Override Reason</label>
                                            <textarea name="commission_override_reason" 
                                                      id="commission_override_reason" 
                                                      rows="3" 
                                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                      placeholder="Why is this commission being overridden?">{{ $lead->commission_override_reason }}</textarea>
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        <button type="submit" 
                                                class="w-full inline-flex justify-center items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 focus:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            Update Commission Override
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif

                    <!-- Existing Commissions -->
                    @if($lead->commissions->count() > 0)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 bg-white border-b border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Generated Commissions</h3>
                                
                                @foreach($lead->commissions as $commission)
                                    <div class="border rounded-lg p-3 mb-3 last:mb-0">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <p class="font-medium text-gray-900">${{ number_format($commission->amount, 2) }}</p>
                                                <p class="text-sm text-gray-500">{{ ucfirst($commission->type) }}</p>
                                            </div>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                @if($commission->status === 'paid') bg-green-100 text-green-800
                                                @elseif($commission->status === 'approved') bg-blue-100 text-blue-800
                                                @else bg-yellow-100 text-yellow-800
                                                @endif">
                                                {{ ucfirst($commission->status) }}
                                            </span>
                                        </div>
                                        <p class="text-xs text-gray-400 mt-1">{{ $commission->created_at->format('M j, Y') }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const statusSelect = document.getElementById('status');
            const appointmentFields = document.getElementById('appointment-fields');
            const offerFields = document.getElementById('offer-fields');
            const saleFields = document.getElementById('sale-fields');
            const lostFields = document.getElementById('lost-fields');

            function toggleFields() {
                const status = statusSelect.value;
                
                // Hide all fields first
                appointmentFields.style.display = 'none';
                offerFields.style.display = 'none';
                saleFields.style.display = 'none';
                lostFields.style.display = 'none';

                // Show relevant fields based on status
                if (status === 'appointment_scheduled' || status === 'appointment_completed') {
                    appointmentFields.style.display = 'block';
                }
                if (status === 'offer_made' || status === 'sale') {
                    offerFields.style.display = 'block';
                }
                if (status === 'sale') {
                    saleFields.style.display = 'block';
                }
                if (status === 'lost') {
                    lostFields.style.display = 'block';
                }
            }

            statusSelect.addEventListener('change', toggleFields);
            toggleFields(); // Initial call
        });
    </script>
</x-admin-layout>
