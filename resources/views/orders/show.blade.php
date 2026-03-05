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
                        <span class="px-2 py-1 rounded text-sm {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : ($order->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </p>
                    <p><strong>Total Amount:</strong> ${{ number_format($order->total_amount, 2) }}</p>
                </div>

                <div>
                    <h3 class="font-semibold mb-2">Shipping Information</h3>
                    <p><strong>Name:</strong> {{ $order->user->name }}</p>
                    <p><strong>Email:</strong> {{ $order->user->email }}</p>
                    <!-- Add more shipping fields if needed -->
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
                            <p class="text-sm text-gray-600">Price: ${{ number_format($item->price, 2) }}</p>
                            <p class="font-semibold">${{ number_format($item->price * $item->quantity, 2) }}</p>
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
        </div>
    </div>
</div>
@endsection