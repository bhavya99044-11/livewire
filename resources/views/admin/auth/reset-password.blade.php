

@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 py-6 flex flex-col justify-center sm:py-12">
    <div class="relative py-3 sm:max-w-xl sm:mx-auto">
      <div
        class="absolute inset-0 bg-gradient-to-r from-cyan-400 to-sky-500 shadow-lg transform -skew-y-6 sm:skew-y-0 sm:-rotate-6 sm:rounded-3xl">
      </div>
      <div class="relative px-4 py-10 bg-white shadow-lg sm:rounded-3xl sm:p-20">
  
        <div class="max-w-md mx-auto">
          <div>
            <h1 class="text-2xl font-semibold">{{__('messages.forgot_password')}}</h1>
          </div>
          <div class="divide-y ">
            <form method="post" action="{{ route('admin.reset-password.post', ['token' =>request()->route('token')]) }}" class="py-8 text-base leading-6 space-y-5 text-gray-700 sm:text-lg sm:leading-7">
              @csrf
              <div class="relative">
                <input  autocomplete="off"  id="password" name="password" type="password" class="peer  h-10 w-full border-b-2 border-gray-300 text-gray-900 focus:outline-none focus:borer-rose-600" placeholder="{{__("messages.password")}}" />
                <div class="text-red-500  text-sm w-full">
                  @error('password')
                  {{$message}}
                @enderror
                    @if(Session('error'))
                    <div class="text-red-500  text-sm w-full">
                        {{Session('error')}}
                    </div>
                    @endif
                </div>
              </div>
              <div class="relative">
                <input  autocomplete="off"  name="password_confirmation" type="password" class="peer  h-10 w-full border-b-2 border-gray-300 text-gray-900 focus:outline-none focus:borer-rose-600" placeholder="{{__('messages.confirm_password')}}" />
                {{-- <label for="email" class="absolute left-0 -top-3.5 text-gray-600 text-sm peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-440 peer-placeholder-shown:top-2 transition-all peer-focus:-top-3.5 peer-focus:text-gray-600 peer-focus:text-sm">{{__('messages.confirm_password')}}</label> --}}
                <div class="text-red-500  text-sm w-full">
                  @error('password_confirmation')
                  {{$message}}
                @enderror
                    @if(Session('error'))
                    <div class="text-red-500  text-sm w-full">
                        {{Session('error')}}
                    </div>
                    @endif
                </div>
              </div>
            
              <div class="relative flex justify-between">
                <button class="bg-cyan-500 text-white rounded-md px-2 py-1">{{__('messages.reset_password')}}</button>
                <div class="text-sm text-blue-600 flex items-center justify-center">
                    <a href="{{route('admin.login')}}">{{__('messages.back_to_login')}}</a>
                </div>
              </div>
            </form>
          </div>
        </div>
  
       
      </div>
    </div>
  </div>
  @endsection