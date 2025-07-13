<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manage Commissions') }}
            </h2>
            <div class="flex space-x-4">
                <a href="{{ route('admin.dashboard') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back to Dashboard
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 overflow-hidden">
                    @if(session('success'))
                        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    <h3 class="text-lg font-medium text-gray-900 mb-4">All Commissions ({{ $commissions->total() }})</h3>
                    
                    @if($commissions->count() > 0)
                        <div class="w-full overflow-x-auto border border-gray-200 rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200 table-fixed">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="w-1/6 px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Partner</th>
                                        <th class="w-1/6 px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lead</th>
                                        <th class="w-1/12 px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                        <th class="w-1/12 px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                        <th class="w-1/12 px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="w-1/12 px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="w-1/4 px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($commissions as $commission)
                                        <tr>
                                            <td class="px-3 py-3">
                                                <div class="truncate">
                                                    <div class="text-sm font-medium text-gray-900 truncate">{{ $commission->user->name }}</div>
                                                    <div class="text-xs text-gray-500 truncate">{{ $commission->user->email }}</div>
                                                    @if($commission->user->payout_method)
                                                        <div class="text-xs text-gray-400 truncate">{{ ucfirst($commission->user->payout_method) }}</div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-3 py-3">
                                                <div class="truncate">
                                                    <div class="text-sm font-medium text-gray-900 truncate">{{ $commission->lead->name }}</div>
                                                    <div class="text-xs text-gray-500 truncate">{{ $commission->lead->company }}</div>
                                                </div>
                                            </td>
                                            <td class="px-3 py-3">
                                                <span class="inline-flex px-1 py-0.5 text-xs font-semibold rounded-full w-full justify-center
                                                    @if($commission->type === 'rev_share') bg-blue-100 text-blue-800
                                                    @else bg-purple-100 text-purple-800 @endif">
                                                    {{ $commission->type === 'rev_share' ? 'Rev' : 'Bonus' }}
                                                </span>
                                            </td>
                                            <td class="px-3 py-3 text-sm font-medium text-gray-900">
                                                <div class="truncate">${{ number_format($commission->amount, 0) }}</div>
                                            </td>
                                            <td class="px-3 py-3">
                                                <form action="{{ route('admin.commissions.update-status', $commission) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <select 
                                                        name="status" 
                                                        onchange="this.form.submit()"
                                                        class="text-xs rounded-full px-1 py-0.5 font-semibold w-full border-0 cursor-pointer
                                                            @if($commission->status === 'pending') bg-yellow-100 text-yellow-800
                                                            @elseif($commission->status === 'approved') bg-blue-100 text-blue-800
                                                            @else bg-green-100 text-green-800 @endif"
                                                    >
                                                        <option value="pending" {{ $commission->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                        <option value="approved" {{ $commission->status === 'approved' ? 'selected' : '' }}>Approved</option>
                                                        <option value="paid" {{ $commission->status === 'paid' ? 'selected' : '' }}>Paid</option>
                                                    </select>
                                                </form>
                                            </td>
                                            <td class="px-3 py-3">
                                                <div class="text-xs text-gray-500">
                                                    <div class="font-medium">{{ $commission->created_at->format('M j') }}</div>
                                                    @if($commission->paid_at)
                                                        <div class="text-xs text-green-600">Paid: {{ $commission->paid_at->format('M j') }}</div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-3 py-3">
                                                <div class="flex flex-col space-y-1">
                                                    <a href="{{ route('admin.partners.show', $commission->user) }}" class="text-blue-600 hover:text-blue-900 text-xs">
                                                        Partner
                                                    </a>
                                                    @if($commission->status === 'pending')
                                                        <form action="{{ route('admin.commissions.update-status', $commission) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="status" value="approved">
                                                            <button type="submit" class="text-green-600 hover:text-green-900 text-xs text-left">
                                                                Approve
                                                            </button>
                                                        </form>
                                                    @endif
                                                    @if($commission->status === 'approved')
                                                        <form action="{{ route('admin.commissions.update-status', $commission) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="status" value="paid">
                                                            <button type="submit" class="text-purple-600 hover:text-purple-900 text-xs text-left">
                                                                Mark Paid
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $commissions->links() }}
                        </div>

                        <!-- Summary Stats -->
                        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="bg-yellow-50 p-4 rounded-lg">
                                <h4 class="text-sm font-medium text-yellow-800">Pending Commissions</h4>
                                <p class="text-2xl font-bold text-yellow-900">
                                    ${{ number_format($commissions->where('status', 'pending')->sum('amount'), 2) }}
                                </p>
                            </div>
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <h4 class="text-sm font-medium text-blue-800">Approved Commissions</h4>
                                <p class="text-2xl font-bold text-blue-900">
                                    ${{ number_format($commissions->where('status', 'approved')->sum('amount'), 2) }}
                                </p>
                            </div>
                            <div class="bg-green-50 p-4 rounded-lg">
                                <h4 class="text-sm font-medium text-green-800">Paid Commissions</h4>
                                <p class="text-2xl font-bold text-green-900">
                                    ${{ number_format($commissions->where('status', 'paid')->sum('amount'), 2) }}
                                </p>
                            </div>
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-8">No commissions found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
