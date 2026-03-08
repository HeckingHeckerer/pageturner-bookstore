@extends('layouts.app')

@section('title', 'Shopping Cart - PageTurner')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-white mb-8">Shopping Cart</h1>

    @if(session('success'))
        <x-alert type="success" class="mb-6">
            {{ session('success') }}
        </x-alert>
    @endif

    @if(session('error'))
        <x-alert type="error" class="mb-6">
            {{ session('error') }}
        </x-alert>
    @endif

    @if(count($cartItems) > 0)
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-2">Book</th>
                            <th class="text-left py-2">Price</th>
                            <th class="text-left py-2">Quantity</th>
                            <th class="text-left py-2">Subtotal</th>
                            <th class="text-left py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="cart-items">
                        @foreach($cartItems as $item)
                            <tr class="border-b" data-book-id="{{ $item->book->id }}" data-price="{{ $item->book->price }}">
                                <td class="py-4">
                                    <div class="flex items-center">
                                        <img src="{{ $item->book->cover_image ? asset('storage/'.$item->book->cover_image) : 'https://via.placeholder.com/50' }}"
                                             alt="{{ $item->book->title }}"
                                             class="w-12 h-16 object-cover rounded mr-4">
                                        <div>
                                            <h3 class="font-semibold">{{ $item->book->title }}</h3>
                                            <p class="text-sm text-gray-600">by {{ $item->book->author }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 item-price">${{ number_format($item->book->price, 2) }}</td>
                                <td class="py-4">
                                    <input type="number" class="quantity-input w-16 border rounded px-2 py-1" value="{{ $item->quantity }}" min="1" max="{{ $item->book->stock_quantity }}" data-book-id="{{ $item->book->id }}">
                                </td>
                                <td class="py-4 item-subtotal">${{ number_format($item->book->price * $item->quantity, 2) }}</td>
                                <td class="py-4">
                                    <form action="{{ route('cart.remove', $item->book) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6 flex justify-between items-center">
                <div class="text-xl font-bold">
                    Total: <span id="cart-total">${{ number_format($total, 2) }}</span>
                </div>
                <button type="button" onclick="openCheckoutModal()" class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition">
                    Checkout
                </button>
            </div>
        </div>

        <!-- Checkout Modal -->
        <div id="checkoutModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-lg p-6 max-w-lg mx-4 w-full">
                <div class="flex items-center mb-4">
                    <svg class="h-8 w-8 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <h3 class="text-xl font-bold text-gray-900">Complete Your Order</h3>
                </div>
                
                <form id="checkoutForm" action="{{ route('orders.store') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="shipping_address" class="block text-sm font-medium text-gray-700">Shipping Address</label>
                            <input type="text" id="shipping_address" name="shipping_address" required 
                                   value="{{ Auth::user()->default_shipping_address ?? '' }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="shipping_city" class="block text-sm font-medium text-gray-700">City</label>
                                <input type="text" id="shipping_city" name="shipping_city" required 
                                       value="{{ Auth::user()->default_shipping_city ?? '' }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                            </div>
                            <div>
                                <label for="shipping_state" class="block text-sm font-medium text-gray-700">State</label>
                                <input type="text" id="shipping_state" name="shipping_state" required 
                                       value="{{ Auth::user()->default_shipping_state ?? '' }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="shipping_zip" class="block text-sm font-medium text-gray-700">ZIP Code</label>
                                <input type="text" id="shipping_zip" name="shipping_zip" required 
                                       value="{{ Auth::user()->default_shipping_zip ?? '' }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                            </div>
                            <div>
                                <label for="shipping_country" class="block text-sm font-medium text-gray-700">Country</label>
                                <input type="text" id="shipping_country" name="shipping_country" required 
                                       value="{{ Auth::user()->default_shipping_country ?? '' }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                            </div>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="save_as_default" name="save_as_default" checked class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                            <label for="save_as_default" class="ml-2 block text-sm text-gray-900">
                                Save as my default shipping address
                            </label>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" onclick="closeCheckoutModal()" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition font-medium">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 text-white bg-green-500 rounded-lg hover:bg-green-600 transition font-medium">
                            Place Order
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            const quantityInputs = document.querySelectorAll('.quantity-input');
            const cartTotal = document.getElementById('cart-total');
            const cartItemsTable = document.getElementById('cart-items');

            function updateTotals() {
                let total = 0;

                // Update each item's subtotal
                const rows = cartItemsTable.querySelectorAll('tr');
                rows.forEach(row => {
                    const quantityInput = row.querySelector('.quantity-input');
                    const priceText = row.querySelector('.item-price').textContent;
                    const subtotalElement = row.querySelector('.item-subtotal');

                    // Parse price from "$X.XX" format
                    const price = parseFloat(priceText.replace('$', ''));
                    const quantity = parseInt(quantityInput.value) || 0;
                    const subtotal = price * quantity;

                    // Update subtotal display
                    subtotalElement.textContent = '$' + subtotal.toFixed(2);

                    // Add to total
                    total += subtotal;
                });

                // Update total display
                cartTotal.textContent = '$' + total.toFixed(2);

                // Also update hidden cart data for form submission if needed
                updateCartSession();
            }

            function updateCartSession() {
                const rows = cartItemsTable.querySelectorAll('tr');
                rows.forEach(row => {
                    const bookId = row.getAttribute('data-book-id');
                    const quantity = row.querySelector('.quantity-input').value;

                    // Send quantity update to backend via fetch
                    fetch(`/cart/update/${bookId}`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        },
                        body: JSON.stringify({
                            quantity: parseInt(quantity)
                        })
                    }).catch(error => console.error('Error updating cart:', error));
                });
            }

            // Add event listeners to all quantity inputs
            quantityInputs.forEach(input => {
                input.addEventListener('change', updateTotals);
                input.addEventListener('input', updateTotals);
            });

            // Initialize totals on page load
            updateTotals();

            function openCheckoutModal() {
                document.getElementById('checkoutModal').classList.remove('hidden');
            }

            function closeCheckoutModal() {
                document.getElementById('checkoutModal').classList.add('hidden');
            }

            // Close modal when clicking outside
            document.getElementById('checkoutModal')?.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeCheckoutModal();
                }
            });

            // Close modal on Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeCheckoutModal();
                }
            });
        </script>
    @else
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <h2 class="text-2xl font-semibold mb-4">Your cart is empty</h2>
            <p class="text-gray-600 mb-6">Add some books to get started!</p>
            <a href="{{ route('books.index') }}" class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition">
                Browse Books
            </a>
        </div>
    @endif
</div>
@endsection