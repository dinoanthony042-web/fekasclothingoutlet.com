@extends('layouts.app')

@section('title', 'Login')

@push('styles')
<style>
    header, .bg-\[#f8f6ff\] {
        display: none !important;
    }
    main {
        padding-top: 2rem !important;
    }
</style>
@endpush

@section('content')
<div class="mx-auto max-w-xl rounded-[2rem] border border-[#E7DDD4] bg-white p-10 shadow-[0_20px_50px_-30px_rgba(0,0,0,0.14)]">
    <div class="mb-8">
        <p class="text-sm uppercase tracking-[0.35em] text-[#8c7d74]">Welcome back</p>
        <h1 class="mt-3 text-3xl font-semibold text-[#1b1b18]">Login to your account</h1>
    </div>

    @if($errors->any())
        <div class="mb-6 rounded-3xl border border-[#E6B4B0] bg-[#FDE7E4] p-4 text-sm text-[#7D2E34]">
            {{ $errors->first() }}
        </div>
    @endif

    <form id="login-form" method="post" action="{{ route('login.store') }}" class="space-y-6">
        @csrf
        <input type="hidden" name="guest_cart" id="guest-cart-input" />
        <div>
            <label class="block text-sm font-semibold text-[#4f433d]">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required class="mt-3 w-full rounded-3xl border border-[#e4dad1] bg-[#f9f4f0] px-4 py-3 text-sm outline-none" />
        </div>
        <div>
            <label class="block text-sm font-semibold text-[#4f433d]">Password</label>
            <input type="password" name="password" required class="mt-3 w-full rounded-3xl border border-[#e4dad1] bg-[#f9f4f0] px-4 py-3 text-sm outline-none" />
        </div>
        <div class="flex items-center justify-between text-sm text-[#6e625d]">
            <label class="inline-flex items-center gap-2">
                <input type="checkbox" name="remember" class="h-4 w-4 rounded border-[#d8c5b8] text-[#1b1b18] focus:ring-[#1b1b18]" />
                Remember me
            </label>
            <a href="#" class="font-semibold text-[#1b1b18] hover:text-[#7b6c65]">Forgot password?</a>
        </div>
        <button type="submit" class="w-full rounded-full bg-[#1b1b18] px-6 py-4 text-sm font-semibold uppercase tracking-[0.2em] text-white transition hover:bg-[#403c39]">Login</button>
    </form>

    <p class="mt-8 text-center text-sm text-[#6e625d]">New here? <a href="{{ route('register') }}" class="font-semibold text-[#1b1b18] hover:text-[#7b6c65]">Create an account</a></p>
</div>
@endsection




@push('scripts')
<script>
    document.getElementById('login-form').addEventListener('submit', function(e) {
        const guestCart = localStorage.getItem('guest_cart') || '[]';
        document.getElementById('guest-cart-input').value = guestCart;
    });
</script>
@endpush

