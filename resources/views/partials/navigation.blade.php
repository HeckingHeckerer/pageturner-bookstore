
<nav class="bg-indigo-600 text-white shadow-lg">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
<div class="flex justify-between h-16">
<div class="flex items-center">
<!-- Logo --><a href="{{ route('home') }}" class="text-xl font-bold">PageTurner
</a>
<!-- Navigation Links -->
<div class="hidden md:flex ml-10 space-x-4"><a href="{{ route('home') }}" class="hover:bg-indigo-700 px-3 py-2 rounded-md">

Home
</a>
<a href="{{ route('books.index') }}" class="hover:bg-indigo-700 px-3 py-2 rounded-md">

Books
</a>
<a href="{{ route('categories.index') }}" class="hover:bg-indigo-700 px-3 py-2 rounded-md">

Categories
</a>
@auth
@if(auth()->user()->isAdmin())
<a href="{{ route('admin.dashboard') }}" class="hover:bg-indigo-700 px-3 py-2 rounded-md">
Admin Dashboard
</a>
@endif
@endauth
</div>
</div>


<!-- Right Side -->
<div class="flex items-center space-x-4">
@guest

<a href="{{ route('login') }}" class="hover:bg-indigo-700 px-3 py-2 rounded-md">

Login
</a>
<a href="{{ route('register') }}" class="bg-white text-indigo-600 px-4 py-2 rounded-md font-medium">

Register
</a>
@endguest
@auth
<a href="{{ route('cart') }}" class="hover:bg-indigo-700 px-3 py-2 rounded-md">

Cart {{ auth()->user()->cart()->count() ? '(' . auth()->user()->cart()->count() . ')' : '' }}
</a>
<a href="{{ route('orders.index') }}" class="hover:bg-indigo-700 px-3 py-2 rounded-md">

@if(auth()->user()->isAdmin())
View Orders
@else
My Orders
@endif
</a>

<!-- User Account Dropdown -->
<x-dropdown align="right" width="64">
    <x-slot name="trigger">
        <button class="hover:bg-indigo-700 px-3 py-2 rounded-md text-indigo-200 flex items-center">
            <span>{{ auth()->user()->name }}</span>
        </button>
    </x-slot>

    <x-slot name="content">
        <div class="px-4 py-3 border-b border-gray-200">
            <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
            <p class="text-sm text-gray-500">{{ auth()->user()->email }}</p>
        </div>
        
        @php
        $user = auth()->user();
        $address = collect([
            $user->default_shipping_address,
            $user->default_shipping_city,
            $user->default_shipping_state,
            $user->default_shipping_zip,
            $user->default_shipping_country
        ])->filter()->implode(', ');
        @endphp
        
        @if($address)
        <div class="px-4 py-3 bg-gray-50">
            <p class="text-xs text-gray-500 uppercase">Shipping Address</p>
            <p class="text-sm text-gray-900">{{ $address }}</p>
        </div>
        @endif
        
        <x-dropdown-link href="{{ route('profile.edit') }}">
            Profile Settings
        </x-dropdown-link>
        
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <x-dropdown-link href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
                {{ __('Log Out') }}
            </x-dropdown-link>
        </form>
    </x-slot>
</x-dropdown>
@endauth
</div>
</div>
</div>
</nav>


