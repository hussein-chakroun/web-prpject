@extends('layouts.master')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex flex-col space-y-6">
    @if (
    Auth::check() && 
    (\App\Models\Admin::where('user_id', Auth::id())->exists() || 
    \App\Models\SuperAdmin::where('user_id', Auth::id())->exists())
)
            <div class="p-6 bg-white rounded-lg shadow-md border border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Admin Panel</h2>
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-900 text-white rounded-md hover:bg-gray-800 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                    </svg>
                    Go to Admin Panel
                </a>
            </div>
        @endif

        <div class="p-6 bg-white rounded-lg shadow-md border border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Update Email</h2>
            <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
                @csrf
                @method('PATCH')
                <input type="hidden" name="type" value="email">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                </div>
                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-gray-900 text-white rounded-md hover:bg-gray-800 transition-colors">
                    Update Email
                </button>
            </form>
        </div>

        <div class="p-6 bg-white rounded-lg shadow-md border border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Update Password</h2>
            <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
                @csrf
                @method('PATCH')
                <input type="hidden" name="type" value="password">
                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                    <input type="password" name="current_password" id="current_password" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                    <input type="password" name="password" id="password" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                </div>
                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-gray-900 text-white rounded-md hover:bg-gray-800 transition-colors">
                    Update Password
                </button>
            </form>
        </div>

        <div class="p-6 bg-white rounded-lg shadow-md border border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Track Orders</h2>
            <a href="{{ route('user.orders') }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-gray-900 text-white rounded-md hover:bg-gray-800 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                </svg>
                Check Orders
            </a>
        </div>

        <div class="p-6 bg-white rounded-lg shadow-md border border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Logout</h2>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 001 1h12a1 1 0 001-1V4a1 1 0 00-1-1H3zm11 4a1 1 0 10-2 0v4a1 1 0 102 0V7zm-3 1a1 1 0 10-2 0v3a1 1 0 102 0V8zM8 9a1 1 0 00-2 0v3a1 1 0 102 0V9z" clip-rule="evenodd" />
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="mt-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mt-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
@endsection
