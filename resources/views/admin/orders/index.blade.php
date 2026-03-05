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
                                <div class="mt-4 pt-4 border-t flex justify-end">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">
                                        View Full Details →
                                    </a>
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
@endsection