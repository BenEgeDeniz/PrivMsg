<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PrivMsg - Military-Grade Encrypted Messaging</title>
    <meta name="description" content="Secure, end-to-end encrypted messaging with 2048-bit RSA encryption. Your privacy is our priority.">
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Custom Styles -->
    <style>
        body {
            background-color: #0f1117;
            color: #e2e8f0;
        }
        
        /* Custom scrollbar for webkit browsers */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #1a202c;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #2d3748;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #4a5568;
        }
        
        /* Fix for inputs to ensure proper styling on all devices */
        input[type="text"], 
        input[type="password"], 
        input[type="email"],
        textarea {
            background-color: #1f2937 !important;
            background-image: none !important;
        }
        
        /* Cross-browser button background fix */
        button, .btn, a.btn, [type="button"], [type="submit"] {
            background-color: inherit;
        }
        
        /* Fix for gradient buttons in all browsers */
        .bg-gradient-to-r {
            background-color: var(--tw-gradient-from, #2563eb) !important;
        }
        
        /* Navbar fixes */
        nav.bg-gradient-to-r {
            background-color: #111827 !important; /* fallback */
            background-image: linear-gradient(to right, #111827, #1f2937) !important;
        }
        
        footer.bg-gradient-to-r {
            background-color: #111827 !important; /* fallback */
            background-image: linear-gradient(to right, #111827, #1f2937) !important;
        }
        
        /* User dropdown menu fix */
        .bg-gradient-to-br {
            background-color: #2563eb !important; /* fallback for blue gradient */
            background-image: linear-gradient(to bottom right, #3b82f6, #1d4ed8) !important;
        }
        
        .from-gray-800.to-gray-900 {
            background-color: #1f2937 !important; /* fallback */
            background-image: linear-gradient(to bottom right, #1f2937, #111827) !important;
        }
        
        /* Mobile menu fix */
        #mobile-menu a.bg-gradient-to-r {
            background-color: #111827 !important;
            background-image: linear-gradient(to right, #1f2937, #111827) !important;
        }
        
        /* Standard button colors */
        button[type="submit"], 
        .bg-blue-600, 
        .from-blue-600, 
        .bg-gradient-to-r.from-blue-600 {
            background-color: #2563eb !important;
            background-image: linear-gradient(to right, #2563eb, #1d4ed8) !important;
        }
        
        .bg-red-600, 
        .from-red-600, 
        .bg-gradient-to-r.from-red-600 {
            background-color: #dc2626 !important;
            background-image: linear-gradient(to right, #dc2626, #b91c1c) !important;
        }
        
        .bg-green-600, 
        .from-green-600, 
        .bg-gradient-to-r.from-green-600 {
            background-color: #059669 !important;
            background-image: linear-gradient(to right, #059669, #047857) !important;
        }
        
        /* Mobile button fixes */
        @media (max-width: 640px) {
            /* Reset all the overly broad selectors */
            .btn-blue-gradient,
            button[type="submit"],
            a[href].bg-gradient-to-r,
            .inline-flex.items-center[href],
            .block.w-full.text-left,
            .block.px-3.py-2 {
                background-image: none;
                --tw-gradient-from: initial;
                --tw-gradient-to: initial;
                --tw-gradient-stops: initial;
            }
            
            /* Properly target only nav buttons */
            nav .w-full.inline-flex.justify-center.items-center.px-4.py-2.border.border-transparent {
                background-color: #1f2937;
            }
            
            /* For form submit buttons */
            form button[type="submit"]:not(.text-left):not(.bg-gray-800) {
                background-color: #2563eb !important;
                background-image: linear-gradient(to right, #2563eb, #1d4ed8) !important;
            }
            
            /* Keep sign out button dark */
            form button[type="submit"].bg-gray-800 {
                background-color: #1f2937 !important;
                background-image: none !important;
                border-color: rgba(75, 85, 99, 0.5) !important;
            }
            
            /* Specific fixes for mobile nav buttons */
            #mobile-menu a {
                background-image: none !important;
                background-color: transparent !important;
                border-color: transparent !important;
                box-shadow: none !important;
            }
            
            #mobile-menu a:hover {
                background-color: rgba(55, 65, 81, 0.5) !important;
            }
            
            /* Home is active - more subtle styling */
            #mobile-menu a:first-child {
                background-color: rgba(31, 41, 55, 0.7) !important;
                border: 1px solid rgba(75, 85, 99, 0.4) !important;
            }
            
            /* Keep proper styling for the sign in/up buttons - more subtle */
            .mobile-btn-gray {
                background-color: rgba(31, 41, 55, 0.8) !important;
                background-image: none !important;
                border: 1px solid rgba(75, 85, 99, 0.5) !important;
            }
            
            .mobile-btn-blue {
                background-color: rgba(37, 99, 235, 0.8) !important;
                background-image: none !important;
                border: 1px solid rgba(59, 130, 246, 0.5) !important;
            }
            
            /* Fix for message buttons in settings page */
            a[href*="messages"] {
                background-color: #2563eb !important;
                background-image: linear-gradient(to right, #2563eb, #1d4ed8) !important;
                color: white !important;
            }
            
            /* Fix for KeyStatus component buttons */
            .bg-gradient-to-r.from-blue-600.to-blue-700 {
                background-color: #2563eb !important;
                background-image: linear-gradient(to right, #2563eb, #1d4ed8) !important;
            }
            
            .bg-gradient-to-r.from-red-600.to-red-700 {
                background-color: #dc2626 !important;
                background-image: linear-gradient(to right, #dc2626, #b91c1c) !important;
            }
            
            /* Fix for Generate Fresh Keys button */
            .bg-gradient-to-r.from-green-600.to-green-700 {
                background-color: #059669 !important;
                background-image: linear-gradient(to right, #059669, #047857) !important;
            }
        }
    </style>
</head>
<body class="min-h-screen">
    <!-- Navigation -->
    <nav class="bg-gradient-to-r from-gray-900 to-gray-800 border-b border-gray-700 shadow-md">
        <div class="container mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="/" class="flex items-center">
                            <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            <span class="ml-2 text-xl font-bold text-white">PrivMsg</span>
                        </a>
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:items-center sm:space-x-4">
                        <a href="/" class="px-3 py-2 text-sm font-medium text-gray-300 hover:text-white hover:border-b-2 hover:border-blue-500 transition-all duration-150">Home</a>
                        
                        @auth
                        <a href="{{ route('messages') }}" class="px-3 py-2 text-sm font-medium text-gray-300 hover:text-white hover:border-b-2 hover:border-blue-500 transition-all duration-150">Messages</a>
                        <a href="{{ route('settings') }}" class="px-3 py-2 text-sm font-medium text-gray-300 hover:text-white hover:border-b-2 hover:border-blue-500 transition-all duration-150">Settings</a>
                        @endauth
                        
                    </div>
                </div>
                <div class="hidden sm:ml-6 sm:flex sm:items-center">
                    @guest
                    <a href="{{ route('login') }}" class="mobile-btn-gray inline-flex items-center px-4 py-2 border border-gray-600 text-sm font-medium rounded-md text-white bg-gray-800 hover:bg-gray-700 transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Sign in
                    </a>
                    <a href="{{ route('register') }}" class="mobile-btn-blue ml-3 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 transition-all duration-150 shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Sign up
                    </a>
                    @else
                    <!-- Profile dropdown -->
                    <div class="ml-3 relative">
                        <div>
                            <button type="button" class="flex text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-blue-500 transition-all duration-150" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                <span class="sr-only">Open user menu</span>
                                <div class="h-8 w-8 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white shadow-md">
                                    {{ substr(Auth::user()->username ?? 'User', 0, 1) }}
                                </div>
                            </button>
                        </div>
                        
                        <!-- Dropdown menu -->
                        <div class="hidden origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-gradient-to-br from-gray-800 to-gray-900 ring-1 ring-black ring-opacity-5 focus:outline-none z-50 border border-gray-700" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1" id="user-dropdown">
                            <a href="{{ route('settings') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white transition-colors duration-150" role="menuitem">Settings</a>
                            <div class="border-t border-gray-700 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white transition-colors duration-150" role="menuitem">Sign out</button>
                            </form>
                        </div>
                    </div>
                    @endguest
                </div>
                
                <!-- Mobile menu button -->
                <div class="-mr-2 flex items-center sm:hidden">
                    <button type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500 transition-all duration-150" aria-controls="mobile-menu" aria-expanded="false" id="mobile-menu-button">
                        <span class="sr-only">Open main menu</span>
                        <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu, show/hide based on menu state. -->
        <div class="hidden sm:hidden" id="mobile-menu">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="/" class="block px-3 py-2 rounded-md text-base font-medium text-white bg-gradient-to-r from-gray-800 to-gray-900 border border-gray-700 shadow-sm">Home</a>
                
                @auth
                <a href="{{ route('messages') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-white hover:bg-gray-700 transition-colors duration-150">Messages</a>
                <a href="{{ route('settings') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-white hover:bg-gray-700 transition-colors duration-150">Settings</a>
                @endauth
                
            </div>
            
            @auth
            <div class="pt-4 pb-3 border-t border-gray-700">
                <div class="flex items-center px-5">
                    <div class="flex-shrink-0">
                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white shadow-md">
                            {{ substr(Auth::user()->username ?? 'User', 0, 1) }}
                        </div>
                    </div>
                    <div class="ml-3">
                        <div class="text-base font-medium text-white">{{ Auth::user()->username ?? 'undefined' }}</div>
                    </div>
                </div>
                <div class="mt-3 px-2 space-y-1">
                    <a href="{{ route('settings') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-400 hover:text-white hover:bg-gray-700 transition-colors duration-150">Settings</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-gray-400 hover:text-white hover:bg-gray-700 transition-colors duration-150">Sign out</button>
                    </form>
                </div>
            </div>
            @else
            <div class="pt-4 pb-3 border-t border-gray-700 px-5 flex flex-col space-y-2">
                <a href="{{ route('login') }}" class="mobile-btn-gray w-full inline-flex justify-center items-center px-4 py-2 border border-gray-600 text-base font-medium rounded-md text-white bg-gray-800 hover:bg-gray-700 transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Sign in
                </a>
                <a href="{{ route('register') }}" class="mobile-btn-blue btn-blue-gradient w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-base font-medium rounded-md text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 shadow-sm transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Sign up
                </a>
            </div>
            @endif
        </div>
    </nav>

    <!-- Page Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gradient-to-r from-gray-900 to-gray-800 border-t border-gray-700 py-8">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="flex items-center mb-4 md:mb-0">
                    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    <span class="ml-2 text-lg font-bold text-white">PrivMsg</span>
                </div>
                
                <div class="flex space-x-6 mb-4 md:mb-0">
                    <a href="https://github.com/BenEgeDeniz/PrivMsg" class="text-gray-400 hover:text-white transition-colors duration-150">
                        <span class="sr-only">GitHub</span>
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd"></path>
                        </svg>
                    </a>
                </div>
                
                <div class="flex space-x-6">
                    <a href="{{ route('transparency') }}" class="text-sm text-gray-400 hover:text-white hover:underline transition-colors duration-150">Transparency</a>
                    <a href="{{ route('privacy') }}" class="text-sm text-gray-400 hover:text-white hover:underline transition-colors duration-150">Privacy Policy</a>
                    <a href="{{ route('terms') }}" class="text-sm text-gray-400 hover:text-white hover:underline transition-colors duration-150">Terms of Service</a>
                </div>
            </div>
            
            <div class="mt-8 border-t border-gray-700 pt-8 flex flex-col md:flex-row justify-between items-center">
                <div class="text-sm text-gray-400">
                    &copy; 2025 PrivMsg. All rights reserved. | PrivMsg is a project of BenEgeDeniz.
                </div>
                <div class="mt-4 md:mt-0 flex items-center">
                    <div class="flex items-center text-sm text-gray-400">
                        <div class="bg-gray-800 rounded-md py-1 px-3 border border-gray-700 shadow-sm flex items-center">
                            <svg class="w-4 h-4 mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                            End-to-End Encrypted
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile menu toggle
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            
            mobileMenuButton.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });
            
            // User dropdown toggle
            const userMenuButton = document.getElementById('user-menu-button');
            const userDropdown = document.getElementById('user-dropdown');
            
            if (userMenuButton) {
                userMenuButton.addEventListener('click', function() {
                    userDropdown.classList.toggle('hidden');
                });
                
                // Close the dropdown when clicking outside
                document.addEventListener('click', function(event) {
                    if (!userMenuButton.contains(event.target) && !userDropdown.contains(event.target)) {
                        userDropdown.classList.add('hidden');
                    }
                });
            }
        });
    </script>
    
    <!-- App JS Bundle -->
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <!-- Fallback to direct script inclusion when Vite manifest is not available -->
        <script src="{{ asset('js/app.js') }}" defer></script>
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @endif
    
    <!-- Stacked scripts for specific sections -->
    @stack('scripts')
</body>
</html>
