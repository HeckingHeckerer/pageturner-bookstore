@extends('layouts.app')

@section('title', 'View User - Admin')

@section('content')
<div class="min-h-screen bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">User Details</h1>
                    <p class="mt-2 text-gray-600">View information for {{ $user->name }}</p>
                </div>
                <div class="flex space-x-4">
                    <a href="{{ route('admin.users.edit', $user) }}" 
                       class="bg-yellow-600 text-white px-4 py-2 rounded-md hover:bg-yellow-700 transition">
                        Edit User
                    </a>
                    <a href="{{ route('admin.users.index') }}" 
                       class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition">
                        Back to Users
                    </a>
                </div>
            </div>
        </div>

        <!-- User Information Cards -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Basic Information</h2>
                <div class="space-y-4">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <span class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-indigo-500 text-white font-medium text-lg">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </span>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">{{ $user->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $user->email }}</p>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-200 pt-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Role</label>
                                <span class="mt-1 block px-3 py-2 bg-gray-50 border border-gray-300 rounded-md text-sm {{ $user->role === 'admin' ? 'text-red-600' : 'text-green-600' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email Verified</label>
                                <span class="mt-1 block px-3 py-2 bg-gray-50 border border-gray-300 rounded-md text-sm {{ $user->email_verified_at ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $user->email_verified_at ? 'Yes' : 'No' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 pt-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Member Since</label>
                                <span class="mt-1 block px-3 py-2 bg-gray-50 border border-gray-300 rounded-md text-sm">
                                    {{ $user->created_at->format('F j, Y') }}
                                </span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Last Updated</label>
                                <span class="mt-1 block px-3 py-2 bg-gray-50 border border-gray-300 rounded-md text-sm">
                                    {{ $user->updated_at->format('F j, Y') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shipping Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Shipping Information</h2>
                <div class="space-y-4">
                    @if($user->default_shipping_address || $user->default_shipping_city || $user->default_shipping_state || $user->default_shipping_zip || $user->default_shipping_country)
                        <div class="grid grid-cols-1 gap-4">
                            @if($user->default_shipping_address)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Address</label>
                                    <span class="mt-1 block px-3 py-2 bg-gray-50 border border-gray-300 rounded-md text-sm">
                                        {{ $user->default_shipping_address }}
                                    </span>
                                </div>
                            @endif
                            
                            @if($user->default_shipping_city)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">City</label>
                                    <span class="mt-1 block px-3 py-2 bg-gray-50 border border-gray-300 rounded-md text-sm">
                                        {{ $user->default_shipping_city }}
                                    </span>
                                </div>
                            @endif
                            
                            @if($user->default_shipping_state)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">State/Province</label>
                                    <span class="mt-1 block px-3 py-2 bg-gray-50 border border-gray-300 rounded-md text-sm">
                                        {{ $user->default_shipping_state }}
                                    </span>
                                </div>
                            @endif
                            
                            @if($user->default_shipping_zip)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">ZIP/Postal Code</label>
                                    <span class="mt-1 block px-3 py-2 bg-gray-50 border border-gray-300 rounded-md text-sm">
                                        {{ $user->default_shipping_zip }}
                                    </span>
                                </div>
                            @endif
                            
                            @if($user->default_shipping_country)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Country</label>
                                    <span class="mt-1 block px-3 py-2 bg-gray-50 border border-gray-300 rounded-md text-sm">
                                        {{ $user->default_shipping_country }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500 border-2 border-dashed border-gray-300 rounded-lg">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <p>No shipping information provided</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- User Activity Summary -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">User Activity</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="text-3xl font-bold text-indigo-600">{{ $user->orders()->count() }}</div>
                    <div class="text-sm text-gray-500">Total Orders</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-green-600">{{ $user->reviews()->count() }}</div>
                    <div class="text-sm text-gray-500">Reviews Written</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-purple-600">{{ $user->cartItems()->count() }}</div>
                    <div class="text-sm text-gray-500">Items in Cart</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection