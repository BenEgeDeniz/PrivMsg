@extends('welcome')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-900 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-white">
                Create a new account
            </h2>
            <p class="mt-2 text-center text-sm text-gray-400">
                Or
                <a href="{{ route('login') }}" class="font-medium text-blue-500 hover:text-blue-400">
                    sign in to your existing account
                </a>
            </p>
        </div>
        <div id="register-app"></div>
    </div>
</div>

@push('scripts')
<script>
    // This will be initialized in the Vue component
    window.cryptoKeys = {
        privateKey: null,
        publicKey: null,
        exportedPublicKey: null
    };
</script>
@endpush
@endsection