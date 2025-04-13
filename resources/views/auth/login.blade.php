@extends('welcome')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-900 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-white">
                Sign in to your account
            </h2>
            <p class="mt-2 text-center text-sm text-gray-400">
                Or
                <a href="{{ route('register') }}" class="font-medium text-blue-500 hover:text-blue-400">
                    create a new account
                </a>
            </p>
        </div>
        <div id="login-app"></div>
    </div>
</div>
@endsection