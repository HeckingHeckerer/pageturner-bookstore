@extends('layouts.app')

@section('title', 'My Orders - PageTurner')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-white mb-8">My Orders</h1>

    @if($orders->count() > 0)
        <div class="space-y-6">
            @foreach($orders as $order)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-lg font-semibold">Order #{{ $order->id }}</h3>
                            <p class="text-gray-600">Placed on {{ $order->created_at->format('M d, Y') }}</p>
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

                    <div class="border-t pt-4">
                        <h4 class="font-semibold mb-2">Items:</h4>
                        <div class="space-y-2">
                            @foreach($order->orderItems as $item)
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center">
                                        <img src="{{ $item->book->cover_image ? asset('storage/'.$item->book->cover_image) : 'https://via.placeholder.com/40' }}"
                                             alt="{{ $item->book->title }}"
                                             class="w-10 h-14 object-cover rounded mr-3">
                                        <div>
                                            <p class="font-medium">{{ $item->book->title }}</p>
                                            <p class="text-sm text-gray-600">by {{ $item->book->author }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm">Qty: {{ $item->quantity }}</p>
                                        <p class="font-medium">${{ number_format($item->unit_price * $item->quantity, 2) }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="border-t pt-4">
                        <div class="flex justify-between items-center mb-2">
                            <h4 class="font-semibold">Shipping Address:</h4>
                            @if($order->status === 'pending')
                                <button type="button" 
                                        onclick="openShippingModal({{ $order->id }}, '{{ $order->shipping_address ?? '' }}', '{{ $order->shipping_city ?? '' }}', '{{ $order->shipping_state ?? '' }}', '{{ $order->shipping_zip ?? '' }}', '{{ $order->shipping_country ?? '' }}')" 
                                        class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                    Change
                                </button>
                            @endif
                        </div>
                        <div id="shipping-info-{{ $order->id }}">
                            <p class="text-gray-700">{{ $order->shipping_address }}</p>
                            <p class="text-gray-700">{{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zip }}</p>
                            <p class="text-gray-700">{{ $order->shipping_country }}</p>
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-t flex justify-between items-center">
                        <a href="{{ route('orders.show', $order) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">
                            View Details →
                        </a>

                        @if($order->status === 'pending')
                            <button type="button" onclick="openCancelModal({{ $order->id }})" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition">
                                Cancel Order
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $orders->links() }}
        </div>
    @else
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <h2 class="text-2xl font-semibold mb-4">No orders yet</h2>
            <p class="text-gray-600 mb-6">Start shopping to see your orders here!</p>
            <a href="{{ route('books.index') }}" class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition">
                Browse Books
            </a>
        </div>
    @endif
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

<!-- Update Shipping Modal -->
<div id="shippingModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg p-6 max-w-md mx-4 w-full">
        <div class="flex items-center mb-4">
            <svg class="h-8 w-8 text-indigo-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            <h3 class="text-xl font-bold text-gray-900">Update Shipping Address</h3>
        </div>
        
        <form id="shippingForm" method="POST">
            @csrf
            @method('PATCH')
            <div class="space-y-4">
                <div>
                    <label for="shipping_address" class="block text-sm font-medium text-gray-700">Address</label>
                    <input type="text" id="shipping_address" name="shipping_address" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="shipping_city" class="block text-sm font-medium text-gray-700">City</label>
                        <input type="text" id="shipping_city" name="shipping_city" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label for="shipping_state" class="block text-sm font-medium text-gray-700">State</label>
                        <input type="text" id="shipping_state" name="shipping_state" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="shipping_zip" class="block text-sm font-medium text-gray-700">ZIP Code</label>
                        <input type="text" id="shipping_zip" name="shipping_zip" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label for="shipping_country" class="block text-sm font-medium text-gray-700">Country</label>
                        <input type="text" id="shipping_country" name="shipping_country" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="closeShippingModal()" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition font-medium">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 text-white bg-indigo-500 rounded-lg hover:bg-indigo-600 transition font-medium">
                    Update Address
                </button>
            </div>
        </form>
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

function openShippingModal(orderId, address, city, state, zip, country) {
    const form = document.getElementById('shippingForm');
    form.action = `/orders/${orderId}/shipping`;
    
    // Populate form with existing data
    document.getElementById('shipping_address').value = address || '';
    document.getElementById('shipping_city').value = city || '';
    document.getElementById('shipping_state').value = state || '';
    document.getElementById('shipping_zip').value = zip || '';
    document.getElementById('shipping_country').value = country || '';
    
    document.getElementById('shippingModal').classList.remove('hidden');
}

function closeShippingModal() {
    document.getElementById('shippingModal').classList.add('hidden');
}

// Close modals when clicking outside of them
document.getElementById('cancelModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeCancelModal();
    }
});

document.getElementById('shippingModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeShippingModal();
    }
});

// Close modals on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeCancelModal();
        closeShippingModal();
    }
});
</script>
@endsection