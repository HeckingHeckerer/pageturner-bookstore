@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-900">Notifications</h2>
                    <div class="flex space-x-4">
                        <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                                Mark All as Read
                            </button>
                        </form>
                    </div>
                </div>

                @php
                    $unreadNotifications = $notifications->whereNull('read_at');
                    $readNotifications = $notifications->whereNotNull('read_at');
                @endphp

                <!-- Unread Notifications Section -->
                @if($unreadNotifications->isNotEmpty())
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-700 mb-4">Unread Notifications</h3>
                        <div class="space-y-4">
                            @foreach($unreadNotifications as $notification)
                                <div class="flex items-start space-x-4 p-4 border border-gray-200 rounded-lg bg-white">
                                    <div class="flex-shrink-0">
                                        @if($notification->type === 'order_placed' || $notification->type === 'order_status_changed')
                                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                          d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                                </svg>
                                            </div>
                                        @elseif($notification->type === 'new_order')
                                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                        @elseif($notification->type === 'new_review')
                                            <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                          d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.54 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.784.57-1.838-.197-1.539-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <p class="text-lg font-medium text-gray-900">
                                                {{ $notification->data['message'] }}
                                            </p>
                                            <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded-full font-medium">
                                                New
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-500 mt-2">
                                            {{ $notification->created_at->format('F j, Y \a\t g:i A') }}
                                        </p>
                                        <div class="mt-4 flex space-x-3">
                                            <form action="{{ route('notifications.destroy', $notification) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="text-red-600 hover:text-red-800 text-sm font-medium">
                                                    Delete
                                                </button>
                                            </form>
                                            <form action="{{ route('notifications.markAsRead', $notification) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                    Mark as Read
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Marked Messages Section -->
                @if($readNotifications->isNotEmpty())
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-4">Marked Messages</h3>
                        <div class="space-y-4">
                            @foreach($readNotifications as $notification)
                                <div class="flex items-start space-x-4 p-4 border border-gray-200 rounded-lg bg-gray-50">
                                    <div class="flex-shrink-0">
                                        @if($notification->type === 'order_placed' || $notification->type === 'order_status_changed')
                                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                          d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                                </svg>
                                            </div>
                                        @elseif($notification->type === 'new_order')
                                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                        @elseif($notification->type === 'new_review')
                                            <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                          d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.54 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.784.57-1.838-.197-1.539-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <p class="text-lg font-medium text-gray-900">
                                                {{ $notification->data['message'] }}
                                            </p>
                                        </div>
                                        <p class="text-sm text-gray-500 mt-2">
                                            {{ $notification->created_at->format('F j, Y \a\t g:i A') }}
                                        </p>
                                        <div class="mt-4 flex space-x-3">
                                            <form action="{{ route('notifications.destroy', $notification) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="text-red-600 hover:text-red-800 text-sm font-medium">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <!-- Show empty state when no notifications at all -->
                    @if($notifications->isEmpty())
                        <div class="text-center py-8 text-gray-500">
                            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            <p>No notifications yet</p>
                        </div>
                    @endif
                @endif

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $notifications->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
