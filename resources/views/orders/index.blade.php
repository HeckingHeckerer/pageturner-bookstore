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
                            <span class="px-2 py-1 rounded text-sm {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : ($order->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
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
                                        <p class="font-medium">${{ number_format($item->price * $item->quantity, 2) }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-t">
                        <a href="{{ route('orders.show', $order) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">
                            View Details →
                        </a>
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
@endsection