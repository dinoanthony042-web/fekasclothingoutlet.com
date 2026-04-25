@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Customer Report</h1>

    @if($customers->count() > 0)
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Orders</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Spent</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member Since</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($customers as $customer)
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $customer->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $customer->email }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $customer->phone ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $customer->total_orders ?? 0 }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">₦{{ number_format($customer->total_spent ?? 0, 2) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $customer->created_at->format('M d, Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $customers->links() }}
        </div>
    @else
        <div class="bg-white rounded-lg shadow p-8 text-center">
            <p class="text-gray-500 text-lg">No customers found.</p>
        </div>
    @endif
</div>
@endsection
