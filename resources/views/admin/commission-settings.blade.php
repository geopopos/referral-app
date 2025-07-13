<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-poppins font-bold text-xl text-volume-dark leading-tight">
            {{ __('Commission Settings') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success Message -->
            @if (session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            <!-- Current Active Setting -->
            @if($activeSetting)
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-100 mb-6">
                    <div class="p-6">
                        <h3 class="font-poppins text-lg font-bold text-volume-dark mb-4">Current Active Commission Structure</h3>
                        <div class="bg-green-50 border border-green-200 rounded-xl p-6">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <span class="text-sm font-medium text-volume-gray">Commission Rate</span>
                                    <p class="text-2xl font-bold text-green-600">{{ $activeSetting->commission_percentage }}%</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-volume-gray">Quick Close Bonus</span>
                                    <p class="text-2xl font-bold text-green-600">${{ number_format($activeSetting->quick_close_bonus, 2) }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-volume-gray">Quick Close Days</span>
                                    <p class="text-2xl font-bold text-green-600">{{ $activeSetting->quick_close_days }} days</p>
                                </div>
                            </div>
                            @if($activeSetting->description)
                                <p class="mt-4 text-sm text-volume-gray bg-white p-3 rounded-lg">{{ $activeSetting->description }}</p>
                            @endif
                            <p class="mt-4 text-xs text-volume-gray">
                                Active since {{ $activeSetting->updated_at->format('M j, Y g:i A') }}
                                @if($activeSetting->creator)
                                    by {{ $activeSetting->creator->name }}
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-100 mb-6">
                    <div class="p-6">
                        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                                <p class="text-yellow-800 font-medium">No active commission structure found. Please create and activate one below.</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Create New Commission Setting -->
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-100 mb-6">
                <div class="p-6">
                    <h3 class="font-poppins text-lg font-bold text-volume-dark mb-6">Create New Commission Structure</h3>
                    
                    <form method="POST" action="{{ route('admin.commission-settings.store') }}">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="commission_percentage" class="block text-sm font-semibold text-volume-dark mb-2">Commission Percentage (%)</label>
                                <input type="number" 
                                       name="commission_percentage" 
                                       id="commission_percentage" 
                                       step="0.01" 
                                       min="0" 
                                       max="100" 
                                       value="{{ old('commission_percentage', 10) }}"
                                       class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg focus:border-volume-primary focus:ring-volume-primary focus:ring-2 focus:ring-opacity-50 transition-colors"
                                       required>
                                @error('commission_percentage')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="quick_close_bonus" class="block text-sm font-semibold text-volume-dark mb-2">Quick Close Bonus ($)</label>
                                <input type="number" 
                                       name="quick_close_bonus" 
                                       id="quick_close_bonus" 
                                       step="0.01" 
                                       min="0" 
                                       value="{{ old('quick_close_bonus', 250) }}"
                                       class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg focus:border-volume-primary focus:ring-volume-primary focus:ring-2 focus:ring-opacity-50 transition-colors"
                                       required>
                                @error('quick_close_bonus')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="quick_close_days" class="block text-sm font-semibold text-volume-dark mb-2">Quick Close Days</label>
                                <input type="number" 
                                       name="quick_close_days" 
                                       id="quick_close_days" 
                                       min="1" 
                                       max="365" 
                                       value="{{ old('quick_close_days', 7) }}"
                                       class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg focus:border-volume-primary focus:ring-volume-primary focus:ring-2 focus:ring-opacity-50 transition-colors"
                                       required>
                                @error('quick_close_days')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-2 text-sm text-volume-gray">Deals closed within this many days get the quick close bonus</p>
                            </div>

                            <div class="flex items-center">
                                <label class="flex items-center">
                                    <input type="checkbox" 
                                           name="activate_immediately" 
                                           value="1"
                                           class="rounded border-gray-300 text-volume-primary shadow-sm focus:border-volume-primary focus:ring-volume-primary">
                                    <span class="ml-2 text-sm font-medium text-volume-dark">Activate immediately</span>
                                </label>
                            </div>
                        </div>

                        <div class="mt-6">
                            <label for="description" class="block text-sm font-semibold text-volume-dark mb-2">Description (Optional)</label>
                            <textarea name="description" 
                                      id="description" 
                                      rows="3" 
                                      class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg focus:border-volume-primary focus:ring-volume-primary focus:ring-2 focus:ring-opacity-50 transition-colors"
                                      placeholder="Describe this commission structure...">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-8">
                            <button type="submit" 
                                    class="bg-volume-primary hover:bg-volume-secondary text-white font-semibold py-3 px-6 rounded-lg transition-colors shadow-lg">
                                Create Commission Structure
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Commission Settings History -->
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-100">
                <div class="p-6">
                    <h3 class="font-poppins text-lg font-bold text-volume-dark mb-6">Commission Settings History</h3>
                    
                    @if($settings->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-volume-light">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-volume-dark uppercase tracking-wider">Status</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-volume-dark uppercase tracking-wider">Commission %</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-volume-dark uppercase tracking-wider">Quick Close Bonus</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-volume-dark uppercase tracking-wider">Quick Close Days</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-volume-dark uppercase tracking-wider">Created</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-volume-dark uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @foreach($settings as $setting)
                                        <tr class="{{ $setting->is_active ? 'bg-green-50' : 'hover:bg-volume-light' }} transition-colors">
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                @if($setting->is_active)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-800">
                                                        Active
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-gray-100 text-gray-800">
                                                        Inactive
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm font-semibold text-volume-dark">
                                                {{ $setting->commission_percentage }}%
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm font-semibold text-volume-dark">
                                                ${{ number_format($setting->quick_close_bonus, 2) }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-volume-gray">
                                                {{ $setting->quick_close_days }} days
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-volume-gray">
                                                {{ $setting->created_at->format('M j, Y') }}
                                                @if($setting->creator)
                                                    <br><span class="text-xs">by {{ $setting->creator->name }}</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                                                @if(!$setting->is_active)
                                                    <form method="POST" action="{{ route('admin.commission-settings.activate', $setting) }}" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" 
                                                                class="text-volume-primary hover:text-volume-secondary font-semibold transition-colors"
                                                                onclick="return confirm('Are you sure you want to activate this commission structure? This will deactivate the current one.')">
                                                            Activate
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                        @if($setting->description)
                                            <tr class="{{ $setting->is_active ? 'bg-green-50' : '' }}">
                                                <td colspan="6" class="px-4 py-3 text-sm text-volume-gray">
                                                    <strong class="text-volume-dark">Description:</strong> {{ $setting->description }}
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6">
                            {{ $settings->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-volume-gray" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                            <h3 class="mt-4 text-lg font-semibold text-volume-dark">No commission settings found</h3>
                            <p class="mt-2 text-sm text-volume-gray">Create your first commission structure above.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
