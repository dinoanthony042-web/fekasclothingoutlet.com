@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
<div class="mx-auto w-full max-w-md p-6 bg-white rounded-xl border border-[#e6d9f5]">
    <h2 class="text-lg font-semibold mb-4">Reset your password</h2>

    @if($errors->any())
        <div class="mb-4 rounded p-3 bg-[#FDE7E4] text-[#7D2E34]">{{ $errors->first() }}</div>
    @endif

    <form method="post" action="{{ route('password.update') }}" class="space-y-4">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}" />

        <div>
            <label class="block text-sm font-semibold text-[#4f433d]">Email</label>
            <input type="email" name="email" value="{{ $email ?? old('email') }}" required class="mt-2 w-full rounded-2xl border border-[#e6d9f5] px-3 py-2" />
        </div>

        <div>
            <label class="block text-sm font-semibold text-[#4f433d]">New password</label>
            <input type="password" name="password" required class="mt-2 w-full rounded-2xl border border-[#e6d9f5] px-3 py-2" />
        </div>

        <div>
            <label class="block text-sm font-semibold text-[#4f433d]">Confirm password</label>
            <input type="password" name="password_confirmation" required class="mt-2 w-full rounded-2xl border border-[#e6d9f5] px-3 py-2" />
        </div>

        <button type="submit" class="w-full rounded-full bg-gradient-to-r from-[#5b1e7e] to-[#8b2e9e] text-white py-2 font-semibold">Reset password</button>
    </form>

    <p class="mt-4 text-sm">Back to <a href="{{ route('login') }}" class="text-[#5b1e7e] font-semibold">sign in</a></p>
</div>
@endsection
