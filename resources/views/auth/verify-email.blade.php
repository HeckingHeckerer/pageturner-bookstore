<x-guest-layout>
    <div class="mb-6 text-center">
        <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-700 p-4 rounded">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <!-- Email icon -->
                    <svg class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 7.89a2 2 0 002.82 0L21 8M4 19h16a1 1 0 001-1V6a1 1 0 00-1-1H4a1 1 0 00-1 1v12a1 1 0 001 1z" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-lg font-semibold">Email Verification Required</p>
                    <p class="text-sm">Please check your email for a verification link to complete your registration.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-4 text-sm text-gray-600 text-center">
        {{ __('Thank you for signing up! Before you can log in, you must verify your email address by clicking on the link we just sent to your email. If you didn\'t receive the email, we will gladly send you another.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-600 text-center">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-primary-button>
                    {{ __('Resend Verification Email') }}
                </x-primary-button>
            </div>
        </form>

        <!-- Development: Direct verification link for testing -->
        @if(app()->environment('local', 'testing'))
            <div>
                @if(auth()->check())
                    <a href="{{ route('verification.verify', ['id' => auth()->id(), 'hash' => sha1(auth()->user()->getEmailForVerification())]) }}" 
                       class="ml-4 text-sm text-blue-600 hover:text-blue-800 underline">
                        {{ __('Verify Email (Development)') }}
                    </a>
                @else
                    <a href="{{ route('verification.verify', ['id' => 1, 'hash' => sha1('user@example.com')]) }}" 
                       class="ml-4 text-sm text-blue-600 hover:text-blue-800 underline">
                        {{ __('Verify Email (Development)') }}
                    </a>
                @endif
            </div>
        @endif

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>
