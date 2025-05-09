@extends('layouts.admin')

@section('content')
@php
$breadCrumbs=[
    [
        'name'=>'dashboard',
        'url'=>route('admin.dashboard'),
],
[
    'name'=>'change password',
    'url'=>route('admin.profile.change-password')
]
]

@endphp

@include('admin.components.bread-crumb',['breadCrumbs'=>$breadCrumbs])

  <section class="mt-12  bg-gray-100 flex items-center justify-center p-4">
    <div class="max-w-md  w-full mx-auto bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-all duration-300">
      <div class="px-8 py-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">{{__('messages.change_password')}}</h2>
        
        <form id="passwordForm" class="space-y-4" method="post" action={{route('admin.profile.change-password')}}>
            @csrf
          <div>
            <label for="oldPassword" class="block text-sm font-medium text-gray-700 mb-1">
             {{__('messages.current_password')}}
            </label>
            <div class="relative group">
                <input
                  type="password"
                  id="oldPassword"
                  value="{{old('current_password')}}"
                  name="current_password"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-500 focus:outline-none transition-all duration-200"
                  placeholder="{{__('messages.enter_current_password')}}"
                />
                <button
                  type="button"
                  onclick="togglePassword('oldPassword')"
                  class="absolute right-3 top-[22px] transform -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none transition-colors duration-200"
                >
                <i class="group-[.active]:hidden fa-solid fa-eye"></i>
                <i class="hidden group-[.active]:block fa-solid fa-eye-slash"></i>
                </button>
                <span class="text-red-500">
                    @error('current_password')
                        {{$message}}
                    @enderror
                </span>
              </div>
          </div>

          <div>
            <label for="newPassword" class="block text-sm font-medium text-gray-700 mb-1">
             {{__('messages.new_password')}}
            </label>
            <div class="relative group">
              <input
                type="password"
                id="newPassword"
                name="password"
                value="{{old('password')}}"
                class="w-full peer px-4 py-2 group border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-500 focus:outline-none transition-all duration-200"
                placeholder="{{__('messages.enter_new_password')}}"
              />
              <button
                type="button"
                onclick="togglePassword('newPassword')"
                class="absolute right-3 top-[22px] transform -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none transition-colors duration-200"
              >
              <i class="group-[.active]:hidden fa-solid fa-eye"></i>
              <i class="hidden group-[.active]:block fa-solid fa-eye-slash"></i>
              </button>
              <span class="text-red-500">
                @error('password')
                    {{$message}}
                @enderror
            </span>
            </div>
          </div>

          <div>
            <label for="confirmPassword" class="block text-sm font-medium text-gray-700 mb-1">
              {{__('messages.confirm_password')}}
            </label>
            <div class="relative group">
              <input
                type="password"
                id="confirmPassword"
                name="password_confirmation"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-500 focus:outline-none transition-all duration-200"
                placeholder="{{__('messages.enter_confirm_password')}}"
              />
              <button
                type="button"
                value="{{old('password_confirmation')}}"
                onclick="togglePassword('confirmPassword')"
                class="absolute right-3 top-[22px] transform -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none transition-colors duration-200"
              >
              <i class="group-[.active]:hidden fa-solid fa-eye"></i>
              <i class="hidden group-[.active]:block fa-solid fa-eye-slash"></i>
              </button>
              <span class="text-red-500">
                @error('password_confirmation')
                    {{$message}}
                @enderror
            </span>
            </div>
          </div>

          <div class="mt-8">
            <button
              type="submit"
              class="w-full px-4 py-2 bg-blue-500 text-white font-medium rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300 focus:ring-opacity-50 transform active:scale-[0.98] transition-all duration-300"
            >
              {{__('messages.change_password')}}
            </button>
          </div>
        </form>
      </div>
    </div>

      
  </section>

@endsection

@push('scripts')

<script>
       function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        input.parentElement.classList.toggle('active')
        input.type = input.type === 'password' ? 'text' : 'password';
      }
</script>
@endpush