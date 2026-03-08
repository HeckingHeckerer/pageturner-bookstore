@extends('layouts.app')

@section('title', 'Order #' . $order->id . ' - PageTurner')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h1 class="text-2xl font-bold mb-4">Order #{{ $order->id }}</h1>

            <div class="grid md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h3 class="font-semibold mb-2">Order Details</h3>
                    <p><strong>Order Date:</strong> {{ $order->created_at->format('M d, Y \a\t g:i A') }}</p>
                    <p><strong>Status:</strong>
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
                    </p>
                    <p><strong>Total Amount:</strong> ${{ number_format($order->total_amount, 2) }}</p>
                </div>

                <div>
                    <h3 class="font-semibold mb-2">Shipping Information</h3>
                    <p><strong>Name:</strong> {{ $order->user->name }}</p>
                    <p><strong>Email:</strong> {{ $order->user->email }}</p>
                    <div class="mt-2">
                        <p><strong>Shipping Address:</strong></p>
                        <p>{{ $order->shipping_address }}</p>
                        <p>{{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zip }}</p>
                        <p>{{ $order->shipping_country }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold mb-4">Order Items</h2>

            <div class="space-y-4">
                @foreach($order->orderItems as $item)
                    <div class="flex items-center border-b pb-4">
                        <img src="{{ $item->book->cover_image ? asset('storage/'.$item->book->cover_image) : 'https://via.placeholder.com/80' }}"
                             alt="{{ $item->book->title }}"
                             class="w-20 h-28 object-cover rounded mr-4">

                        <div class="flex-grow">
                            <h3 class="font-semibold text-lg">{{ $item->book->title }}</h3>
                            <p class="text-gray-600">by {{ $item->book->author }}</p>
                            <p class="text-sm text-gray-500">ISBN: {{ $item->book->isbn }}</p>
                        </div>

                        <div class="text-right">
                            <p class="text-sm text-gray-600">Quantity: {{ $item->quantity }}</p>
                            <p class="text-sm text-gray-600">Price: ${{ number_format($item->unit_price, 2) }}</p>
                            <p class="font-semibold">${{ number_format($item->unit_price * $item->quantity, 2) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6 pt-4 border-t">
                <div class="flex justify-between items-center text-xl font-bold">
                    <span>Total:</span>
                    <span>${{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>
        </div>

        <div class="mt-6 text-center">
            <a href="{{ route('orders.index') }}" class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition mr-4">
                Back to Orders
            </a>
            <a href="{{ route('books.index') }}" class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition">
                Continue Shopping
            </a>

            @if($order->status === 'pending')
                <button type="button" onclick="openCancelModal({{ $order->id }})" class="bg-red-500 text-white px-6 py-3 rounded-lg hover:bg-red-600 transition ml-4">
                    Cancel Order
                </button>
            @endif
        </div>
    </div>
</div>

<!-- Cancel Order Modal -->
<div id="cancelModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm mx-4">
        <div class="flex items-center mb-4">
            <svg class="h-8 w-8 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0 5v.01M7.08 6.36A9 9 0 1021 12a9 9 0 00-13.92-5.64z"></path>
            </svg>
            <h3 class="text-xl font-bold text-gray-900">Cancel Order?</h3>
        </div>
        
        <p class="text-gray-600 mb-2">Are you sure you want to cancel this order?</p>
        <p class="text-sm text-gray-500 mb-6">This action will restore all items to inventory. This cannot be undone.</p>
        
        <div class="flex justify-end space-x-3">
            <button type="button" onclick="closeCancelModal()" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition font-medium">
                Keep Order
            </button>
            <form id="cancelForm" method="POST" style="display:inline;">
                @csrf
                @method('PATCH')
                <button type="submit" class="px-4 py-2 text-white bg-red-500 rounded-lg hover:bg-red-600 transition font-medium">
                    Yes, Cancel Order
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function openCancelModal(orderId) {
    const form = document.getElementById('cancelForm');
    form.action = `/orders/${orderId}/cancel`;
    document.getElementById('cancelModal').classList.remove('hidden');
}

function closeCancelModal() {
    document.getElementById('cancelModal').classList.add('hidden');
}

// Close modal when clicking outside of it
document.getElementById('cancelModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeCancelModal();
    }
});

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeCancelModal();
    }
});
</script>
@endsection