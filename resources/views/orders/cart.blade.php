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
                            <tr class="border-b" data-book-id="{{ $item['book']->id }}" data-price="{{ $item['book']->price }}">
                                <td class="py-4">
                                    <div class="flex items-center">
                                        <img src="{{ $item['book']->cover_image ? asset('storage/'.$item['book']->cover_image) : 'https://via.placeholder.com/50' }}"
                                             alt="{{ $item['book']->title }}"
                                             class="w-12 h-16 object-cover rounded mr-4">
                                        <div>
                                            <h3 class="font-semibold">{{ $item['book']->title }}</h3>
                                            <p class="text-sm text-gray-600">by {{ $item['book']->author }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 item-price">${{ number_format($item['book']->price, 2) }}</td>
                                <td class="py-4">
                                    <input type="number" class="quantity-input w-16 border rounded px-2 py-1" value="{{ $item['quantity'] }}" min="1" max="{{ $item['book']->stock_quantity }}" data-book-id="{{ $item['book']->id }}">
                                </td>
                                <td class="py-4 item-subtotal">${{ number_format($item['subtotal'], 2) }}</td>
                                <td class="py-4">
                                    <form action="{{ route('cart.remove', $item['book']) }}" method="POST">
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
                <form action="{{ route('orders.store') }}" method="POST" id="checkout-form">
                    @csrf
                    <button type="submit" class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition">
                        Place Order
                    </button>
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
                    const price = parseFloat(row.getAttribute('data-price'));

                    // Auto-submit quantity updates to backend
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/cart/update/` + bookId;
                    form.style.display = 'none';

                    form.innerHTML = `
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="quantity" value="${quantity}">
                    `;

                    // Optional: You can submit this form, but for now just calculate client-side
                    // document.body.appendChild(form);
                    // form.submit();
                    // document.body.removeChild(form);
                });
            }

            // Add event listeners to all quantity inputs
            quantityInputs.forEach(input => {
                input.addEventListener('change', updateTotals);
                input.addEventListener('input', updateTotals);
            });

            // Initialize totals on page load
            updateTotals();
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