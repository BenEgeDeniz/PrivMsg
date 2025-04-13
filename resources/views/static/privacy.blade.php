@extends('welcome')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-4xl mx-auto bg-gray-800 rounded-lg shadow-lg overflow-hidden">
        <div class="px-6 py-8">
            <h1 class="text-3xl font-bold text-white mb-6">Privacy Policy</h1>
            
            <div class="prose prose-lg prose-invert max-w-none text-gray-300">
                <p class="mb-4">
                    Last updated: March 31, 2025
                </p>
                
                <h2 class="text-xl font-semibold text-white mt-8 mb-4">1. Introduction</h2>
                <p>
                    At PrivMsg, we prioritize your privacy and security above all else. This Privacy Policy outlines our approach to data collection and protection when you use our secure messaging service.
                </p>
                <p class="mt-3">
                    Our philosophy is simple: we only collect the absolute minimum data required for the service to function.
                </p>
                
                <h2 class="text-xl font-semibold text-white mt-8 mb-4">2. Data We Collect</h2>
                <p>
                    Based on our database structure, we collect and store only:
                </p>
                <ul class="list-disc list-inside mt-2 space-y-1">
                    <li><strong>Username</strong>: We do not require your real name, email address, or other personal details</li>
                    <li><strong>Public key</strong>: Your public encryption key is stored to enable other users to send you encrypted messages</li>
                    <li><strong>Encrypted messages</strong>: Messages are stored in encrypted form - we cannot read their contents</li>
                    <li><strong>Message metadata</strong>: Limited to sender ID, recipient ID, timestamp, and read status</li>
                    <li><strong>Session data</strong>: Basic information required for authentication (IP address, user agent)</li>
                </ul>
                <p class="mt-3">
                    <strong>What we do NOT collect or store:</strong>
                </p>
                <ul class="list-disc list-inside mt-2 space-y-1">
                    <li>Private encryption keys</li>
                    <li>Unencrypted message content</li>
                    <li>Your real name, email address, or contact information</li>
                    <li>Your location data (beyond IP address for session authentication)</li>
                    <li>Device information (beyond user agent for session authentication)</li>
                    <li>Any other personal identifying information</li>
                </ul>
                
                <h2 class="text-xl font-semibold text-white mt-8 mb-4">3. End-to-End Encryption</h2>
                <p>
                    Our service employs 2048-bit RSA encryption for all messages. This means:
                </p>
                <ul class="list-disc list-inside mt-2 space-y-1">
                    <li>Messages are encrypted on your device before transmission</li>
                    <li>Only the intended recipient can decrypt messages</li>
                    <li>Your private key never leaves your device</li>
                    <li>We have no technical ability to decrypt your messages</li>
                </ul>
                
                <h2 class="text-xl font-semibold text-white mt-8 mb-4">4. How We Use Your Data</h2>
                <p>
                    The limited data we collect is used exclusively for:
                </p>
                <ul class="list-disc list-inside mt-2 space-y-1">
                    <li>Authenticating your identity to the service</li>
                    <li>Facilitating message delivery between users</li>
                    <li>Maintaining basic service functionality</li>
                </ul>
                <p class="mt-3">
                    We do not analyze your data for marketing purposes, sell your information to third parties, or build behavioral profiles.
                </p>
                
                <h2 class="text-xl font-semibold text-white mt-8 mb-4">5. Data Retention</h2>
                <p>
                    Messages are stored until you choose to delete them. If you delete your account, all your messages and account information will be permanently removed from our systems.
                </p>
                
                <h2 class="text-xl font-semibold text-white mt-8 mb-4">6. Legal Compliance</h2>
                <p>
                    While we are committed to user privacy, we will comply with valid legal requests from authorities. However:
                </p>
                <ul class="list-disc list-inside mt-2 space-y-1">
                    <li>We can only provide the limited data we actually possess</li>
                    <li>Due to our technical design, we cannot access message content</li>
                    <li>We collect minimal identifying information about our users</li>
                </ul>
                <p class="mt-3">
                    If legally compelled, we will provide information to appropriate legal organizations, though this will be limited to what we actually have access to.
                </p>
                
                <h2 class="text-xl font-semibold text-white mt-8 mb-4">7. Security Measures</h2>
                <p>
                    We employ industry-standard security measures to protect our systems and your data, including:
                </p>
                <ul class="list-disc list-inside mt-2 space-y-1">
                    <li>2048-bit RSA encryption for messages</li>
                    <li>Secure password hashing</li>
                    <li>Regular security audits and updates</li>
                    <li>Protection against common web vulnerabilities</li>
                </ul>
                
                <h2 class="text-xl font-semibold text-white mt-8 mb-4">8. Changes to This Privacy Policy</h2>
                <p>
                    We may update our Privacy Policy from time to time. We will notify users of any changes by posting the new policy with an updated revision date.
                </p>
                <p class="mt-3">
                    Your continued use of the service after changes to the Privacy Policy constitutes acceptance of those changes.
                </p>
                
                <h2 class="text-xl font-semibold text-white mt-8 mb-4">9. User Rights</h2>
                <p>
                    You have the right to:
                </p>
                <ul class="list-disc list-inside mt-2 space-y-1">
                    <li>Access the data we hold about you</li>
                    <li>Delete your account and associated data</li>
                    <li>Export your data (though this is limited to your public key as we cannot decrypt your messages)</li>
                </ul>
                
                <h2 class="text-xl font-semibold text-white mt-8 mb-4">10. Contact Us</h2>
                <p>
                    If you have questions about this Privacy Policy or your data, please contact us at <a href="mailto:privmsg@benegedeniz.com" class="text-blue-400 hover:text-blue-300">privmsg@benegedeniz.com</a>.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection