@extends('layouts.app')
@section('title', 'Order #' . $order->id . ' - Admin Dashboard')

@section('header')
<h1 class="text-3xl font-bold text-gray-900">Order #{{ $order->id }} Details</h1>
@endsection

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <!-- Back to Orders -->
    <div class="mb-6">
        <a href="{{ route('admin.orders.index') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">
            ← Back to All Orders
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-2xl font-bold mb-4">Order #{{ $order->id }}</h2>

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
                <h3 class="font-semibold mb-2">Customer Information</h3>
                <p><strong>Name:</strong> {{ $order->user->name }}</p>
                <p><strong>Email:</strong> {{ $order->user->email }}</p>
                <p><strong>Account Type:</strong> {{ $order->user->isAdmin() ? 'Admin' : 'Customer' }}</p>
                <p><strong>Member Since:</strong> {{ $order->user->created_at->format('M d, Y') }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold mb-4">Order Items</h2>

        <div class="space-y-4">
            @foreach($order->orderItems as $item)
                <div class="flex items-center border-b pb-4 last:border-b-0">
                    <img src="{{ $item->book->cover_image ? asset('storage/'.$item->book->cover_image) : 'https://via.placeholder.com/80' }}"
                         alt="{{ $item->book->title }}"
                         class="w-20 h-28 object-cover rounded mr-4">

                    <div class="flex-grow">
                        <h3 class="font-semibold text-lg">{{ $item->book->title }}</h3>
                        <p class="text-gray-600">by {{ $item->book->author }}</p>
                        <p class="text-sm text-gray-500">ISBN: {{ $item->book->isbn }}</p>
                        <p class="text-sm text-gray-500">Category: {{ $item->book->category->name }}</p>
                    </div>

                    <div class="text-right">
                        <p class="text-sm">Quantity: {{ $item->quantity }}</p>
                        <p class="text-sm">Unit Price: ${{ number_format($item->unit_price, 2) }}</p>
                        <p class="font-semibold">Subtotal: ${{ number_format($item->unit_price * $item->quantity, 2) }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6 pt-4 border-t">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-600">Stock levels after order:</p>
                    @foreach($order->orderItems as $item)
                        <p class="text-xs text-gray-500">{{ $item->book->title }}: {{ $item->book->stock_quantity }} remaining</p>
                    @endforeach
                </div>
                <div class="text-right">
                    <p class="text-2xl font-bold text-indigo-600">Total: ${{ number_format($order->total_amount, 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Admin Actions (if needed in future) -->
    @if($order->status === 'pending')
        <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-md p-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-medium text-yellow-800">Order Management</h3>
                    <p class="text-sm text-yellow-700 mt-1">This order is pending and can be managed by the customer.</p>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection