@extends('layouts.mobile')

@section('content')
<div class="h-full w-full bg-gray-900">
    <!-- Add username and user-id meta tags for JavaScript -->
    <meta name="username" content="{{ Auth::user()->username }}">
    <meta name="user-id" content="{{ Auth::id() }}">
    
    <div id="mobile-messaging-app" class="h-full w-full">
        <!-- Vue.js mobile messaging app will be mounted here -->
    </div>
</div>

@push('scripts')
@endpush
@endsection