<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-poppins font-bold text-xl text-volume-dark leading-tight">
            {{ __('Webhook Management') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Success/Error Messages -->
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            @if (session('info'))
                <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('info') }}</span>
                </div>
            @endif

            <!-- Webhook Statistics -->
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-100">
                <div class="p-6">
                    <h3 class="font-poppins text-lg font-bold text-volume-dark mb-4">Webhook Statistics (Last 30 Days)</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-blue-600">{{ $stats['total_deliveries'] }}</div>
                            <div class="text-sm text-blue-800">Total Deliveries</div>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-green-600">{{ $stats['successful_deliveries'] }}</div>
                            <div class="text-sm text-green-800">Successful</div>
                        </div>
                        <div class="bg-red-50 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-red-600">{{ $stats['failed_deliveries'] }}</div>
                            <div class="text-sm text-red-800">Failed</div>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-purple-600">{{ number_format($stats['success_rate'], 1) }}%</div>
                            <div class="text-sm text-purple-800">Success Rate</div>
                        </div>
                    </div>
                    @if($stats['average_response_time'] > 0)
                        <div class="mt-4 text-sm text-gray-600">
                            Average Response Time: {{ number_format($stats['average_response_time'] * 1000, 0) }}ms
                        </div>
                    @endif
                </div>
            </div>

            <!-- Webhook Settings -->
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-100">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-poppins text-lg font-bold text-volume-dark">Webhook Settings</h3>
                        <div class="flex space-x-2">
                            <form action="{{ route('admin.webhooks.test') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors">
                                    Test Webhook
                                </button>
                            </form>
                            @if($stats['failed_deliveries'] > 0)
                                <form action="{{ route('admin.webhooks.retry-failed') }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors">
                                        Retry Failed ({{ $stats['failed_deliveries'] }})
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <form action="{{ route('admin.webhooks.settings.update') }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <!-- Basic Settings -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="url" class="block text-sm font-medium text-gray-700 mb-2">Webhook URL</label>
                                <input type="url" 
                                       name="url" 
                                       id="url" 
                                       value="{{ old('url', $settings->url) }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-volume-primary focus:border-volume-primary"
                                       placeholder="https://your-app.com/webhooks/volume-up"
                                       required>
                                @error('url')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="auth_type" class="block text-sm font-medium text-gray-700 mb-2">Authentication Type</label>
                                <select name="auth_type" 
                                        id="auth_type" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-volume-primary focus:border-volume-primary"
                                        onchange="toggleAuthFields()">
                                    @foreach($authTypes as $value => $label)
                                        <option value="{{ $value }}" {{ old('auth_type', $settings->auth_type) === $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Authentication Credentials -->
                        <div id="auth-fields" class="space-y-4">
                            <!-- Bearer Token -->
                            <div id="bearer-fields" class="hidden">
                                <label for="bearer_token" class="block text-sm font-medium text-gray-700 mb-2">Bearer Token</label>
                                <input type="text" 
                                       name="auth_credentials[token]" 
                                       id="bearer_token"
                                       value="{{ old('auth_credentials.token', $settings->auth_credentials['token'] ?? '') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-volume-primary focus:border-volume-primary"
                                       placeholder="your-bearer-token">
                            </div>

                            <!-- Basic Auth -->
                            <div id="basic-fields" class="hidden grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="basic_username" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                                    <input type="text" 
                                           name="auth_credentials[username]" 
                                           id="basic_username"
                                           value="{{ old('auth_credentials.username', $settings->auth_credentials['username'] ?? '') }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-volume-primary focus:border-volume-primary">
                                </div>
                                <div>
                                    <label for="basic_password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                                    <input type="password" 
                                           name="auth_credentials[password]" 
                                           id="basic_password"
                                           value="{{ old('auth_credentials.password', $settings->auth_credentials['password'] ?? '') }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-volume-primary focus:border-volume-primary">
                                </div>
                            </div>

                            <!-- Custom Headers -->
                            <div id="custom-fields" class="hidden">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Custom Headers</label>
                                <div class="space-y-2" id="custom-headers">
                                    @if(old('auth_credentials') && is_array(old('auth_credentials')))
                                        @foreach(old('auth_credentials') as $key => $value)
                                            <div class="flex space-x-2">
                                                <input type="text" 
                                                       name="auth_credentials[{{ $key }}]" 
                                                       value="{{ $key }}"
                                                       placeholder="Header Name"
                                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-volume-primary focus:border-volume-primary">
                                                <input type="text" 
                                                       name="auth_credentials[{{ $key }}]" 
                                                       value="{{ $value }}"
                                                       placeholder="Header Value"
                                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-volume-primary focus:border-volume-primary">
                                                <button type="button" onclick="removeHeader(this)" class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">×</button>
                                            </div>
                                        @endforeach
                                    @elseif($settings->auth_type === 'custom' && is_array($settings->auth_credentials))
                                        @foreach($settings->auth_credentials as $key => $value)
                                            <div class="flex space-x-2">
                                                <input type="text" 
                                                       value="{{ $key }}"
                                                       placeholder="Header Name"
                                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-volume-primary focus:border-volume-primary header-name">
                                                <input type="text" 
                                                       name="auth_credentials[{{ $key }}]" 
                                                       value="{{ $value }}"
                                                       placeholder="Header Value"
                                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-volume-primary focus:border-volume-primary">
                                                <button type="button" onclick="removeHeader(this)" class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">×</button>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <button type="button" onclick="addHeader()" class="mt-2 px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 text-sm">
                                    Add Header
                                </button>
                            </div>
                        </div>

                        <!-- Events -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Enabled Events</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                @foreach($availableEvents as $event => $label)
                                    <label class="flex items-center space-x-2">
                                        <input type="checkbox" 
                                               name="enabled_events[]" 
                                               value="{{ $event }}"
                                               {{ in_array($event, old('enabled_events', $settings->enabled_events)) ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-volume-primary focus:ring-volume-primary">
                                        <span class="text-sm text-gray-700">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Advanced Settings -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="max_retry_attempts" class="block text-sm font-medium text-gray-700 mb-2">Max Retry Attempts</label>
                                <input type="number" 
                                       name="max_retry_attempts" 
                                       id="max_retry_attempts"
                                       value="{{ old('max_retry_attempts', $settings->max_retry_attempts) }}"
                                       min="0" 
                                       max="10"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-volume-primary focus:border-volume-primary">
                            </div>

                            <div>
                                <label for="secret_key" class="block text-sm font-medium text-gray-700 mb-2">Secret Key (for HMAC)</label>
                                <input type="text" 
                                       name="secret_key" 
                                       id="secret_key"
                                       value="{{ old('secret_key', $settings->secret_key) }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-volume-primary focus:border-volume-primary"
                                       placeholder="Optional webhook secret">
                            </div>

                            <div class="flex items-end">
                                <label class="flex items-center space-x-2">
                                    <input type="checkbox" 
                                           name="is_active" 
                                           value="1"
                                           {{ old('is_active', $settings->is_active) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-volume-primary focus:ring-volume-primary">
                                    <span class="text-sm font-medium text-gray-700">Enable Webhooks</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-volume-primary hover:bg-volume-secondary text-white px-6 py-2 rounded-lg font-semibold transition-colors">
                                Save Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Webhook Logs -->
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-100">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-poppins text-lg font-bold text-volume-dark">Recent Webhook Logs</h3>
                        <form action="{{ route('admin.webhooks.logs.cleanup') }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="days" value="90">
                            <button type="submit" 
                                    onclick="return confirm('Delete webhook logs older than 90 days?')"
                                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors">
                                Cleanup Old Logs
                            </button>
                        </form>
                    </div>

                    @if($logs->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-volume-light">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-volume-dark uppercase tracking-wider">Event</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-volume-dark uppercase tracking-wider">Status</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-volume-dark uppercase tracking-wider">HTTP Status</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-volume-dark uppercase tracking-wider">Attempts</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-volume-dark uppercase tracking-wider">Response Time</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-volume-dark uppercase tracking-wider">Created</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-volume-dark uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @foreach($logs as $log)
                                        <tr class="hover:bg-volume-light transition-colors">
                                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-volume-dark">
                                                {{ $log->event_type }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <span class="inline-flex px-2 py-1 text-xs font-bold rounded-full 
                                                    @if($log->status === 'success') bg-green-100 text-green-800
                                                    @elseif($log->status === 'failed') bg-red-100 text-red-800
                                                    @elseif($log->status === 'retrying') bg-yellow-100 text-yellow-800
                                                    @else bg-gray-100 text-gray-800 @endif">
                                                    {{ ucfirst($log->status) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-volume-gray">
                                                {{ $log->http_status ?? 'N/A' }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-volume-gray">
                                                {{ $log->attempt_number }}/{{ $log->max_attempts }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-volume-gray">
                                                @if($log->response_time)
                                                    {{ number_format($log->response_time * 1000, 0) }}ms
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-volume-gray">
                                                {{ $log->created_at->format('M j, Y H:i') }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm">
                                                @if($log->status === 'failed' && $log->attempt_number < $log->max_attempts)
                                                    <button onclick="retryWebhook({{ $log->id }})" 
                                                            class="text-orange-600 hover:text-orange-800 font-medium">
                                                        Retry
                                                    </button>
                                                @endif
                                                <button onclick="showLogDetails({{ $log->id }})" 
                                                        class="text-blue-600 hover:text-blue-800 font-medium ml-2">
                                                    Details
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $logs->links() }}
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-10 w-10 text-volume-gray" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="mt-3 text-base font-semibold text-volume-dark">No webhook logs yet</h3>
                            <p class="mt-1 text-sm text-volume-gray">Webhook logs will appear here once webhooks are sent.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleAuthFields() {
            const authType = document.getElementById('auth_type').value;
            const bearerFields = document.getElementById('bearer-fields');
            const basicFields = document.getElementById('basic-fields');
            const customFields = document.getElementById('custom-fields');

            // Hide all fields first
            bearerFields.classList.add('hidden');
            basicFields.classList.add('hidden');
            customFields.classList.add('hidden');

            // Show relevant fields
            if (authType === 'bearer') {
                bearerFields.classList.remove('hidden');
            } else if (authType === 'basic') {
                basicFields.classList.remove('hidden');
            } else if (authType === 'custom') {
                customFields.classList.remove('hidden');
            }
        }

        function addHeader() {
            const container = document.getElementById('custom-headers');
            const div = document.createElement('div');
            div.className = 'flex space-x-2';
            div.innerHTML = `
                <input type="text" 
                       placeholder="Header Name"
                       class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-volume-primary focus:border-volume-primary header-name">
                <input type="text" 
                       placeholder="Header Value"
                       class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-volume-primary focus:border-volume-primary">
                <button type="button" onclick="removeHeader(this)" class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">×</button>
            `;
            container.appendChild(div);
        }

        function removeHeader(button) {
            button.parentElement.remove();
        }

        function retryWebhook(logId) {
            if (confirm('Retry this webhook?')) {
                fetch(`/admin/webhooks/logs/${logId}/retry`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Failed to retry webhook');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to retry webhook');
                });
            }
        }

        function showLogDetails(logId) {
            // This would open a modal or navigate to a details page
            // For now, we'll just show an alert
            alert('Log details functionality would be implemented here');
        }

        // Initialize auth fields on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleAuthFields();
            
            // Update custom header names when typing
            document.addEventListener('input', function(e) {
                if (e.target.classList.contains('header-name')) {
                    const valueInput = e.target.nextElementSibling;
                    const oldName = valueInput.name.match(/\[(.*?)\]/)[1];
                    const newName = e.target.value;
                    valueInput.name = valueInput.name.replace(`[${oldName}]`, `[${newName}]`);
                }
            });
        });
    </script>
</x-admin-layout>
