@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="mx-auto max-w-xl rounded-[2rem] border border-[#E7DDD4] bg-white p-10 shadow-[0_20px_50px_-30px_rgba(0,0,0,0.14)]">
    <div class="mb-8">
        <p class="text-sm uppercase tracking-[0.35em] text-[#8c7d74]">Create your account</p>
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
            <input type="text" name="name" value="{{ old('name') }}" required class="mt-3 w-full rounded-3xl border border-[#e4dad1] bg-[#f9f4f0] px-4 py-3 text-sm outline-none" />
        </div>
        <div>
            <label class="block text-sm font-semibold text-[#4f433d]">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required class="mt-3 w-full rounded-3xl border border-[#e4dad1] bg-[#f9f4f0] px-4 py-3 text-sm outline-none" />
        </div>
        <div class="grid gap-4 lg:grid-cols-2">
            <div>
                <label class="block text-sm font-semibold text-[#4f433d]">Password</label>
                <input type="password" name="password" required class="mt-3 w-full rounded-3xl border border-[#e4dad1] bg-[#f9f4f0] px-4 py-3 text-sm outline-none" />
            </div>
            <div>
                <label class="block text-sm font-semibold text-[#4f433d]">Confirm password</label>
                <input type="password" name="password_confirmation" required class="mt-3 w-full rounded-3xl border border-[#e4dad1] bg-[#f9f4f0] px-4 py-3 text-sm outline-none" />
            </div>
        </div>
        <button type="submit" class="w-full rounded-full bg-[#1b1b18] px-6 py-4 text-sm font-semibold uppercase tracking-[0.2em] text-white transition hover:bg-[#403c39]">Register</button>
    </form>

    <p class="mt-8 text-center text-sm text-[#6e625d]">Already have an account? <a href="{{ route('login') }}" class="font-semibold text-[#1b1b18] hover:text-[#7b6c65]">Sign in</a></p>
</div>
@endsection
