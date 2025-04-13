@extends('welcome')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-4xl mx-auto bg-gray-800 rounded-lg shadow-lg overflow-hidden">
        <div class="px-6 py-8">
            <h1 class="text-3xl font-bold text-white mb-6">Terms of Service</h1>
            
            <div class="prose prose-lg prose-invert max-w-none text-gray-300">
                <p class="mb-4">
                    Last updated: March 31, 2025
                </p>
                
                <h2 class="text-xl font-semibold text-white mt-8 mb-4">1. Introduction</h2>
                <p>
                    Welcome to PrivMsg ("we," "our," or "us"). These Terms of Service ("Terms") govern your access to and use of our secure end-to-end encrypted messaging service.
                </p>
                <p class="mt-3">
                    By accessing or using our Service, you agree to be bound by these Terms. If you disagree with any part of the Terms, you may not access the Service.
                </p>
                
                <h2 class="text-xl font-semibold text-white mt-8 mb-4">2. Transparency and Privacy Commitment</h2>
                <p>
                    PrivMsg is built on the principles of full transparency and strong privacy. Our commitment includes:
                </p>
                <ul class="list-disc list-inside mt-2 space-y-1">
                    <li>End-to-end encryption with 2048-bit RSA keys</li>
                    <li>No access to message contents or private keys</li>
                    <li>Minimal data collection – only what is strictly necessary</li>
                    <li>Complete user control over encryption keys and data</li>
                </ul>
                <p class="mt-3">
                    Your messages are encrypted on your device before transmission and can only be decrypted by the intended recipient. We do not collect or store any personally identifiable information beyond your username.
                </p>
                
                <h2 class="text-xl font-semibold text-white mt-8 mb-4">3. Account Creation and Security</h2>
                <p>
                    To use PrivMsg, you only need to provide a unique username and password. We do not collect emails, phone numbers, or other personal identifiers.
                </p>
                <p class="mt-3">
                    You are responsible for maintaining the confidentiality of your credentials and must notify us of any unauthorized access to your account.
                </p>
                
                <h2 class="text-xl font-semibold text-white mt-8 mb-4">4. Encryption Key Management</h2>
                <p>
                    PrivMsg enforces strict encryption policies:
                </p>
                <ul class="list-disc list-inside mt-2 space-y-1">
                    <li>Your private key is never stored on our servers.</li>
                    <li>We only store your public key to allow message encryption.</li>
                    <li>You are responsible for backing up and securing your private key.</li>
                    <li>If you lose your private key, we cannot recover your messages.</li>
                </ul>
                
                <h2 class="text-xl font-semibold text-white mt-8 mb-4">5. Legal Compliance</h2>
                <p>
                    While we prioritize user privacy, we comply with applicable legal obligations. However:
                </p>
                <ul class="list-disc list-inside mt-2 space-y-1">
                    <li>We collect minimal user data.</li>
                    <li>We do not store or have access to message content.</li>
                    <li>We do not track user activities.</li>
                </ul>
                <p class="mt-3">
                    Any lawful request for data will be constrained by our minimal data collection and encryption-first architecture.
                </p>
                
                <h2 class="text-xl font-semibold text-white mt-8 mb-4">6. Acceptable Use</h2>
                <p>
                    You agree not to use the Service for any prohibited activities, including:
                </p>
                <ul class="list-disc list-inside mt-2 space-y-1">
                    <li>Illegal or fraudulent activities</li>
                    <li>Unauthorized security testing or penetration testing</li>
                    <li>Distributing malware or engaging in cyber attacks</li>
                    <li>Spamming or unauthorized data collection</li>
                </ul>
                
                <h2 class="text-xl font-semibold text-white mt-8 mb-4">7. Service Availability</h2>
                <p>
                    While we aim to provide a reliable service, we do not guarantee uninterrupted availability. We reserve the right to modify, suspend, or discontinue the Service at any time.
                </p>
                
                <h2 class="text-xl font-semibold text-white mt-8 mb-4">8. Termination</h2>
                <p>
                    We may terminate or suspend your account without notice if you violate these Terms. Upon termination, you will lose access to the Service.
                </p>
                
                <h2 class="text-xl font-semibold text-white mt-8 mb-4">9. Limitation of Liability</h2>
                <p>
                    PrivMsg and its affiliates shall not be liable for any indirect or consequential damages, including loss of data, privacy, or profits resulting from the use or inability to use the Service.
                </p>
                
                <h2 class="text-xl font-semibold text-white mt-8 mb-4">10. Changes to These Terms</h2>
                <p>
                    We reserve the right to modify these Terms. We will provide notice of material changes at least 30 days before they take effect. Continued use of the Service constitutes acceptance of the revised Terms.
                </p>
                
                <h2 class="text-xl font-semibold text-white mt-8 mb-4">11. Contact</h2>
                <p>
                    If you have any questions about these Terms, please contact us at <a href="mailto:privmsg@benegedeniz.com" class="text-blue-400 hover:text-blue-300">privmsg@benegedeniz.com</a>.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
