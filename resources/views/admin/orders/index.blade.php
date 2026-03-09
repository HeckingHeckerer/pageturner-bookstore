@extends('layouts.app')
@section('title', 'Customer Orders - Admin Dashboard')

@section('header')
<h1 class="text-3xl font-bold text-gray-900">Customer Orders</h1>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    @if($ordersByUser->count() > 0)
        <div class="space-y-8">
            @foreach($ordersByUser as $userId => $userOrders)
                @php
                    $user = $userOrders->first()->user;
                @endphp
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <!-- User Header -->
                    <div class="bg-gray-50 px-6 py-4 border-b">
                        <div class="flex justify-between items-center">
                            <div>
                                <h2 class="text-xl font-bold text-gray-900">{{ $user->name }}</h2>
                                <p class="text-gray-600">{{ $user->email }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-500">{{ $userOrders->count() }} order{{ $userOrders->count() > 1 ? 's' : '' }}</p>
                                <p class="text-lg font-semibold text-indigo-600">
                                    Total: ${{ number_format($userOrders->sum('total_amount'), 2) }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Orders List -->
                    <div class="divide-y divide-gray-200">
                        @foreach($userOrders as $order)
                            <div class="p-6">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h3 class="text-lg font-semibold">Order #{{ $order->id }}</h3>
                                        <p class="text-gray-600">Placed on {{ $order->created_at->format('M d, Y \a\t g:i A') }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-bold text-indigo-600">${{ number_format($order->total_amount, 2) }}</p>
                                        <span class="px-2 py-1 rounded text-sm
                                            @if($order->status === 'pending')
                                                bg-yellow-100 text-yellow-800
                                            @elseif($order->status === 'completed')
                                                bg-green-100 text-green-800
                                            @elseif($order->status === 'cancelled')
                                                bg-gray-100 text-gray-800
                                            @else
                                                bg-red-100 text-red-800
                                            @endif
                                        ">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Order Items -->
                                <div class="border-t pt-4">
                                    <h4 class="font-semibold mb-3">Items:</h4>
                                    <div class="grid gap-3">
                                        @foreach($order->orderItems as $item)
                                            <div class="flex justify-between items-center bg-gray-50 rounded p-3">
                                                <div class="flex items-center">
                                                    <img src="{{ $item->book->cover_image ? asset('storage/'.$item->book->cover_image) : 'https://via.placeholder.com/50' }}"
                                                         alt="{{ $item->book->title }}"
                                                         class="w-12 h-16 object-cover rounded mr-4">
                                                    <div>
                                                        <p class="font-medium">{{ $item->book->title }}</p>
                                                        <p class="text-sm text-gray-600">by {{ $item->book->author }}</p>
                                                        <p class="text-xs text-gray-500">ISBN: {{ $item->book->isbn }}</p>
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <p class="text-sm">Qty: {{ $item->quantity }}</p>
                                                    <p class="font-medium">${{ number_format($item->unit_price, 2) }} each</p>
                                                    <p class="text-sm font-semibold">${{ number_format($item->unit_price * $item->quantity, 2) }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Order Actions -->
                                <div class="mt-4 pt-4 border-t flex justify-between items-center" id="actions-{{ $order->id }}">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">
                                        ← View Full Details
                                    </a>
                                    @if($order->status === 'pending')
                                        <div class="flex space-x-2">
                                            <button type="button" onclick="confirmAction({{ $order->id }}, 'approve')" class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700">Approve</button>
                                            <button type="button" onclick="confirmAction({{ $order->id }}, 'reject')" class="bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700">Reject</button>
                                        </div>
                                    @elseif($order->status === 'processing')
                                        <span class="text-green-600 font-medium">Order Approved</span>
                                    @elseif($order->status === 'cancelled')
                                        <span class="text-red-600 font-medium">Order Rejected</span>
                                    @else
                                        <span class="text-blue-600 font-medium">Order {{ ucfirst($order->status) }}</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
            </svg>
            <h2 class="text-2xl font-semibold mb-4">No Customer Orders</h2>
            <p class="text-gray-600">There are no orders in the system yet.</p>
        </div>
    @endif
</div>

<!-- Confirmation Modal -->
<div id="confirmation-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg font-medium text-gray-900" id="modal-title">Confirm Action</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500" id="modal-message">Are you sure you want to perform this action?</p>
            </div>
            <div class="flex items-center px-4 py-3">
                <input id="dont-ask-again" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                <label for="dont-ask-again" class="ml-2 block text-sm text-gray-900">
                    Don't ask me again
                </label>
            </div>
            <div class="flex items-center px-4 py-3">
                <button id="confirm-btn" class="px-4 py-2 bg-indigo-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    Confirm
                </button>
                <button id="cancel-btn" class="ml-3 px-4 py-2 bg-gray-300 text-gray-900 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let currentOrderId = null;
let currentAction = null;
let dontAskAgain = localStorage.getItem('adminOrderDontAskAgain') === 'true';

function confirmAction(orderId, action) {
    currentOrderId = orderId;
    currentAction = action;

    if (dontAskAgain) {
        // Skip confirmation and directly submit
        submitAction();
        return;
    }

    // Show modal
    const modal = document.getElementById('confirmation-modal');
    const title = document.getElementById('modal-title');
    const message = document.getElementById('modal-message');

    title.textContent = action === 'approve' ? 'Confirm Approval' : 'Confirm Rejection';
    message.textContent = `Are you sure you want to ${action} order #${orderId}?`;

    modal.classList.remove('hidden');
}

document.getElementById('confirm-btn').addEventListener('click', function() {
    const checkbox = document.getElementById('dont-ask-again');
    if (checkbox.checked) {
        dontAskAgain = true;
        localStorage.setItem('adminOrderDontAskAgain', 'true');
    }

    document.getElementById('confirmation-modal').classList.add('hidden');
    submitAction();
});

document.getElementById('cancel-btn').addEventListener('click', function() {
    document.getElementById('confirmation-modal').classList.add('hidden');
    currentOrderId = null;
    currentAction = null;
});

function submitAction() {
    if (!currentOrderId || !currentAction) return;

    // Create and submit form
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/admin/orders/${currentOrderId}/status`;
    form.style.display = 'none';

    // CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (csrfToken) {
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken.getAttribute('content');
        form.appendChild(csrfInput);
    }

    // Method
    const methodInput = document.createElement('input');
    methodInput.type = 'hidden';
    methodInput.name = '_method';
    methodInput.value = 'PATCH';
    form.appendChild(methodInput);

    // Status
    const statusInput = document.createElement('input');
    statusInput.type = 'hidden';
    statusInput.name = 'status';
    statusInput.value = currentAction === 'approve' ? 'processing' : 'cancelled';
    form.appendChild(statusInput);

    document.body.appendChild(form);
    form.submit();
}
</script>
@endsection