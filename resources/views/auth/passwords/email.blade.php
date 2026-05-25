@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
<div class="mx-auto w-full max-w-md p-6 bg-white rounded-xl border border-[#e6d9f5]">
    <h2 class="text-lg font-semibold mb-4">Forgot your password?</h2>

    @if(session('status'))
        <div class="mb-4 rounded p-3 bg-green-50 text-green-800">{{ session('status') }}</div>
    @endif

    <form method="post" action="{{ route('password.email') }}" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-semibold text-[#4f433d]">Email</label>
            <input type="email" name="email" required class="mt-2 w-full rounded-2xl border border-[#e6d9f5] px-3 py-2" />
        </div>
        <button type="submit" class="w-full rounded-full bg-gradient-to-r from-[#5b1e7e] to-[#8b2e9e] text-white py-2 font-semibold">Send reset link</button>
    </form>

    <p class="mt-4 text-sm">Return to <a href="{{ route('login') }}" class="text-[#5b1e7e] font-semibold">sign in</a></p>
</div>
@endsection
