<!DOCTYPE html>
<html lang="en" class="h-full overflow-hidden">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PrivMsg - Military-Grade Encrypted Messaging</title>
    <meta name="description" content="Secure, end-to-end encrypted messaging with 2048-bit RSA encryption. Your privacy is our priority.">
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Custom Styles -->
    <style>
        html, body {
            background-color: #0f1117;
            color: #e2e8f0;
            height: 100%;
            position: fixed;
            overflow: hidden;
            width: 100%;
            overscroll-behavior: none;
            touch-action: manipulation;
        }
        
        /* Custom scrollbar for webkit browsers */
        ::-webkit-scrollbar {
            width: 4px;
            height: 4px;
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
        
        /* Handle iOS safe areas */
        @supports(padding: max(0px)) {
            body {
                padding-top: env(safe-area-inset-top);
                padding-bottom: env(safe-area-inset-bottom);
                padding-left: env(safe-area-inset-left);
                padding-right: env(safe-area-inset-right);
            }
        }
    </style>
</head>
<body class="h-full overflow-hidden">
    <!-- No Navigation Bar -->

    <!-- Page Content -->
    <main class="h-full overflow-hidden">
        @yield('content')
    </main>

    <!-- No Footer Here -->

    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Prevent bounce effect on iOS
            document.addEventListener('touchmove', function(e) {
                if(e.target.closest('.scrollable')) return;
                e.preventDefault();
            }, { passive: false });
            
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