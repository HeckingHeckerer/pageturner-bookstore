<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Two-Factor Authentication') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('two-factor.settings') }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="two_factor_enabled" value="1" {{ $user->two_factor_enabled ? 'checked' : '' }} class="form-checkbox">
                                <span class="ml-2 text-gray-700">{{ __('Enable Two-Factor Authentication') }}</span>
                            </label>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Save Settings') }}
                            </x-primary-button>
                        </div>
                    </form>

                    <div class="mt-6">
                        <p class="text-sm text-gray-600">
                            {{ __('Two-factor authentication adds an extra layer of security to your account. When enabled, you will be required to provide a verification code from your email when logging in.') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>