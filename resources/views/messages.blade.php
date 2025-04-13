@extends('welcome')

@section('content')
<div class="min-h-screen bg-gray-900">
    <div>
        <main>
            <!-- Add username meta tag for JavaScript -->
            <meta name="username" content="{{ Auth::user()->username }}">
            <meta name="user-id" content="{{ Auth::id() }}">
            
            <div class="sm:px-6 lg:px-8">
                <div class="px-4 py-8 sm:px-0">
                    <div id="messaging-app" class="border-4 border-gray-800 border-dashed rounded-lg h-[calc(100vh-200px)] min-h-[500px]">
                        <!-- Vue.js messaging app will be mounted here -->
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

@push('scripts')
<!-- No need to initialize cryptoContext here as it will be loaded by the Messaging component -->
@endpush
@endsection