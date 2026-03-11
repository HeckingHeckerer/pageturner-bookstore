<div x-data="{ open: false }" class="relative">
    <!-- Notification Button -->
    <button 
        @click="open = !open"
        class="relative p-2 text-white hover:bg-indigo-700 rounded-md transition-colors"
        aria-label="Notifications"
    >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
        </svg>
        
        <!-- Unread Count Badge -->
        @if(auth()->check())
            @php
                $unreadCount = auth()->user()->notifications()->whereNull('read_at')->count();
            @endphp
            @if($unreadCount > 0)
                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold">
                    {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                </span>
            @endif
        @endif
    </button>

    <!-- Dropdown Menu -->
    <div 
        x-show="open"
        @click.away="open = false"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 z-50"
    >
        <!-- Header -->
        <div class="px-4 py-3 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Notifications</h3>
        </div>

        <!-- Notifications List -->
        <div class="max-h-80 overflow-y-auto">
            @if(auth()->check())
                @php
                    $notifications = auth()->user()->notifications()->latest()->limit(10)->get();
                @endphp
                
                @forelse($notifications as $notification)
                    <a href="#" 
                       class="block px-4 py-3 border-b border-gray-100 hover:bg-gray-50 transition-colors {{ !$notification->isRead() ? 'bg-blue-50' : '' }}"
                       onclick="markNotificationAsRead({{ $notification->id }})"
                    >
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                @if($notification->type === 'order_placed' || $notification->type === 'order_status_changed')
                                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                        </svg>
                                    </div>
                                @elseif($notification->type === 'new_order')
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                @elseif($notification->type === 'new_review')
                                    <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.54 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.784.57-1.838-.197-1.539-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">
                                    {{ $notification->data['message'] }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $notification->created_at->diffForHumans() }}
                                </p>
                                @if(!$notification->isRead())
                                    <span class="inline-block mt-1 px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                        New
                                    </span>
                                @endif
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="px-4 py-6 text-center text-gray-500">
                        No notifications yet
                    </div>
                @endforelse
            @else
                <div class="px-4 py-6 text-center text-gray-500">
                    Please log in to see notifications
                </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="px-4 py-2 border-t border-gray-200 bg-gray-50">
            <a href="{{ route('notifications.index') }}" 
               class="block text-center text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                View All Notifications
            </a>
        </div>
    </div>
</div>

<script>
function markNotificationAsRead(notificationId) {
    fetch(`/notifications/${notificationId}/read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        // Update the notification count in the badge
        updateNotificationCount();
        
        // Remove the 'New' badge from the clicked notification
        event.target.closest('a').querySelector('.bg-blue-50')?.classList.remove('bg-blue-50');
        event.target.closest('a').querySelector('.text-blue-800')?.remove();
    })
    .catch(error => console.error('Error:', error));
}

function updateNotificationCount() {
    fetch('/notifications/count')
        .then(response => response.json())
        .then(data => {
            const badge = document.querySelector('.bg-red-500');
            if (data.count > 0) {
                if (badge) {
                    badge.textContent = data.count > 99 ? '99+' : data.count;
                } else {
                    // Create badge if it doesn't exist
                    const button = document.querySelector('[aria-label="Notifications"]');
                    const newBadge = document.createElement('span');
                    newBadge.className = 'absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold';
                    newBadge.textContent = data.count > 99 ? '99+' : data.count;
                    button.appendChild(newBadge);
                }
            } else {
                if (badge) {
                    badge.remove();
                }
            }
        });
}
</script>