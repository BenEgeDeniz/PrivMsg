@extends('welcome')

@section('content')
<div class="bg-gradient-to-b from-gray-900 to-gray-800">
    <!-- Account Deletion Success Message -->
    @if(session('status'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
        <div class="p-4 bg-green-900 bg-opacity-50 text-green-100 rounded-md border border-green-800 mb-4">
            <div class="flex">
                <svg class="h-5 w-5 text-green-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>{{ session('status') }}</span>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Hero Section -->
    <div class="relative overflow-hidden">
        <div class="max-w-7xl mx-auto">
            <div class="relative z-10 pb-8 sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
                <div class="pt-10 mx-auto max-w-7xl px-4 sm:pt-12 sm:px-6 md:pt-16 lg:pt-20 lg:px-8 xl:pt-28">
                    <div class="sm:text-center lg:text-left">
                        <span class="inline-block px-3 py-1 text-xs font-semibold text-blue-400 bg-blue-900 bg-opacity-50 rounded-full mb-2">Secure • Private • Simple</span>
                        <h1 class="text-4xl tracking-tight font-extrabold text-white sm:text-5xl md:text-6xl">
                            <span class="block">Military-Grade</span>
                            <span class="block text-blue-500">Encrypted Messaging</span>
                        </h1>
                        <p class="mt-3 text-base text-gray-300 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                            PrivMsg provides end-to-end encrypted communication with 2048-bit RSA encryption. Your messages are only readable by you and your intended recipient.
                        </p>
                        <div class="mt-4 flex items-center text-gray-400 text-sm sm:justify-center lg:justify-start">
                            <svg class="h-5 w-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                            Open-source cryptography - security you can verify
                        </div>
                        @guest
                        <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                            <div class="rounded-md shadow">
                                <a href="{{ route('register') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 md:py-4 md:text-lg md:px-10">
                                    Create Account
                                </a>
                            </div>
                            <div class="mt-3 sm:mt-0 sm:ml-3">
                                <a href="{{ route('login') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-blue-600 bg-gray-800 hover:bg-gray-700 md:py-4 md:text-lg md:px-10">
                                    Sign In
                                </a>
                            </div>
                        </div>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
        <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2 hidden lg:block">
            <div class="h-56 w-full sm:h-72 md:h-96 lg:w-full lg:h-full p-8 flex items-center justify-center relative overflow-hidden">
                <!-- Abstract encryption visualization - enhanced version -->
                <div class="relative w-full max-w-lg">
                    <!-- Encryption blocks visualization with better animation -->
                    <div class="absolute top-0 left-0 w-40 h-40 bg-blue-500 rounded-xl opacity-20 animate-pulse" style="animation-duration: 3s; filter: blur(2px);"></div>
                    <div class="absolute top-20 left-20 w-36 h-36 bg-blue-600 rounded-xl opacity-30 animate-pulse" style="animation-duration: 5s; filter: blur(1px);"></div>
                    <div class="absolute top-40 left-10 w-32 h-32 bg-blue-700 rounded-xl opacity-40 animate-pulse" style="animation-duration: 4s;"></div>
                    
                    
                    
                    <!-- Binary overlay -->
                    <div class="relative z-10 h-full w-full flex items-center justify-center">
                        <div class="text-blue-400 opacity-90 text-xs font-mono leading-tight">
                            <div>01000101 01101110 01100011 01110010 01111001 01110000 01110100</div>
                            <div>01010000 01110010 01101001 01110110 01100001 01100011 01111001</div>
                            <div>01010011 01100101 01100011 01110101 01110010 01100101 01100100</div>
                            <div>01000100 01100101 01100011 01110010 01111001 01110000 01110100</div>
                            <div>01010010 01010011 01000001 00101101 00110100 00110000 00111001</div>
                            <div>01000001 01000101 01010011 00101101 00110010 00110101 00110110</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Trust Indicators Section -->
    <div class="bg-gray-800 py-8 border-y border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-600 text-white">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-white">Zero Knowledge</h3>
                        <p class="mt-2 text-base text-gray-400">
                            We never have access to your private keys or unencrypted messages - guaranteeing your privacy.
                        </p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-600 text-white">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-white">Open Standards</h3>
                        <p class="mt-2 text-base text-gray-400">
                            Our implementation uses standard cryptographic libraries that follow industry best practices.
                        </p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-600 text-white">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-white">Simple to Use</h3>
                        <p class="mt-2 text-base text-gray-400">
                            Advanced encryption made easy - no technical knowledge required to send secure messages.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="py-16 bg-gradient-to-b from-gray-800 to-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:text-center">
                <h2 class="text-base text-blue-400 font-semibold tracking-wide uppercase">Key Features</h2>
                <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-white sm:text-4xl">
                    Private Communication Made Simple
                </p>
                <p class="mt-4 max-w-2xl text-xl text-gray-300 lg:mx-auto">
                    Our platform uses modern encryption to protect your messages.
                </p>
            </div>

            <div class="mt-16">
                <dl class="space-y-12 md:space-y-0 md:grid md:grid-cols-2 md:gap-x-8 md:gap-y-16">
                    <div class="relative bg-gray-800 bg-opacity-50 p-6 rounded-xl border border-gray-700 transform transition-all hover:translate-y-[-5px] hover:shadow-lg">
                        <dt>
                            <div class="absolute -top-6 left-6 flex items-center justify-center h-14 w-14 rounded-md bg-blue-600 text-white shadow-lg">
                                <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <p class="ml-16 pt-1 text-lg leading-6 font-medium text-white">End-to-End Encryption</p>
                        </dt>
                        <dd class="mt-5 text-base text-gray-400">
                            All messages are encrypted on your device before transmission and can only be decrypted by the intended recipient. We can't read your messages.
                        </dd>
                    </div>

                    <div class="relative bg-gray-800 bg-opacity-50 p-6 rounded-xl border border-gray-700 transform transition-all hover:translate-y-[-5px] hover:shadow-lg">
                        <dt>
                            <div class="absolute -top-6 left-6 flex items-center justify-center h-14 w-14 rounded-md bg-blue-600 text-white shadow-lg">
                                <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </div>
                            <p class="ml-16 pt-1 text-lg leading-6 font-medium text-white">Strong Encryption</p>
                        </dt>
                        <dd class="mt-5 text-base text-gray-400">
                            We use 2048-bit RSA encryption for key exchange and AES-256 for message encryption, providing strong security for your communications.
                        </dd>
                    </div>

                    <div class="relative bg-gray-800 bg-opacity-50 p-6 rounded-xl border border-gray-700 transform transition-all hover:translate-y-[-5px] hover:shadow-lg">
                        <dt>
                            <div class="absolute -top-6 left-6 flex items-center justify-center h-14 w-14 rounded-md bg-blue-600 text-white shadow-lg">
                                <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <p class="ml-16 pt-1 text-lg leading-6 font-medium text-white">Ephemeral Messaging</p>
                        </dt>
                        <dd class="mt-5 text-base text-gray-400">
                            Set messages to self-destruct after being read, leaving no trace of sensitive communications.
                        </dd>
                    </div>

                    <div class="relative bg-gray-800 bg-opacity-50 p-6 rounded-xl border border-gray-700 transform transition-all hover:translate-y-[-5px] hover:shadow-lg">
                        <dt>
                            <div class="absolute -top-6 left-6 flex items-center justify-center h-14 w-14 rounded-md bg-blue-600 text-white shadow-lg">
                                <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>
                                </svg>
                            </div>
                            <p class="ml-16 pt-1 text-lg leading-6 font-medium text-white">Digital Signatures</p>
                        </dt>
                        <dd class="mt-5 text-base text-gray-400">
                            Every message is digitally signed, ensuring authenticity and preventing message tampering or impersonation.
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    <!-- How It Works Section -->
    <div class="py-20 bg-gradient-to-b from-gray-900 to-gray-800 relative overflow-hidden">
        <!-- Background decorative elements -->
        <div class="absolute inset-0 overflow-hidden opacity-10">
            <div class="absolute -top-40 -left-40 w-80 h-80 bg-blue-600 rounded-full filter blur-3xl opacity-30"></div>
            <div class="absolute top-60 -right-40 w-80 h-80 bg-blue-800 rounded-full filter blur-3xl opacity-30"></div>
        </div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="lg:text-center mb-16">
                <h2 class="text-base text-blue-400 font-semibold tracking-wide uppercase">How It Works</h2>
                <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-white sm:text-4xl">
                    The Technical Process
                </p>
                <p class="mt-4 max-w-2xl text-xl text-gray-300 lg:mx-auto">
                    Understanding how your messages are secured through encryption.
                </p>
            </div>

            <div class="relative">
                <!-- Connector Line -->
                <div class="hidden lg:block absolute left-1/2 top-0 bottom-0 w-0.5 bg-gray-700 transform -translate-x-1/2"></div>
                
                <div class="space-y-20">
                    <!-- Step 1 -->
                    <div class="relative">
                        <!-- Step Number (Desktop) -->
                        <div class="hidden lg:flex absolute left-1/2 transform -translate-x-1/2 -translate-y-1/2 items-center justify-center w-12 h-12 rounded-full border-4 border-gray-700 bg-gray-900 z-10">
                            <div class="flex-shrink-0 h-8 w-8 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold">1</div>
                        </div>
                        
                        <div class="lg:grid lg:grid-cols-2 lg:gap-12 items-center">
                            <div class="lg:pr-10">
                                <!-- Step Number (Mobile) -->
                                <div class="flex lg:hidden items-center mb-4">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold">1</div>
                                    <h3 class="ml-4 text-xl leading-6 font-medium text-white">Key Generation</h3>
                                </div>
                                
                                <!-- Title (Desktop) -->
                                <h3 class="hidden lg:block text-2xl leading-6 font-medium text-white mb-4">Key Generation</h3>
                                
                                <div class="mt-2 text-base text-gray-400 space-y-4">
                                    <p>
                                        When you create an account, we generate a 2048-bit RSA key pair on your device. This provides strong encryption for your messages.
                                    </p>
                                    <p>
                                        Your private key never leaves your device and is secured with your password. The public key is shared with your contacts for message encryption.
                                    </p>
                                    <div class="text-sm bg-gray-800 bg-opacity-50 p-3 border border-gray-700 rounded-md mt-3">
                                        <span class="font-medium text-blue-400">Security Note:</span> Your private key is stored encrypted on your device and is never transmitted across the internet.
                                    </div>
                                </div>
                            </div>
                            <div class="mt-10 lg:mt-0 bg-gray-800 bg-opacity-50 rounded-xl p-8 border border-gray-700">
                                <div class="flex flex-col items-center">
                                    <div class="inline-flex items-center justify-center p-4 bg-blue-900 bg-opacity-30 rounded-full mb-5">
                                        <svg class="h-12 w-12 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                        </svg>
                                    </div>
                                    <div class="text-center space-y-2">
                                        <h4 class="text-lg font-medium text-white">RSA 2048-bit Key Pair</h4>
                                        <p class="text-gray-400 text-sm">Generates two mathematically linked keys:</p>
                                        <div class="flex justify-center space-x-4 mt-4">
                                            <div class="bg-gray-900 px-4 py-2 rounded-md border border-gray-700">
                                                <div class="text-xs text-gray-400">Private Key</div>
                                                <div class="text-blue-500 font-mono text-xs mt-1">
                                                    -----BEGIN RSA KEY-----<br/>
                                                    <span class="text-gray-500">Stays on your device</span><br/>
                                                    -----END RSA KEY-----
                                                </div>
                                            </div>
                                            <div class="bg-gray-900 px-4 py-2 rounded-md border border-gray-700">
                                                <div class="text-xs text-gray-400">Public Key</div>
                                                <div class="text-green-500 font-mono text-xs mt-1">
                                                    -----BEGIN PUBLIC KEY-----<br/>
                                                    <span class="text-gray-500">Shared with contacts</span><br/>
                                                    -----END PUBLIC KEY-----
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="relative">
                        <!-- Step Number (Desktop) -->
                        <div class="hidden lg:flex absolute left-1/2 transform -translate-x-1/2 -translate-y-1/2 items-center justify-center w-12 h-12 rounded-full border-4 border-gray-700 bg-gray-900 z-10">
                            <div class="flex-shrink-0 h-8 w-8 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold">2</div>
                        </div>
                        
                        <div class="lg:grid lg:grid-cols-2 lg:gap-12 items-center">
                            <div class="order-2 lg:pl-10">
                                <!-- Step Number (Mobile) -->
                                <div class="flex lg:hidden items-center mb-4">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold">2</div>
                                    <h3 class="ml-4 text-xl leading-6 font-medium text-white">Message Encryption</h3>
                                </div>
                                
                                <!-- Title (Desktop) -->
                                <h3 class="hidden lg:block text-2xl leading-6 font-medium text-white mb-4">Message Encryption</h3>
                                
                                <div class="mt-2 text-base text-gray-400 space-y-4">
                                    <p>
                                        When you send a message, we use a hybrid encryption approach. First, your message is encrypted with AES-256 using a randomly generated key. This is the same encryption standard used for top-secret government documents.
                                    </p>
                                    <p>
                                        This unique AES key is then itself encrypted with the recipient's public RSA key. Once encrypted, only the matching private key can unlock the message, ensuring only the intended recipient can read your communication.
                                    </p>
                                    <div class="text-sm bg-gray-800 bg-opacity-50 p-3 border border-gray-700 rounded-md mt-3">
                                        <span class="font-medium text-blue-400">Security Note:</span> Hybrid encryption combines the speed of symmetric encryption (AES) with the security of asymmetric encryption (RSA).
                                    </div>
                                </div>
                            </div>
                            <div class="order-1 mt-10 lg:mt-0 bg-gray-800 bg-opacity-50 rounded-xl p-8 border border-gray-700">
                                <div class="flex flex-col items-center">
                                    <div class="inline-flex items-center justify-center p-4 bg-blue-900 bg-opacity-30 rounded-full mb-5">
                                        <svg class="h-12 w-12 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                        </svg>
                                    </div>
                                    <div class="text-center">
                                        <h4 class="text-lg font-medium text-white">Hybrid Encryption Process</h4>
                                        <div class="flex flex-col items-center mt-4 relative">
                                            <!-- Message flow diagram -->
                                            <div class="bg-gray-900 px-4 py-3 rounded-md border border-gray-700 mb-6 w-3/4">
                                                <div class="text-sm text-white">Your Message</div>
                                                <div class="text-xs text-gray-400 mt-1">Plaintext</div>
                                            </div>
                                            <svg class="h-6 w-6 text-blue-500 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                            </svg>
                                            <div class="bg-gray-900 px-4 py-3 rounded-md border border-gray-700 mb-6 w-3/4 border-blue-800">
                                                <div class="text-sm text-blue-400">AES-256 Encryption</div>
                                                <div class="text-xs text-gray-400 mt-1">With random key</div>
                                            </div>
                                            <svg class="h-6 w-6 text-blue-500 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                            </svg>
                                            <div class="bg-gray-900 px-4 py-3 rounded-md border border-gray-700 w-3/4 border-green-800">
                                                <div class="text-sm text-green-400">RSA-2048 Encryption</div>
                                                <div class="text-xs text-gray-400 mt-1">Of AES key with recipient's public key</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="relative">
                        <!-- Step Number (Desktop) -->
                        <div class="hidden lg:flex absolute left-1/2 transform -translate-x-1/2 -translate-y-1/2 items-center justify-center w-12 h-12 rounded-full border-4 border-gray-700 bg-gray-900 z-10">
                            <div class="flex-shrink-0 h-8 w-8 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold">3</div>
                        </div>
                        
                        <div class="lg:grid lg:grid-cols-2 lg:gap-12 items-center">
                            <div class="lg:pr-10">
                                <!-- Step Number (Mobile) -->
                                <div class="flex lg:hidden items-center mb-4">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold">3</div>
                                    <h3 class="ml-4 text-xl leading-6 font-medium text-white">Secure Transmission</h3>
                                </div>
                                
                                <!-- Title (Desktop) -->
                                <h3 class="hidden lg:block text-2xl leading-6 font-medium text-white mb-4">Secure Transmission</h3>
                                
                                <div class="mt-2 text-base text-gray-400 space-y-4">
                                    <p>
                                        The encrypted message travels through our servers using TLS (Transport Layer Security) for additional protection during transit. This creates multiple layers of security for your communications.
                                    </p>
                                    <p>
                                        Even if our servers were compromised, your messages remain encrypted end-to-end. We cannot read your messages because we never have access to your private keys, ensuring true zero-knowledge security.
                                    </p>
                                    <div class="text-sm bg-gray-800 bg-opacity-50 p-3 border border-gray-700 rounded-md mt-3">
                                        <span class="font-medium text-blue-400">Privacy Note:</span> We maintain minimal metadata and no message content, maximizing your privacy even beyond the encryption itself.
                                    </div>
                                </div>
                            </div>
                            <div class="mt-10 lg:mt-0 bg-gray-800 bg-opacity-50 rounded-xl p-8 border border-gray-700">
                                <div class="flex flex-col items-center">
                                    <div class="inline-flex items-center justify-center p-4 bg-blue-900 bg-opacity-30 rounded-full mb-5">
                                        <svg class="h-12 w-12 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                        </svg>
                                    </div>
                                    <div class="text-center">
                                        <h4 class="text-lg font-medium text-white">Layered Security Model</h4>
                                        <div class="mt-4 w-full max-w-md">
                                            <div class="relative py-8">
                                                <!-- Transmission animation -->
                                                <div class="flex items-center justify-between mb-2">
                                                    <div class="text-sm text-gray-300">Your Device</div>
                                                    <div class="text-sm text-gray-300">Recipient's Device</div>
                                                </div>
                                                <div class="h-14 w-full bg-gray-900 rounded-lg border border-gray-700 p-2 overflow-hidden">
                                                    <!-- Animated package traveling -->
                                                    <div class="relative w-full h-full">
                                                        <div class="absolute inset-y-0 left-0 bg-blue-900 bg-opacity-30 w-1/4 h-full rounded"></div>
                                                        <div class="absolute inset-y-0 left-1/4 bg-blue-800 bg-opacity-30 w-1/4 h-full rounded"></div>
                                                        <div class="absolute inset-y-0 left-2/4 bg-blue-700 bg-opacity-30 w-1/4 h-full rounded"></div>
                                                        <div class="absolute inset-y-0 left-3/4 bg-blue-600 bg-opacity-30 w-1/4 h-full rounded"></div>
                                                        
                                                        <!-- Data packet -->
                                                        <div class="absolute top-1/2 transform -translate-y-1/2 left-1/3 w-6 h-6 bg-blue-500 rounded-full"></div>
                                                        
                                                        <!-- TLS tunnel visualization -->
                                                        <div class="absolute inset-y-0 inset-x-0 border-2 border-dashed border-gray-600 rounded opacity-70"></div>
                                                    </div>
                                                </div>
                                                <div class="flex justify-between mt-2">
                                                    <div class="flex items-center">
                                                        <div class="w-2 h-2 rounded-full bg-blue-500 mr-1"></div>
                                                        <span class="text-xs text-gray-400">Encrypted Data</span>
                                                    </div>
                                                    <div class="flex items-center">
                                                        <div class="w-full h-0 border border-dashed border-gray-600 w-4 mr-1"></div>
                                                        <span class="text-xs text-gray-400">TLS Tunnel</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 4 -->
                    <div class="relative">
                        <!-- Step Number (Desktop) -->
                        <div class="hidden lg:flex absolute left-1/2 transform -translate-x-1/2 -translate-y-1/2 items-center justify-center w-12 h-12 rounded-full border-4 border-gray-700 bg-gray-900 z-10">
                            <div class="flex-shrink-0 h-8 w-8 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold">4</div>
                        </div>
                        
                        <div class="lg:grid lg:grid-cols-2 lg:gap-12 items-center">
                            <div class="order-2 lg:pl-10">
                                <!-- Step Number (Mobile) -->
                                <div class="flex lg:hidden items-center mb-4">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold">4</div>
                                    <h3 class="ml-4 text-xl leading-6 font-medium text-white">Decryption</h3>
                                </div>
                                
                                <!-- Title (Desktop) -->
                                <h3 class="hidden lg:block text-2xl leading-6 font-medium text-white mb-4">Decryption</h3>
                                
                                <div class="mt-2 text-base text-gray-400 space-y-4">
                                    <p>
                                        When the recipient receives the message, their device first uses their private RSA key to decrypt the AES key. This process can only be performed with the correct private key, which only exists on their device.
                                    </p>
                                    <p>
                                        The decrypted AES key is then used to decrypt the actual message content. This entire decryption process happens seamlessly on the recipient's device without requiring any special actions.
                                    </p>
                                    <div class="text-sm bg-gray-800 bg-opacity-50 p-3 border border-gray-700 rounded-md mt-3">
                                        <span class="font-medium text-blue-400">Technical Note:</span> The decryption process also verifies the digital signature, ensuring the message was not tampered with during transmission.
                                    </div>
                                </div>
                            </div>
                            <div class="order-1 mt-10 lg:mt-0 bg-gray-800 bg-opacity-50 rounded-xl p-8 border border-gray-700">
                                <div class="flex flex-col items-center">
                                    <div class="inline-flex items-center justify-center p-4 bg-blue-900 bg-opacity-30 rounded-full mb-5">
                                        <svg class="h-12 w-12 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div class="text-center">
                                        <h4 class="text-lg font-medium text-white">Decryption Process</h4>
                                        <div class="flex flex-col items-center mt-4 relative">
                                            <!-- Reverse of the encryption process -->
                                            <div class="bg-gray-900 px-4 py-3 rounded-md border border-gray-700 mb-6 w-3/4 border-green-800">
                                                <div class="text-sm text-green-400">Encrypted Package</div>
                                                <div class="text-xs text-gray-400 mt-1">RSA-encrypted AES key + AES-encrypted message</div>
                                            </div>
                                            <svg class="h-6 w-6 text-blue-500 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                                            </svg>
                                            <div class="bg-gray-900 px-4 py-3 rounded-md border border-gray-700 mb-6 w-3/4 border-blue-800">
                                                <div class="text-sm text-blue-400">Private Key Decryption</div>
                                                <div class="text-xs text-gray-400 mt-1">Reveals the AES key</div>
                                            </div>
                                            <svg class="h-6 w-6 text-blue-500 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                                            </svg>
                                            <div class="bg-gray-900 px-4 py-3 rounded-md border border-gray-700 w-3/4">
                                                <div class="text-sm text-white">Original Message</div>
                                                <div class="text-xs text-gray-400 mt-1">Plaintext content</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Encryption Demo Section -->
    <div class="py-16 bg-gradient-to-b from-gray-800 to-gray-900 border-t border-gray-700">
        <div class="container mx-auto px-4 text-gray-300">
            <h1 class="text-3xl font-bold mb-6 text-center text-white">See Encryption in Action</h1>

            <p class="mb-8 text-lg text-center text-gray-400 max-w-2xl mx-auto">
                Try our interactive demo below to understand how your messages are secured using the Web Crypto API right in your browser!
            </p>
        
            <div class="max-w-4xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-800 border border-gray-700 shadow-md rounded px-8 pt-6 pb-8 h-full">
                        <h2 class="text-2xl font-semibold mb-4 text-white">Step 1: Your Message</h2>
                        <p class="mb-4 text-gray-400">Type a message below. This is your original, readable message (plaintext).</p>
                        <textarea id="plaintext-input" class="shadow appearance-none border border-gray-600 rounded w-full py-2 px-3 bg-gray-900 text-gray-100 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" rows="3" placeholder="Enter your secret message..."></textarea>
                    </div>
                
                    <div class="bg-gray-800 border border-gray-700 shadow-md rounded px-8 pt-6 pb-8 h-full">
                        <h2 class="text-2xl font-semibold mb-4 text-white">Step 2: Generate Keys</h2>
                        <p class="mb-4 text-gray-400">Every user has a unique pair of keys: a public key (sharable) and a private key (kept secret). Let's generate a pair for this demo.</p>
                        <button id="generate-keys-btn" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Generate Demo Keys
                        </button>
                        <div id="keys-display" class="mt-4 hidden">
                            <h3 class="text-xl font-semibold text-white">Public Key:</h3>
                            <pre id="public-key-output" class="bg-gray-900 border border-gray-600 p-3 rounded text-sm text-gray-200 overflow-x-auto whitespace-pre-wrap break-all"></pre>
                            <h3 class="text-xl font-semibold mt-4 text-white">Private Key: <span class="text-sm text-red-400">(Secret - Never share!)</span></h3>
                            <pre id="private-key-output" class="bg-gray-900 border border-gray-600 p-3 rounded text-sm text-gray-200 overflow-x-auto whitespace-pre-wrap break-all"></pre>
                            <p class="text-sm text-gray-500 mt-2">In a real scenario, your private key is stored securely and never shared.</p>
                        </div>
                    </div>
                
                    <div class="bg-gray-800 border border-gray-700 shadow-md rounded px-8 pt-6 pb-8 h-full">
                        <h2 class="text-2xl font-semibold mb-4 text-white">Step 3: Encryption</h2>
                        <p class="mb-4 text-gray-400">Now, we use the recipient's public key (in this demo, our generated public key) to encrypt your message. Only the corresponding private key can decrypt it.</p>
                        <button id="encrypt-btn" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50" disabled>
                            Encrypt Message
                        </button>
                        <div id="encrypted-display" class="mt-4 hidden">
                            <h3 class="text-xl font-semibold text-white">Encrypted Message (Ciphertext):</h3>
                            <pre id="ciphertext-output" class="bg-gray-900 border border-gray-600 p-3 rounded text-sm text-gray-200 overflow-x-auto whitespace-pre-wrap break-all"></pre>
                            <p class="text-sm text-gray-500 mt-2">This garbled text is what would be sent over the network. It's unreadable without the private key.</p>
                        </div>
                    </div>
                
                    <div class="bg-gray-800 border border-gray-700 shadow-md rounded px-8 pt-6 pb-8 h-full">
                        <h2 class="text-2xl font-semibold mb-4 text-white">Step 4: Decryption</h2>
                        <p class="mb-4 text-gray-400">Finally, the recipient uses their private key to decrypt the ciphertext and reveal the original message.</p>
                        <button id="decrypt-btn" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50" disabled>
                            Decrypt Message
                        </button>
                        <div id="decrypted-display" class="mt-4 hidden">
                            <h3 class="text-xl font-semibold text-white">Decrypted Message:</h3>
                            <pre id="decrypted-output" class="bg-gray-900 border border-gray-600 p-3 rounded text-sm text-gray-200"></pre>
                        </div>
                    </div>
                </div> <!-- Close grid -->
                
                <p class="text-center text-gray-500 mt-8">
                    This demonstration uses the <a href="https://developer.mozilla.org/en-US/docs/Web/API/SubtleCrypto" target="_blank" rel="noopener noreferrer" class="text-blue-400 hover:text-blue-300 hover:underline">Web Crypto API</a> available in your browser for cryptographic operations.
                </p>
            </div>
        </div>
    </div>

    @guest
    <!-- CTA Section -->
    <div class="bg-gradient-to-r from-blue-700 to-blue-600 relative overflow-hidden">
        <!-- Decorative elements -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-blue-400 rounded-full filter blur-3xl opacity-20"></div>
            <div class="absolute -bottom-20 -left-20 w-60 h-60 bg-blue-300 rounded-full filter blur-3xl opacity-10"></div>
        </div>
        
        <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:py-20 lg:px-8 relative z-10">
            <div class="lg:grid lg:grid-cols-2 lg:gap-8 items-center">
                <div>
                    <h2 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl">
                        <span class="block">Ready to try it out?</span>
                        <span class="block text-blue-200 mt-1">It's completely free.</span>
                    </h2>
                    <p class="mt-4 text-lg text-blue-100 max-w-md">
                        Create an account to send and receive encrypted messages. No payment or personal information required.
                    </p>
                </div>
                
                <div class="mt-10 lg:mt-0 flex justify-center">
                    <div class="bg-white bg-opacity-10 backdrop-filter backdrop-blur-lg rounded-xl p-8 border border-white border-opacity-20 shadow-xl max-w-md w-full">
                        <h3 class="text-xl font-medium text-white mb-6">Create your account</h3>
                        
                        <div class="space-y-6">
                            <div class="flex lg:mt-0 lg:flex-shrink-0">
                                <div class="inline-flex w-full rounded-md shadow">
                                    <a href="{{ route('register') }}" class="w-full inline-flex items-center justify-center px-5 py-4 border border-transparent text-base font-medium rounded-md text-blue-600 bg-white hover:bg-blue-50">
                                        Sign up
                                    </a>
                                </div>
                            </div>
                            <div class="inline-flex w-full rounded-md">
                                <a href="{{ route('login') }}" class="w-full inline-flex items-center justify-center px-5 py-4 border border-white border-opacity-30 text-base font-medium rounded-md text-white bg-transparent hover:bg-white hover:bg-opacity-10">
                                    Sign in
                                </a>
                            </div>
                            <p class="text-sm text-center text-blue-200 mt-4">
                                By signing up, you agree to our 
                                <a href="{{ route('terms') }}" class="text-white hover:text-blue-100">Terms of Service</a> and 
                                <a href="{{ route('privacy') }}" class="text-white hover:text-blue-100">Privacy Policy</a>.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endguest
</div>
@endsection

@push('scripts')
<script>
    // Ensure this code runs only if the demo elements exist on the page
    if (document.getElementById('generate-keys-btn')) {
        const plaintextInput = document.getElementById('plaintext-input');
        const generateKeysBtn = document.getElementById('generate-keys-btn');
        const encryptBtn = document.getElementById('encrypt-btn');
        const decryptBtn = document.getElementById('decrypt-btn');
        const keysDisplay = document.getElementById('keys-display');
        const publicKeyOutput = document.getElementById('public-key-output');
        const privateKeyOutput = document.getElementById('private-key-output');
        const encryptedDisplay = document.getElementById('encrypted-display');
        const ciphertextOutput = document.getElementById('ciphertext-output');
        const decryptedDisplay = document.getElementById('decrypted-display');
        const decryptedOutput = document.getElementById('decrypted-output');

        let keyPair = null;
        let ciphertextBuffer = null; // Store the ArrayBuffer directly

        // Helper function to convert ArrayBuffer to Base64 string
        function arrayBufferToBase64(buffer) {
            let binary = '';
            const bytes = new Uint8Array(buffer);
            const len = bytes.byteLength;
            for (let i = 0; i < len; i++) {
                binary += String.fromCharCode(bytes[i]);
            }
            return window.btoa(binary);
        }

        // Helper function to convert Base64 string to ArrayBuffer
        function base64ToArrayBuffer(base64) {
            const binary_string = window.atob(base64);
            const len = binary_string.length;
            const bytes = new Uint8Array(len);
            for (let i = 0; i < len; i++) {
                bytes[i] = binary_string.charCodeAt(i);
            }
            return bytes.buffer;
        }

        // Key generation logic
        generateKeysBtn.addEventListener('click', async () => {
            try {
                keyPair = await window.crypto.subtle.generateKey(
                    {
                        name: "RSA-OAEP",
                        modulusLength: 2048, // Standard length
                        publicExponent: new Uint8Array([0x01, 0x00, 0x01]), // 65537
                        hash: "SHA-256",
                    },
                    true, // whether the key is extractable (i.e. can be used in exportKey)
                    ["encrypt", "decrypt"] // can be "encrypt", "decrypt", "wrapKey", or "unwrapKey"
                );

                // Export keys to JWK format for display
                const publicKeyJwk = await window.crypto.subtle.exportKey("jwk", keyPair.publicKey);
                const privateKeyJwk = await window.crypto.subtle.exportKey("jwk", keyPair.privateKey);

                publicKeyOutput.textContent = JSON.stringify(publicKeyJwk, null, 2);
                // In a real app, NEVER display the private key like this.
                privateKeyOutput.textContent = JSON.stringify(privateKeyJwk, null, 2);

                keysDisplay.classList.remove('hidden');
                encryptBtn.disabled = false;
                decryptBtn.disabled = true; // Disable decrypt until something is encrypted
                encryptedDisplay.classList.add('hidden');
                decryptedDisplay.classList.add('hidden');
                ciphertextBuffer = null;

                console.log("Keys generated successfully.");

            } catch (error) {
                console.error("Key generation failed:", error);
                alert('Error generating keys. See console for details.');
            }
        });

        // Encryption logic
        encryptBtn.addEventListener('click', async () => {
            if (!keyPair || !plaintextInput.value) {
                alert('Please generate keys and enter a message first.');
                return;
            }

            try {
                const encodedPlaintext = new TextEncoder().encode(plaintextInput.value);

                ciphertextBuffer = await window.crypto.subtle.encrypt(
                    {
                        name: "RSA-OAEP"
                    },
                    keyPair.publicKey, // The public key to encrypt with
                    encodedPlaintext // Data to encrypt
                );

                ciphertextOutput.textContent = arrayBufferToBase64(ciphertextBuffer);
                encryptedDisplay.classList.remove('hidden');
                decryptBtn.disabled = false; // Enable decryption now
                decryptedDisplay.classList.add('hidden'); // Hide previous decryption result if any

                console.log("Encryption successful.");

            } catch (error) {
                console.error("Encryption failed:", error);
                alert('Error encrypting message. See console for details.');
            }
        });

        // Decryption logic
        decryptBtn.addEventListener('click', async () => {
            if (!keyPair || !ciphertextBuffer) {
                 alert('Please encrypt a message first.');
                return;
            }

            try {
                const decryptedBuffer = await window.crypto.subtle.decrypt(
                    {
                        name: "RSA-OAEP"
                    },
                    keyPair.privateKey, // The private key to decrypt with
                    ciphertextBuffer // Data to decrypt
                );

                decryptedOutput.textContent = new TextDecoder().decode(decryptedBuffer);
                decryptedDisplay.classList.remove('hidden');

                console.log("Decryption successful.");

            } catch (error) {
                console.error("Decryption failed:", error);
                 // Common issue: Trying to decrypt data not encrypted with the corresponding public key
                alert('Error decrypting message. Was the ciphertext tampered with? See console for details.');
            }
        });
    } // End of check for demo elements
</script>
@endpush