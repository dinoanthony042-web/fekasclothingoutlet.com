@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
<div class="max-w-2xl rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">← Back to users</a>
        <h1 class="mt-2 text-2xl font-semibold text-gray-900">Edit {{ $user->name }}</h1>
    </div>

    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="mt-1 w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200" />
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="mt-1 w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200" />
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Role</label>
            <select name="role" class="mt-1 w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>User</option>
                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">Save changes</button>
            <a href="{{ route('admin.users.index') }}" class="rounded-xl border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">Cancel</a>
        </div>
    </form>
</div>
@endsection
