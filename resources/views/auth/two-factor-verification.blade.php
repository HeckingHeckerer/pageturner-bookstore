<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Two-step verification is enabled for your account. Please enter the 4-digit verification code sent to your email to complete the login process.') }}
    </div>

    <form method="POST" action="{{ route('two-factor.verify') }}">
        @csrf

        <!-- Verification Code -->
        <div>
            <x-input-label for="code" :value="__('4-Digit Verification Code')" />
            <x-text-input id="code" class="block mt-1 w-full" type="text" name="code" :value="old('code')" required autofocus autocomplete="one-time-code" maxlength="4" pattern="[0-9]{4}" />
            <x-input-error :messages="$errors->get('code')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                {{ __('Cancel') }}
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>

            <x-primary-button>
                {{ __('Verify Code') }}
            </x-primary-button>
        </div>

        <div class="mt-4 text-center">
            <a href="{{ route('two-factor.resend') }}" class="text-sm text-gray-600 hover:text-gray-900">
                {{ __('Resend Code') }}
            </a>
        </div>
    </form>
</x-guest-layout>
