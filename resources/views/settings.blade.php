@extends('welcome')

@section('content')
<div class="min-h-screen bg-gray-900">
    <div class="py-10">
        <header>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                <h1 class="text-3xl font-bold text-white">Settings</h1>
                <div>
                    <a href="{{ route('messages') }}" class="mobile-btn-blue inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-150 ease-in-out">
                        <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                        </svg>
                        Messages
                    </a>
                </div>
            </div>
        </header>
        <main>
            <!-- Add username meta tag for JavaScript -->
            <meta name="username" content="{{ Auth::user()->username }}">
            
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="px-4 py-8 sm:px-0">
                    <div class="rounded-xl p-6 text-gray-300 relative overflow-hidden">
                        <!-- Background decorative elements -->
                        <div class="absolute inset-0 overflow-hidden pointer-events-none">
                            <div class="absolute -top-10 -left-10 w-40 h-40 bg-blue-600 rounded-full filter blur-3xl opacity-10"></div>
                            <div class="absolute top-60 -right-10 w-40 h-40 bg-blue-800 rounded-full filter blur-3xl opacity-10"></div>
                            <div class="absolute -bottom-10 left-1/3 w-40 h-40 bg-blue-700 rounded-full filter blur-3xl opacity-10"></div>
                        </div>
                        
                        <div class="bg-gradient-to-br from-gray-800 to-gray-900 p-6 rounded-xl shadow-lg mb-6 border border-gray-700 relative z-10">
                            <h2 class="text-xl font-semibold text-white mb-3">Welcome, {{ Auth::user()->username }}</h2>
                            <p class="text-gray-400 mb-6">Your secure communication hub is ready. All messages are end-to-end encrypted and only readable by you and your intended recipients.</p>
                            
                            <div id="key-status-app" class="mb-8"></div>
                            
                            <!-- Privacy Settings -->
                            <div class="mt-8 border-t border-gray-700 pt-6">
                                <h3 class="text-lg font-medium text-white mb-4">Privacy Settings</h3>
                                
                                <form method="POST" action="{{ route('settings.update-visibility') }}" class="space-y-5">
                                    @csrf
                                    <div class="bg-gray-800 bg-opacity-50 p-4 rounded-md border border-gray-700">
                                        <div class="flex items-center">
                                            <input 
                                                type="checkbox" 
                                                id="is_hidden" 
                                                name="is_hidden" 
                                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-600 rounded bg-gray-700"
                                                {{ Auth::user()->is_hidden ? 'checked' : '' }}
                                            >
                                            <label for="is_hidden" class="ml-3 block text-sm font-medium text-gray-300">
                                                Hide me from the new messages list
                                            </label>
                                        </div>
                                        <p class="text-xs text-gray-400 ml-7 mt-2">
                                            When enabled, other users won't see you in the list of available users when starting a new conversation. 
                                            You'll still receive messages from users who already know your username.
                                        </p>
                                    </div>
                                    
                                    <div>
                                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-150 ease-in-out">
                                            <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Save Privacy Settings
                                        </button>
                                    </div>
                                    
                                    @if(session('visibility_status'))
                                        <div class="p-4 bg-green-900 bg-opacity-50 text-green-100 rounded-md border border-green-800 mt-4">
                                            <div class="flex">
                                                <svg class="h-5 w-5 text-green-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span>{{ session('visibility_status') }}</span>
                                            </div>
                                        </div>
                                    @endif
                                </form>
                            </div>
                            
                            <!-- Data & Privacy Section -->
                            <div class="mt-8 border-t border-gray-700 pt-6">
                                <h3 class="text-lg font-medium text-white mb-4">Data & Privacy</h3>
                                
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:items-start">
                                    <!-- Data Export Card -->
                                    <div class="bg-gray-800 bg-opacity-50 p-4 rounded-md border border-gray-700">
                                        <div>
                                            <h4 class="text-md font-medium text-white mb-2">Download Your Data</h4>
                                            <p class="text-gray-300 mb-3">Download a copy of your personal data including your user information, public keys, and message history.</p>
                                        </div>
                                        
                                        <div>
                                            <a href="{{ route('settings.export-data') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-150 ease-in-out">
                                                <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                Download My Data (JSON)
                                            </a>
                                            <p class="text-xs text-gray-400 mt-2">The downloaded file contains all your user data in JSON format.</p>
                                        </div>
                                    </div>
                                    
                                    <!-- Account Deletion Card -->
                                    <div class="bg-red-900 bg-opacity-20 p-4 rounded-md border border-red-800 self-start">
                                        <div id="account-app"></div>
                                        <h4 class="text-md font-medium text-white mb-2">Delete Account</h4>
                                        <p class="text-red-200 mb-4">Permanently delete your account and all your data. This action cannot be undone.</p>
                                        
                                        <details class="text-gray-300">
                                            <summary class="cursor-pointer text-red-300 hover:text-red-200 font-medium mb-2">I want to delete my account</summary>
                                            
                                            <div class="mt-4 bg-gray-800 bg-opacity-70 p-4 rounded-md border border-gray-700">
                                                <form method="POST" action="{{ route('account.delete') }}" class="space-y-4">
                                                    @csrf
                                                    <div>
                                                        <label for="password" class="block text-sm font-medium text-gray-300 mb-1">Confirm your password</label>
                                                        <input 
                                                            type="password" 
                                                            id="password" 
                                                            name="password" 
                                                            required
                                                            class="mt-1 focus:ring-red-500 focus:border-red-500 block w-full shadow-sm sm:text-sm border-gray-600 rounded-md bg-gray-700 text-white"
                                                            placeholder="Enter your password to confirm"
                                                        >
                                                        @error('password')
                                                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    
                                                    <div class="flex items-center">
                                                        <input 
                                                            type="checkbox" 
                                                            id="confirm_delete" 
                                                            name="confirm_delete" 
                                                            required
                                                            
                                                        >
                                                        <label for="confirm_delete" class="ml-3 block text-sm font-medium text-gray-300">
                                                            I understand this will delete all my data and cannot be undone
                                                        </label>
                                                    </div>
                                                    
                                                    <div>
                                                        <button 
                                                            type="submit" 
                                                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-150 ease-in-out"
                                                            onclick="return confirm('Are you absolutely sure you want to delete your account? This action CANNOT be undone.');"
                                                        >
                                                            <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                            </svg>
                                                            Permanently Delete My Account
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </details>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-8 border-t border-gray-700 pt-6 flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                                <a href="{{ route('messages') }}" class="mobile-btn-blue inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-150 ease-in-out">
                                    <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                                    </svg>
                                    Go to Messages
                                </a>
                                <form method="POST" action="{{ route('logout') }}" class="inline">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center justify-center w-full sm:w-auto px-4 py-2 border border-gray-600 text-sm font-medium rounded-md text-gray-300 bg-gray-800 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-150 ease-in-out">
                                        <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                        Sign Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection