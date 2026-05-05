@extends('layouts.app')

@section('title', 'Register')

@push('styles')
<style>
    header, .bg-\[#f8f6ff\] {
        display: none !important;
    }
    main {
        padding-top: 2rem !important;
        background: linear-gradient(to bottom, #faf5ff, white, #fff5f9);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
@endpush

@section('content')
<div class="mx-auto max-w-xl rounded-[2rem] border border-[#e6d9f5] bg-white p-10 shadow-[0_20px_50px_-30px_rgba(0,0,0,0.14)]">
    <div class="text-center mb-8">
        <img src="{{ asset('images/fekasdark.png') }}" alt="Feka Clothing" class="mx-auto h-16 w-auto mb-4">
        <p class="text-sm uppercase tracking-[0.35em] text-[#6b4b8a]">Create your account</p>
        <h1 class="mt-3 text-3xl font-semibold text-[#1b1b18]">Welcome to Fekas Clothing Outlet</h1>
    </div>

    @if($errors->any())
        <div class="mb-6 rounded-3xl border border-[#E6B4B0] bg-[#FDE7E4] p-4 text-sm text-[#7D2E34]">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="post" action="{{ route('register.store') }}" class="space-y-6">
        @csrf
        <div>
            <label class="block text-sm font-semibold text-[#4f433d]">Name</label>
            <input type="text" name="name" value="{{ old('name') }}" required class="mt-3 w-full rounded-3xl border border-[#e6d9f5] bg-[#faf5ff] px-4 py-3 text-sm outline-none focus:border-[#5b1e7e] focus:ring-4 focus:ring-[#5b1e7e]/10" />
        </div>
        <div>
            <label class="block text-sm font-semibold text-[#4f433d]">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required class="mt-3 w-full rounded-3xl border border-[#e6d9f5] bg-[#faf5ff] px-4 py-3 text-sm outline-none focus:border-[#5b1e7e] focus:ring-4 focus:ring-[#5b1e7e]/10" />
        </div>
        <div class="grid gap-4 lg:grid-cols-2">
            <div>
                <label class="block text-sm font-semibold text-[#4f433d]">Password</label>
                <input type="password" name="password" required class="mt-3 w-full rounded-3xl border border-[#e6d9f5] bg-[#faf5ff] px-4 py-3 text-sm outline-none focus:border-[#5b1e7e] focus:ring-4 focus:ring-[#5b1e7e]/10" />
            </div>
            <div>
                <label class="block text-sm font-semibold text-[#4f433d]">Confirm password</label>
                <input type="password" name="password_confirmation" required class="mt-3 w-full rounded-3xl border border-[#e6d9f5] bg-[#faf5ff] px-4 py-3 text-sm outline-none focus:border-[#5b1e7e] focus:ring-4 focus:ring-[#5b1e7e]/10" />
            </div>
        </div>
        <button type="submit" class="w-full rounded-full bg-gradient-to-r from-[#5b1e7e] to-[#8b2e9e] px-6 py-4 text-sm font-semibold uppercase tracking-[0.2em] text-white transition hover:shadow-lg">Register</button>
    </form>

    <p class="mt-8 text-center text-sm text-[#6e625d]">Already have an account? <a href="{{ route('login') }}" class="font-semibold text-[#5b1e7e] hover:text-[#e91e8c]">Sign in</a></p>
</div>
@endsection
