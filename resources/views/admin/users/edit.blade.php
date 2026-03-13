@extends('layouts.app')

@section('title', 'Edit User - Admin')

@section('content')
<div class="min-h-screen bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white shadow-xl rounded-lg overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-6 border-b border-gray-200 bg-gradient-to-r from-yellow-500 to-yellow-600">
                <div class="flex items-center">
                    <svg class="h-8 w-8 text-white mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <h1 class="text-2xl font-bold text-white">Edit User: {{ $user->name }}</h1>
                </div>
            </div>

            <div class="p-8">
                @include('partials.flash-messages')

                <form method="POST" action="{{ route('admin.users.update', $user) }}">
                    @csrf
                    @method('PUT')

                    <!-- Basic Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <x-input-label for="name" :value="'Name'" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="email" :value="'Email'" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" value="{{ old('email', $user->email) }}" required autocomplete="username" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Password (Optional) -->
                    <div class="mb-8">
                        <x-input-label for="password" :value="'New Password (leave blank to keep current)'" />
                        <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        <p class="mt-1 text-sm text-gray-500">Optional. Leave blank to keep current password.</p>
                    </div>

                    <!-- Password Confirmation -->
                    <div class="mb-8">
                        <x-input-label for="password_confirmation" :value="'Confirm New Password'" />
                        <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" autocomplete="new-password" />
                    </div>

                    <!-- Role -->
                    <div class="mb-8">
                        <x-input-label for="role" :value="'Role'" />
                        <select id="role" name="role" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                            <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                        <x-input-error :messages="$errors->get('role')" class="mt-2" />
                    </div>

                    <!-- Shipping Info -->
                    <div class="border-t border-gray-200 pt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-6">Default Shipping Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="default_shipping_address" :value="'Address'" />
                                <x-text-input id="default_shipping_address" class="block mt-1 w-full" type="text" name="default_shipping_address" value="{{ old('default_shipping_address', $user->default_shipping_address) }}" autocomplete="address-line1" />
                                <x-input-error :messages="$errors->get('default_shipping_address')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="default_shipping_city" :value="'City'" />
                                <x-text-input id="default_shipping_city" class="block mt-1 w-full" type="text" name="default_shipping_city" value="{{ old('default_shipping_city', $user->default_shipping_city) }}" autocomplete="address-level2" />
                                <x-input-error :messages="$errors->get('default_shipping_city')" class="mt-2" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                            <div>
                                <x-input-label for="default_shipping_state" :value="'State'" />
                                <x-text-input id="default_shipping_state" class="block mt-1 w-full" type="text" name="default_shipping_state" value="{{ old('default_shipping_state', $user->default_shipping_state) }}" />
                                <x-input-error :messages="$errors->get('default_shipping_state')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="default_shipping_zip" :value="'ZIP Code'" />
                                <x-text-input id="default_shipping_zip" class="block mt-1 w-full" type="text" name="default_shipping_zip" value="{{ old('default_shipping_zip', $user->default_shipping_zip) }}" />
                                <x-input-error :messages="$errors->get('default_shipping_zip')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="default_shipping_country" :value="'Country'" />
                                <x-text-input id="default_shipping_country" class="block mt-1 w-full" type="text" name="default_shipping_country" value="{{ old('default_shipping_country', $user->default_shipping_country) }}" />
                                <x-input-error :messages="$errors->get('default_shipping_country')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-col sm:flex-row gap-4 mt-10 pt-8 border-t border-gray-200">
                        <x-primary-button class="flex-1">
                            {{ __('Update User') }}
                        </x-primary-button>
                        <a href="{{ route('admin.users.index') }}" 
                           class="flex-1 text-center px-6 py-3 border border-gray-300 text-gray-700 bg-white rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

