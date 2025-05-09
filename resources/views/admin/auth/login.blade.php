@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100  flex items-center justify-center px-4">
    <div class="relative max-w-sm w-[600px]">
        <!-- Gradient Background -->
        <div class="absolute inset-0 bg-gradient-to-r  from-cyan-400 to-sky-500 shadow-lg transform -skew-y-6 rounded-xl sm:rounded-3xl"></div>

        <!-- Login Card -->
        <div class="relative bg-white shadow-lg rounded-xl sm:rounded-3xl px-5 py-8">
            <h1 class="text-xl font-semibold text-center mb-6">{{ __('messages.login') }}</h1>
            
            <form method="POST" action="{{ route('admin.login') }}" class="space-y-5">
                @csrf

                <!-- Email -->
                <div class="relative">
                    <input id="email" name="email" type="text" value="{{ old('email') }}" autocomplete="off"
                        class="peer placeholder-transparent w-full border-b-2 border-gray-300 h-10 text-sm text-gray-900 focus:outline-none focus:border-sky-500"
                        placeholder="{{ __('messages.email_address') }}" />
                    <label for="email"
                        class="absolute left-0 -top-3.5 text-gray-600 text-xs transition-all peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400 peer-placeholder-shown:top-2 peer-focus:-top-3.5 peer-focus:text-gray-600 peer-focus:text-xs">
                        {{ __('messages.email_address') }}
                    </label>
                    <div class="text-red-500 text-xs mt-1">
                        @error('email') {{ $message }} @enderror
                    </div>
                </div>

                <!-- Password -->
                <div class="relative">
                    <input id="password" name="password" type="password" autocomplete="off"
                        class="peer placeholder-transparent w-full border-b-2 border-gray-300 h-10 text-sm text-gray-900 focus:outline-none focus:border-sky-500"
                        placeholder="{{ __('messages.password') }}" />
                    <label for="password"
                        class="absolute left-0 -top-3.5 text-gray-600 text-xs transition-all peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400 peer-placeholder-shown:top-2 peer-focus:-top-3.5 peer-focus:text-gray-600 peer-focus:text-xs">
                        {{ __('messages.password') }}
                    </label>
                    <div class="text-red-500 text-xs mt-1">
                        @error('password') {{ $message }} @enderror
                    </div>
                </div>

                <!-- Submit and Forgot -->
                <div class="flex flex-col gap-3">
                    <button class="bg-cyan-500 hover:bg-cyan-600 text-white text-sm font-semibold px-4 py-2 rounded-md">
                        {{ __('messages.login') }}
                    </button>
                    <a href="{{ route('admin.forgot-password.view') }}" class="text-xs text-center text-red-500">
                        {{ __('messages.forgot_password') }}?
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


@push('scripts')

@endpush