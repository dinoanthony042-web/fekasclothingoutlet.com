@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-2xl bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
    <div class="flex items-center gap-4 p-6 border-b border-gray-100">
        <img src="{{ asset('images/fekasdark.png') }}" alt="Logo" class="h-12 w-auto">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Registration Successful</h1>
            <p class="text-sm text-gray-600">Welcome to {{ config('app.name') }} — you're almost ready.</p>
        </div>
    </div>

    <div class="p-6">
        <p class="text-gray-700 mb-4">Thanks for creating an account. We sent a verification email to <strong>{{ session('registered_email') ?? request('email') }}</strong>. Please follow the link in the email to verify your address and access all features.</p>

        <div class="flex flex-col sm:flex-row sm:items-center gap-3">
            <a href="{{ route('login') }}" class="inline-block px-5 py-3 bg-[#111827] text-white rounded-lg text-center font-medium">Go to Login</a>
            <a href="/" class="inline-block px-5 py-3 border border-gray-200 rounded-lg text-gray-700 text-center">Continue Shopping</a>
        </div>

        <hr class="my-6" />

        <div class="text-sm text-gray-500">
            <p>If you don't see the email, check your spam folder or <a href="/contact" class="text-[#5b1e7e]">contact support</a>. The verification link expires in 60 minutes.</p>
        </div>
    </div>
</div>
@endsection