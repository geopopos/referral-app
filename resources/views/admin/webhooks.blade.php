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

            <!-- Overall Webhook Statistics -->
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-100">
                <div class="p-6">
                    <h3 class="font-poppins text-lg font-bold text-volume-dark mb-4">Overall Webhook Statistics (Last 30 Days)</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-blue-600">{{ $overallStats['total_deliveries'] }}</div>
                            <div class="text-sm text-blue-800">Total Deliveries</div>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-green-600">{{ $overallStats['successful_deliveries'] }}</div>
                            <div class="text-sm text-green-800">Successful</div>
                        </div>
                        <div class="bg-red-50 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-red-600">{{ $overallStats['failed_deliveries'] }}</div>
                            <div class="text-sm text-red-800">Failed</div>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-purple-600">{{ $overallStats['active_configurations'] }}/{{ $overallStats['total_configurations'] }}</div>
                            <div class="text-sm text-purple-800">Active Webhooks</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Webhook Configurations -->
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-100">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-poppins text-lg font-bold text-volume-dark">Webhook Configurations</h3>
                        <button onclick="showCreateModal()" class="bg-volume-primary hover:bg-volume-secondary text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors">
                            Add New Webhook
                        </button>
                    </div>

                    @if($webhookSettings->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-volume-light">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-volume-dark uppercase tracking-wider">Name</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-volume-dark uppercase tracking-wider">URL</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-volume-dark uppercase tracking-wider">Events</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-volume-dark uppercase tracking-wider">Priority</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-volume-dark uppercase tracking-wider">Status</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-volume-dark uppercase tracking-wider">Statistics</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-volume-dark uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @foreach($webhookSettings as $webhook)
                                        <tr class="hover:bg-volume-light transition-colors">
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <div class="text-sm font-medium text-volume-dark">{{ $webhook->name }}</div>
                                                @if($webhook->description)
                                                    <div class="text-xs text-volume-gray">{{ Str::limit($webhook->description, 50) }}</div>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <div class="text-sm text-volume-gray">{{ Str::limit($webhook->url, 40) }}</div>
                                                <div class="text-xs text-volume-gray">{{ ucfirst($webhook->auth_type) }} auth</div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="flex flex-wrap gap-1">
                                                    @foreach($webhook->enabled_events as $event)
                                                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                                            {{ str_replace(['lead.', 'commission.'], ['L.', 'C.'], $event) }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-volume-gray">
                                                {{ $webhook->priority }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <span class="inline-flex px-2 py-1 text-xs font-bold rounded-full 
                                                    @if($webhook->is_active) bg-green-100 text-green-800
                                                    @else bg-red-100 text-red-800 @endif">
                                                    {{ $webhook->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-volume-gray">
                                                @if(isset($webhookStats[$webhook->id]))
                                                    <div>{{ $webhookStats[$webhook->id]['total_deliveries'] }} total</div>
                                                    <div>{{ number_format($webhookStats[$webhook->id]['success_rate'], 1) }}% success</div>
                                                @else
                                                    <div>No data</div>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm space-x-2">
                                                <button onclick="editWebhook({{ $webhook->id }})" 
                                                        class="text-blue-600 hover:text-blue-800 font-medium">
                                                    Edit
                                                </button>
                                                <form action="{{ route('admin.webhooks.toggle', $webhook) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" 
                                                            class="text-orange-600 hover:text-orange-800 font-medium">
                                                        {{ $webhook->is_active ? 'Disable' : 'Enable' }}
                                                    </button>
                                                </form>
                                                <button onclick="deleteWebhook({{ $webhook->id }}, '{{ $webhook->name }}')" 
                                                        class="text-red-600 hover:text-red-800 font-medium">
                                                    Delete
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-10 w-10 text-volume-gray" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            <h3 class="mt-3 text-base font-semibold text-volume-dark">No webhook configurations yet</h3>
                            <p class="mt-1 text-sm text-volume-gray">Get started by creating your first webhook configuration.</p>
                            <button onclick="showCreateModal()" class="mt-4 bg-volume-primary hover:bg-volume-secondary text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors">
                                Create First Webhook
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-100">
                <div class="p-6">
                    <h3 class="font-poppins text-lg font-bold text-volume-dark mb-4">Quick Actions</h3>
                    <div class="flex flex-wrap gap-3">
                        <form action="{{ route('admin.webhooks.test') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors">
                                Test Active Webhooks
                            </button>
                        </form>
                        @if($overallStats['failed_deliveries'] > 0)
                            <form action="{{ route('admin.webhooks.retry-failed') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors">
                                    Retry Failed ({{ $overallStats['failed_deliveries'] }})
                                </button>
                            </form>
                        @endif
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
                </div>
            </div>

            <!-- Recent Webhook Logs -->
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-100">
                <div class="p-6">
                    <h3 class="font-poppins text-lg font-bold text-volume-dark mb-4">Recent Webhook Logs</h3>

                    @if($logs->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-volume-light">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-volume-dark uppercase tracking-wider">Webhook</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-volume-dark uppercase tracking-wider">Event</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-volume-dark uppercase tracking-wider">Status</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-volume-dark uppercase tracking-wider">HTTP Status</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-volume-dark uppercase tracking-wider">Attempts</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-volume-dark uppercase tracking-wider">Response Time</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-volume-dark uppercase tracking-wider">Created</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @foreach($logs as $log)
                                        <tr class="hover:bg-volume-light transition-colors">
                                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-volume-dark">
                                                {{ $log->webhookSetting ? $log->webhookSetting->name : 'Unknown' }}
                                            </td>
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
                                                {{ $log->created_at->format('M j, H:i') }}
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

    <!-- Create/Edit Webhook Modal -->
    <div id="webhookModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-bold text-gray-900 mb-4" id="modalTitle">Create New Webhook</h3>
                <form id="webhookForm" method="POST">
                    @csrf
                    <div id="methodField"></div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                            <input type="text" name="name" id="name" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-volume-primary focus:border-volume-primary">
                        </div>
                        <div>
                            <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                            <input type="number" name="priority" id="priority" min="0" value="0" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-volume-primary focus:border-volume-primary">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea name="description" id="description" rows="2"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-volume-primary focus:border-volume-primary"></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="url" class="block text-sm font-medium text-gray-700 mb-2">Webhook URL</label>
                            <input type="url" name="url" id="url" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-volume-primary focus:border-volume-primary">
                        </div>
                        <div>
                            <label for="auth_type" class="block text-sm font-medium text-gray-700 mb-2">Authentication</label>
                            <select name="auth_type" id="auth_type" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-volume-primary focus:border-volume-primary">
                                @foreach($authTypes as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Enabled Events</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                            @foreach($availableEvents as $event => $label)
                                <label class="flex items-center space-x-2">
                                    <input type="checkbox" name="enabled_events[]" value="{{ $event }}"
                                           class="rounded border-gray-300 text-volume-primary focus:ring-volume-primary">
                                    <span class="text-sm text-gray-700">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="max_retry_attempts" class="block text-sm font-medium text-gray-700 mb-2">Max Retry Attempts</label>
                            <input type="number" name="max_retry_attempts" id="max_retry_attempts" min="0" max="10" value="3"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-volume-primary focus:border-volume-primary">
                        </div>
                        <div>
                            <label for="secret_key" class="block text-sm font-medium text-gray-700 mb-2">Secret Key (Optional)</label>
                            <input type="text" name="secret_key" id="secret_key"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-volume-primary focus:border-volume-primary">
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="is_active" value="1" checked
                                   class="rounded border-gray-300 text-volume-primary focus:ring-volume-primary">
                            <span class="text-sm font-medium text-gray-700">Enable this webhook</span>
                        </label>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeModal()" 
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-volume-primary text-white rounded-lg hover:bg-volume-secondary transition-colors">
                            Save Webhook
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showCreateModal() {
            document.getElementById('modalTitle').textContent = 'Create New Webhook';
            document.getElementById('webhookForm').action = '{{ route("admin.webhooks.store") }}';
            document.getElementById('methodField').innerHTML = '';
            document.getElementById('webhookForm').reset();
            document.getElementById('webhookModal').classList.remove('hidden');
        }

        function editWebhook(id) {
            // Fetch webhook data and populate edit modal
            fetch(`/admin/webhooks/${id}/edit`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        populateEditModal(data.webhook);
                    } else {
                        alert('Error loading webhook data: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error loading webhook data. Please try again.');
                });
        }

        function populateEditModal(webhook) {
            // Change modal title and form action
            document.getElementById('modalTitle').textContent = 'Edit Webhook';
            document.getElementById('webhookForm').action = `/admin/webhooks/${webhook.id}`;
            document.getElementById('methodField').innerHTML = '@method("PATCH")';

            // Populate form fields
            document.getElementById('name').value = webhook.name || '';
            document.getElementById('description').value = webhook.description || '';
            document.getElementById('priority').value = webhook.priority || 0;
            document.getElementById('url').value = webhook.url || '';
            document.getElementById('auth_type').value = webhook.auth_type || 'none';
            document.getElementById('max_retry_attempts').value = webhook.max_retry_attempts || 3;
            document.getElementById('secret_key').value = webhook.secret_key || '';

            // Handle enabled events checkboxes
            const eventCheckboxes = document.querySelectorAll('input[name="enabled_events[]"]');
            eventCheckboxes.forEach(checkbox => {
                checkbox.checked = webhook.enabled_events && webhook.enabled_events.includes(checkbox.value);
            });

            // Handle is_active checkbox
            const isActiveCheckbox = document.querySelector('input[name="is_active"]');
            if (isActiveCheckbox) {
                isActiveCheckbox.checked = webhook.is_active;
            }

            // Show modal
            document.getElementById('webhookModal').classList.remove('hidden');
        }

        function deleteWebhook(id, name) {
            if (confirm(`Are you sure you want to delete the webhook "${name}"?\n\nThis action cannot be undone and will also delete all associated webhook logs.`)) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `{{ url('/admin/webhooks') }}/${id}`;
                form.innerHTML = `
                    @csrf
                    @method('DELETE')
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }

        function closeModal() {
            document.getElementById('webhookModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('webhookModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
</x-admin-layout>
