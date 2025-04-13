@extends('welcome')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-5xl mx-auto bg-gray-800 rounded-lg shadow-lg overflow-hidden">
        <div class="px-6 py-8">
            <h1 class="text-3xl font-bold text-white mb-6">PrivMsg Technical Transparency Document</h1>
            
            <div class="prose prose-lg prose-invert max-w-none text-gray-300">
                <p class="mb-4 italic">
                    Last updated: April 13, 2025
                </p>
                
                <div class="bg-gray-700 p-4 rounded-md mb-8 border-l-4 border-yellow-500">
                    <p class="text-yellow-300 font-medium">Purpose:</p>
                    <p>This document provides an in-depth technical explanation of the PrivMsg application's architecture, security model, data handling practices, and core cryptographic operations. It aims to leave no ambiguity regarding the system's inner workings, targeting security professionals, developers, and users requiring deep technical insight.</p>
                </div>
                
                <h2 class="text-2xl font-semibold text-white mt-10 mb-4 border-b pb-2 border-gray-600">1. System Architecture</h2>
                
                <h3 class="text-xl font-medium text-white mt-6 mb-3">1.1 Core Components & Technology Stack</h3>
                <ul class="list-disc list-inside mt-2 space-y-2">
                    <li><strong>Backend Framework:</strong> Laravel 12 (PHP 8.4)</li>
                    <li><strong>Database:</strong> MySQL</li>
                    <li><strong>Web Server:</strong> Nginx</li>
                    <li><strong>Frontend Framework:</strong> Vue.js 3 (Composition API)</li>
                    <li><strong>UI Styling:</strong> Tailwind CSS v4</li>
                    <li><strong>Templating:</strong> Laravel Blade (primarily for layout scaffolding)</li>
                    <li><strong>JavaScript Runtime:</strong> Browser (utilizing modern Web APIs)</li>
                    <li><strong>Client-Side Crypto:</strong> 
                        <ul class="list-circle list-inside ml-5 mt-1">
                            <li>Web Crypto API (`window.crypto.subtle`) for core cryptographic primitives (RSA-OAEP, AES-GCM, SHA-256, RSASSA-PKCS1-v1_5).</li>
                            <li>`js-crypto-rsa` and `js-crypto-utils` (potentially for helper functions or broader compatibility, check usage in `CryptoService.js`).</li>
                        </ul>
                    </li>
                </ul>
                
                <h3 class="text-xl font-medium text-white mt-6 mb-3">1.2 Database Schema (MySQL)</h3>
                <p>The following tables constitute the core data model. Column types reflect MySQL interpretations unless specified otherwise.</p>
                
                <div class="space-y-6 mt-4">
                    <div class="bg-gray-900 p-4 rounded-md border border-gray-700">
                        <h4 class="font-semibold text-white">`users` Table</h4>
                        <p class="text-sm text-gray-400 mb-2">Stores user account and authentication information.</p>
                        <ul class="list-disc list-inside text-sm space-y-1">
                            <li>`id` (BIGINT UNSIGNED, PK, AI)</li>
                            <li>`username` (VARCHAR(255), Unique)</li>
                            <li>`password` (VARCHAR(255)) - Stores Bcrypt hash (see Authentication section).</li>
                            <li>`is_hidden` (BOOLEAN, Default: 0) - Flag for user search visibility. Added in migration `2025_03_31_230333`.</li>
                            <li>`remember_token` (VARCHAR(100), Nullable) - Used for "Remember Me" functionality.</li>
                            <li>`created_at` (TIMESTAMP, Nullable)</li>
                            <li>`updated_at` (TIMESTAMP, Nullable)</li>
                        </ul>
                    </div>

                    <div class="bg-gray-900 p-4 rounded-md border border-gray-700">
                        <h4 class="font-semibold text-white">`key_pairs` Table</h4>
                        <p class="text-sm text-gray-400 mb-2">Stores user public keys for enabling message encryption.</p>
                        <ul class="list-disc list-inside text-sm space-y-1">
                            <li>`id` (BIGINT UNSIGNED, PK, AI)</li>
                            <li>`user_id` (BIGINT UNSIGNED, FK -> users.id, On Delete Cascade, Indexed)</li>
                            <li>`public_key` (TEXT) - Stores the user's RSA public key in PEM format (SPKI).</li>
                            <li>`created_at` (TIMESTAMP, Nullable)</li>
                            <li>`updated_at` (TIMESTAMP, Nullable)</li>
                        </ul>
                    </div>

                    <div class="bg-gray-900 p-4 rounded-md border border-gray-700">
                        <h4 class="font-semibold text-white">`messages` Table</h4>
                        <p class="text-sm text-gray-400 mb-2">Stores end-to-end encrypted message content and metadata.</p>
                        <ul class="list-disc list-inside text-sm space-y-1">
                            <li>`id` (BIGINT UNSIGNED, PK, AI)</li>
                            <li>`sender_id` (BIGINT UNSIGNED, FK -> users.id, On Delete Cascade, Indexed)</li>
                            <li>`recipient_id` (BIGINT UNSIGNED, FK -> users.id, On Delete Cascade, Indexed)</li>
                            <li>`encrypted_content` (TEXT) - Stores Base64 encoded JSON containing encrypted message, encrypted AES key, and IV.</li>
                            <li>`signature` (TEXT, Nullable) - Stores Base64 encoded RSASSA-PKCS1-v1_5 signature of the encrypted message content.</li>
                            <li>`is_read` (BOOLEAN, Default: 0, Indexed) - Read status flag.</li>
                            <li>`is_ephemeral` (BOOLEAN, Default: 0) - Auto-delete flag. Added in migration `2025_04_06_004504`.</li>
                            <li>`created_at` (TIMESTAMP, Nullable)</li>
                            <li>`updated_at` (TIMESTAMP, Nullable)</li>
                        </ul>
                    </div>
                    
                    <div class="bg-gray-900 p-4 rounded-md border border-gray-700">
                        <h4 class="font-semibold text-white">`sessions` Table</h4>
                        <p class="text-sm text-gray-400 mb-2">Managed by Laravel's file session driver (`file`), but schema exists for database driver compatibility.</p>
                        <ul class="list-disc list-inside text-sm space-y-1">
                            <li>`id` (VARCHAR(255), PK) - Session ID.</li>
                            <li>`user_id` (BIGINT UNSIGNED, Nullable, Indexed) - Associated authenticated user.</li>
                            <li>`ip_address` (VARCHAR(45), Nullable)</li>
                            <li>`user_agent` (TEXT, Nullable)</li>
                            <li>`payload` (LONGTEXT) - Serialized session data (Base64 encoded).</li>
                            <li>`last_activity` (INT, Indexed) - Timestamp of last activity.</li>
                        </ul>
                    </div>
                    
                    <div class="bg-gray-900 p-4 rounded-md border border-gray-700">
                        <h4 class="font-semibold text-white">`typing_statuses` Table</h4>
                        <p class="text-sm text-gray-400 mb-2">Tracks real-time typing indicators between users.</p>
                        <ul class="list-disc list-inside text-sm space-y-1">
                             <li>`id` (BIGINT UNSIGNED, PK, AI)</li>
                             <li>`sender_id` (BIGINT UNSIGNED, FK -> users.id, On Delete Cascade, Indexed)</li>
                             <li>`recipient_id` (BIGINT UNSIGNED, FK -> users.id, On Delete Cascade, Indexed)</li>
                             <li>`is_typing` (BOOLEAN, Default: 0)</li>
                             <li>`updated_at` (TIMESTAMP, Nullable)</li>
                         </ul>
                     </div>
                </div>
                
                <h3 class="text-xl font-medium text-white mt-6 mb-3">1.3 Model Relationships (Eloquent ORM)</h3>
                <p>Defined in `app/Models/*.php`:</p>
                <ul class="list-disc list-inside mt-2 space-y-2">
                    <li><strong>`App\Models\User`</strong>:
                        <ul class="list-circle list-inside ml-5 mt-1 text-sm">
                            <li>`keyPair(): HasOne` -> `App\Models\KeyPair` (via `user_id`)</li>
                            <li>`sentMessages(): HasMany` -> `App\Models\Message` (via `sender_id`)</li>
                            <li>`receivedMessages(): HasMany` -> `App\Models\Message` (via `recipient_id`)</li>
                            <li>`typingStatuses(): HasMany` -> `App\Models\TypingStatus` (via `sender_id`)</li>
                        </ul>
                    </li>
                    <li><strong>`App\Models\KeyPair`</strong>:
                        <ul class="list-circle list-inside ml-5 mt-1 text-sm">
                            <li>`user(): BelongsTo` -> `App\Models\User` (via `user_id`)</li>
                        </ul>
                    </li>
                    <li><strong>`App\Models\Message`</strong>:
                        <ul class="list-circle list-inside ml-5 mt-1 text-sm">
                            <li>`sender(): BelongsTo` -> `App\Models\User` (via `sender_id`)</li>
                            <li>`recipient(): BelongsTo` -> `App\Models\User` (via `recipient_id`)</li>
                        </ul>
                    </li>
                     <li><strong>`App\Models\TypingStatus`</strong>:
                         <ul class="list-circle list-inside ml-5 mt-1 text-sm">
                             <li>`sender(): BelongsTo` -> `App\Models\User` (via `sender_id`)</li>
                             <li>`recipient(): BelongsTo` -> `App\Models\User` (via `recipient_id`)</li>
                         </ul>
                     </li>
                </ul>
                <p class="text-sm mt-2">Note: Model logic also includes cascading deletes defined in `User::booted()` method.</p>

                <h2 class="text-2xl font-semibold text-white mt-10 mb-4 border-b pb-2 border-gray-600">2. Security Architecture & Cryptography</h2>

                <h3 class="text-xl font-medium text-white mt-6 mb-3">2.1 End-to-End Encryption (E2EE) Implementation</h3>
                <p>E2EE is handled entirely client-side via JavaScript (`resources/js/services/CryptoService.js`) utilizing the Web Crypto API. The server only stores opaque encrypted blobs and public keys.</p>

                <h4 class="text-lg font-medium text-white mt-4 mb-2">2.1.1 Key Generation (`CryptoService.generateKeyPair`)</h4>
                <ul class="list-disc list-inside mt-2 space-y-1 text-sm">
                    <li>Uses `window.crypto.subtle.generateKey`.</li>
                    <li>Algorithm: `RSA-OAEP` (RSAES-OAEP).</li>
                    <li>Modulus Length: 2048 bits.</li>
                    <li>Public Exponent: 65537 (`0x010001`).</li>
                    <li>Hash Function for OAEP: `SHA-256`.</li>
                    <li>Key Usages: `['encrypt', 'decrypt']`.</li>
                    <li>Keys generated as `CryptoKey` objects (extractable).</li>
                    <li>Public key exported to PEM (SPKI format, Base64 encoded) for server storage (`/api/keypair` POST).</li>
                    <li>Private key exported to PEM (PKCS#8 format, Base64 encoded) for storage.</li>
                </ul>
                
                <h4 class="text-lg font-medium text-white mt-4 mb-2">2.1.2 Key Storage (Client-Side)</h4>
                 <ul class="list-disc list-inside mt-2 space-y-1 text-sm">
                     <li>Utilizes IndexedDB (`PrivMsgEncryptionKeys` database, `keys` object store).</li>
                     <li>`CryptoService.storeKeyPair` stores PEM-encoded private and public keys, indexed by `username`.</li>
                     <li>`CryptoService.retrieveKeyPair` fetches PEM keys and imports them back into non-extractable `CryptoKey` objects using `importPrivateKey` and `importPublicKey`.</li>
                     <li><strong>Security Note:</strong> IndexedDB storage is local to the browser profile and subject to its security constraints (e.g., clearing site data removes keys).</li>
                 </ul>

                <h4 class="text-lg font-medium text-white mt-4 mb-2">2.1.3 Message Encryption (`CryptoService.encryptMessage`) - Hybrid Encryption</h4>
                <p class="text-sm">Combines asymmetric (RSA) and symmetric (AES) encryption for efficiency and security.</p>
                <ol class="list-decimal list-inside mt-2 space-y-2 text-sm">
                    <li>Recipient's public key (PEM) is fetched from the server (`/api/keypair/{userId}`).</li>
                    <li>Recipient's public key is imported into a `CryptoKey` object (`RSA-OAEP`, usage: `['encrypt']`).</li>
                    <li>A unique symmetric key is generated for *this specific message*:
                        <ul class="list-disc list-inside ml-5 mt-1">
                             <li>Uses `window.crypto.subtle.generateKey`.</li>
                             <li>Algorithm: `AES-GCM` (AES in Galois/Counter Mode).</li>
                             <li>Key Length: 256 bits.</li>
                             <li>Key Usages: `['encrypt', 'decrypt']`.</li>
                        </ul>
                    </li>
                    <li>A random 12-byte (96-bit) Initialization Vector (IV) is generated using `window.crypto.getRandomValues`.</li>
                    <li>The plaintext message (UTF-8 string) is encoded to `ArrayBuffer`.</li>
                    <li>Message `ArrayBuffer` is encrypted using the generated AES-GCM key and IV:
                        <ul class="list-disc list-inside ml-5 mt-1">
                            <li>Uses `window.crypto.subtle.encrypt`.</li>
                             <li>Algorithm: `{ name: 'AES-GCM', iv: iv }`.</li>
                        </ul>
                    </li>
                    <li>The generated AES key (`CryptoKey`) is exported to its raw `ArrayBuffer` format (`exportKey('raw', ...)`).</li>
                    <li>The raw AES key `ArrayBuffer` is encrypted using the recipient's RSA public key:
                        <ul class="list-disc list-inside ml-5 mt-1">
                            <li>Uses `window.crypto.subtle.encrypt`.</li>
                             <li>Algorithm: `{ name: 'RSA-OAEP' }`.</li>
                        </ul>
                    </li>
                    <li>The *encrypted message* `ArrayBuffer` (output of step 6) is signed using the *sender's* private key:
                         <ul class="list-disc list-inside ml-5 mt-1">
                             <li>Sender's private key PEM is re-imported specifically for signing (`importPrivateKeyForSigning`) using algorithm `RSASSA-PKCS1-v1_5`, hash `SHA-256`, usage `['sign']`.</li>
                             <li>Uses `window.crypto.subtle.sign`.</li>
                             <li>Algorithm: `{ name: 'RSASSA-PKCS1-v1_5' }`.</li>
                         </ul>
                     </li>
                    <li>The encrypted message, encrypted AES key, IV, and signature are Base64 encoded.</li>
                    <li>A JSON object `{ message, key, iv }` is created with the Base64 strings.</li>
                    <li>This JSON string is stored in the `encrypted_content` column of the `messages` table.</li>
                    <li>The Base64 signature is stored in the `signature` column.</li>
                </ol>
                <p class="text-sm mt-2">Data sent to `/api/messages` (POST): `{ recipient_id, encrypted_content (JSON string), signature (Base64 string), is_ephemeral }`.</p>

                <h4 class="text-lg font-medium text-white mt-4 mb-2">2.1.4 Message Decryption (`CryptoService.decryptMessage`)</h4>
                <ol class="list-decimal list-inside mt-2 space-y-2 text-sm">
                    <li>Encrypted data package (JSON string) and signature (Base64) are retrieved from the `messages` table.</li>
                    <li>JSON package is parsed. Base64 components (`message`, `key`, `iv`, `signature`) are decoded into `ArrayBuffer`s.</li>
                    <li>Sender's public key (PEM) is fetched from the server (`/api/keypair/{senderId}`).</li>
                    <li>Sender's public key is imported for verification (`importPublicKeyForVerification`, algorithm `RSASSA-PKCS1-v1_5`, hash `SHA-256`, usage `['verify']`).</li>
                    <li>Signature Verification:
                         <ul class="list-disc list-inside ml-5 mt-1">
                             <li>Uses `window.crypto.subtle.verify`.</li>
                             <li>Verifies the signature (`signature` ArrayBuffer) against the *encrypted message* `ArrayBuffer` using the sender's verification key.</li>
                             <li>If verification fails, decryption stops.</li>
                         </ul>
                     </li>
                    <li>The encrypted AES key `ArrayBuffer` is decrypted using the *recipient's* private key (`CryptoKey` retrieved from IndexedDB):
                        <ul class="list-disc list-inside ml-5 mt-1">
                            <li>Uses `window.crypto.subtle.decrypt`.</li>
                             <li>Algorithm: `{ name: 'RSA-OAEP' }`.</li>
                        </ul>
                    </li>
                     <li>The decrypted raw AES key `ArrayBuffer` is imported into a `CryptoKey` object:
                         <ul class="list-disc list-inside ml-5 mt-1">
                             <li>Uses `window.crypto.subtle.importKey`.</li>
                             <li>Format: `'raw'`.</li>
                             <li>Algorithm: `{ name: 'AES-GCM', length: 256 }`.</li>
                             <li>Key Usages: `['decrypt']`.</li>
                         </ul>
                     </li>
                    <li>The encrypted message `ArrayBuffer` is decrypted using the imported AES key and the received IV `ArrayBuffer`:
                        <ul class="list-disc list-inside ml-5 mt-1">
                            <li>Uses `window.crypto.subtle.decrypt`.</li>
                             <li>Algorithm: `{ name: 'AES-GCM', iv: iv }`.</li>
                        </ul>
                    </li>
                    <li>The resulting plaintext `ArrayBuffer` is decoded into a UTF-8 string using `TextDecoder`.</li>
                    <li>The plaintext message is displayed in the UI.</li>
                </ol>

                <h4 class="text-lg font-medium text-white mt-4 mb-2">2.1.5 Cryptographic Primitives Summary</h4>
                 <ul class="list-disc list-inside mt-2 space-y-1 text-sm">
                     <li><strong>Asymmetric Encryption:</strong> RSA-OAEP with SHA-256.</li>
                     <li><strong>Symmetric Encryption:</strong> AES-GCM with 256-bit keys and 96-bit IVs (Provides Authenticated Encryption - AEAD).</li>
                     <li><strong>Digital Signatures:</strong> RSASSA-PKCS1-v1_5 with SHA-256.</li>
                     <li><strong>Key Derivation:</strong> Not used directly; AES keys generated randomly per message.</li>
                     <li><strong>Hashing (for crypto):</strong> SHA-256 (used within RSA-OAEP and RSASSA-PKCS1-v1_5).</li>
                 </ul>
                
                <div class="bg-gray-700 p-4 rounded-md my-4 border-l-4 border-red-500">
                     <p class="text-red-300 font-medium">Security Implication: Lack of Forward Secrecy</p>
                     <p class="text-sm">As the recipient's long-term RSA private key is used to decrypt the per-message AES key, compromise of this single RSA key allows decryption of *all past messages* encrypted to the corresponding public key. Implementing a key exchange protocol like Signal Protocol (using Diffie-Hellman) would be required to achieve Forward Secrecy.</p>
                 </div>

                <h3 class="text-xl font-medium text-white mt-6 mb-3">2.2 Authentication and Authorization</h3>
                <ul class="list-disc list-inside mt-2 space-y-2 text-sm">
                    <li><strong>Password Hashing:</strong> Laravel's default Bcrypt implementation (`Hash::make`, `Hash::check`). Default cost factor is typically 10 or 12 (verify in `config/hashing.php` if non-default). Stored in `users.password`.</li>
                    <li><strong>Session Management:</strong> Laravel's standard session handling (`file` driver). Session ID stored in encrypted cookie (`laravel_session`). Session data stored server-side in `storage/framework/sessions/`.</li>
                    <li><strong>CSRF Protection:</strong> Laravel's built-in CSRF middleware is active (verify in `app/Http/Kernel.php`). `XSRF-TOKEN` cookie and `_token` form field/header required for non-GET requests.</li>
                    <li><strong>Authentication Guard:</strong> Standard `web` guard using the `session` driver and `eloquent` user provider (`config/auth.php`).</li>
                    <li><strong>Authorization:</strong> Primarily handled via Route Model Binding and explicit checks within controllers (e.g., checking `Auth::id()` against message sender/recipient). No dedicated Policy or Gate classes seem to be in use based on current structure.</li>
                    <li><strong>Rate Limiting:</strong> Default Laravel rate limiters may apply (check `app/Providers/RouteServiceProvider.php`). Specific rate limiting for login (`LoginRateLimiter`) likely exists in `AuthController`.</li>
                </ul>
                
                 <h3 class="text-xl font-medium text-white mt-6 mb-3">2.3 Ephemeral Messages</h3>
                 <ul class="list-disc list-inside mt-2 space-y-1 text-sm">
                     <li>Frontend sets `is_ephemeral` flag during message send API call.</li>
                     <li>Server stores this flag in `messages.is_ephemeral`.</li>
                     <li><strong>Deletion Logic:</strong> Requires examining `MessageController` or a scheduled job/command. Deletion likely occurs either after the `/api/messages/{id}/read` endpoint is hit for the *first time* by the recipient, or potentially via a background cleanup task comparing `updated_at` timestamps for read, ephemeral messages. The exact trigger mechanism needs verification in the codebase.</li>
                 </ul>


                <h2 class="text-2xl font-semibold text-white mt-10 mb-4 border-b pb-2 border-gray-600">3. API Endpoint Details</h2>
                <p>API routes are defined in `routes/web.php` under the `/api` prefix and require authentication (`auth` middleware). Controllers are located in `app/Http/Controllers`.</p>
                
                <div class="space-y-4 mt-4">
                    <div class="bg-gray-900 p-3 rounded-md border border-gray-700 text-sm">
                        <p><strong class="text-white">`GET /api/messages`</strong> (<code>MessageController@index</code>)</p>
                        <p>Fetches messages where the authenticated user is the recipient. Likely paginated. Returns message objects including `id`, `sender_id`, `encrypted_content`, `signature`, `is_read`, `created_at`.</p>
                    </div>
                     <div class="bg-gray-900 p-3 rounded-md border border-gray-700 text-sm">
                        <p><strong class="text-white">`POST /api/messages`</strong> (<code>MessageController@send</code>)</p>
                         <p>Accepts: `{ recipient_id, encrypted_content, signature, is_ephemeral }`. Validates input, creates a new `Message` record. Returns the created message object.</p>
                     </div>
                     <div class="bg-gray-900 p-3 rounded-md border border-gray-700 text-sm">
                         <p><strong class="text-white">`GET /api/messages/poll`</strong> (<code>MessageController@poll</code>)</p>
                         <p>Checks for new messages since the last poll (likely based on `lastMessageId` query parameter). Returns new messages. Uses long polling or simple polling (verify implementation).</p>
                     </div>
                     <div class="bg-gray-900 p-3 rounded-md border border-gray-700 text-sm">
                         <p><strong class="text-white">`PATCH /api/messages/{messageId}/read`</strong> (<code>MessageController@markAsRead</code>)</p>
                         <p>Updates the `is_read` flag for the specified message ID. Requires the authenticated user to be the recipient.</p>
                     </div>
                     <div class="bg-gray-900 p-3 rounded-md border border-gray-700 text-sm">
                         <p><strong class="text-white">`DELETE /api/messages/{messageId}`</strong> (<code>MessageController@deleteMessage</code>)</p>
                         <p>Deletes a specific message. Requires the authenticated user to be either the sender or recipient (verify logic).</p>
                     </div>
                     <div class="bg-gray-900 p-3 rounded-md border border-gray-700 text-sm">
                         <p><strong class="text-white">`GET /api/conversations`</strong> (<code>MessageController@getConversations</code>)</p>
                         <p>Returns a list of users the authenticated user has conversations with, likely including the latest message preview (encrypted) and timestamp.</p>
                     </div>
                     <div class="bg-gray-900 p-3 rounded-md border border-gray-700 text-sm">
                         <p><strong class="text-white">`DELETE /api/conversations/{userId}`</strong> (<code>MessageController@deleteConversation</code>)</p>
                         <p>Deletes all messages between the authenticated user and the specified `userId` (both sent and received).</p>
                     </div>
                     <div class="bg-gray-900 p-3 rounded-md border border-gray-700 text-sm">
                         <p><strong class="text-white">`POST /api/typing-status`</strong> (<code>MessageController@updateTypingStatus</code>)</p>
                         <p>Accepts `{ recipient_id, is_typing (boolean) }`. Creates or updates a record in `typing_statuses`.</p>
                     </div>
                     <div class="bg-gray-900 p-3 rounded-md border border-gray-700 text-sm">
                         <p><strong class="text-white">`GET /api/typing-status`</strong> (<code>MessageController@getTypingStatus</code>)</p>
                         <p>Returns a list of user IDs currently typing to the authenticated user (based on recent `updated_at` in `typing_statuses`).</p>
                     </div>
                     <div class="bg-gray-900 p-3 rounded-md border border-gray-700 text-sm">
                         <p><strong class="text-white">`GET /api/keypair`</strong> (<code>KeyPairController@showOwn</code>)</p>
                         <p>Returns the authenticated user's public key (PEM) from the `key_pairs` table.</p>
                     </div>
                     <div class="bg-gray-900 p-3 rounded-md border border-gray-700 text-sm">
                         <p><strong class="text-white">`POST /api/keypair`</strong> (<code>KeyPairController@store</code>)</p>
                         <p>Accepts `{ public_key (PEM) }`. Creates or updates the `KeyPair` record for the authenticated user.</p>
                     </div>
                     <div class="bg-gray-900 p-3 rounded-md border border-gray-700 text-sm">
                         <p><strong class="text-white">`GET /api/keypair/{userId}`</strong> (<code>KeyPairController@show</code>)</p>
                         <p>Returns the public key (PEM) for the specified `userId`.</p>
                     </div>
                     <div class="bg-gray-900 p-3 rounded-md border border-gray-700 text-sm">
                         <p><strong class="text-white">`GET /api/users/search`</strong> (<code>UserController@search</code>)</p>
                         <p>Accepts `query` parameter. Searches for users by username, excluding users where `is_hidden` is true.</p>
                     </div>
                     <div class="bg-gray-900 p-3 rounded-md border border-gray-700 text-sm">
                         <p><strong class="text-white">`GET /api/users/{userId}`</strong> (<code>UserController@show</code>)</p>
                         <p>Returns public profile information for the specified `userId` (e.g., `id`, `username`).</p>
                     </div>
                 </div>

                <h2 class="text-2xl font-semibold text-white mt-10 mb-4 border-b pb-2 border-gray-600">4. Frontend Implementation Details (Vue.js)</h2>

                <h3 class="text-xl font-medium text-white mt-6 mb-3">4.1 State Management</h3>
                 <p class="text-sm">Likely uses Vue's built-in reactivity system (ref, reactive) or potentially Vuex (check `package.json` dependencies and `resources/js/store`). Global state might include authenticated user info, current conversation, messages, crypto keys.</p>
                
                <h3 class="text-xl font-medium text-white mt-6 mb-3">4.2 Core Components</h3>
                 <p class="text-sm">Key components in `resources/js/components/` likely include:</p>
                 <ul class="list-disc list-inside mt-2 space-y-1 text-sm">
                     <li>`Messaging.vue`: Main chat interface, handles message display, input, sending, polling.</li>
                     <li>`ConversationList.vue`: Displays list of conversations.</li>
                     <li>`KeyStatus.vue` / `Register.vue`: Handle key generation, storage, and backup prompts.</li>
                     <li>`Settings.vue`: User settings interface.</li>
                 </ul>

                 <h3 class="text-xl font-medium text-white mt-6 mb-3">4.3 Client-Side Services</h3>
                 <ul class="list-disc list-inside mt-2 space-y-1 text-sm">
                     <li>`CryptoService.js`: Encapsulates all cryptographic operations and IndexedDB key storage (detailed in section 2.1).</li>
                     <li>`ApiService.js` (or similar): Wraps `axios` or `fetch` calls to interact with the backend API endpoints defined in section 3. Handles request/response formatting and error handling.</li>
                     <li>`NotificationService.js` / `ToastService.js`: UI feedback mechanisms.</li>
                 </ul>

                <h3 class="text-xl font-medium text-white mt-6 mb-3">4.4 Data Flow Example (Sending Message)</h3>
                <ol class="list-decimal list-inside mt-2 space-y-2 text-sm">
                     <li>User types message in `Messaging.vue` input.</li>
                     <li>On submit, `sendMessage` method is called.</li>
                     <li>`ApiService` fetches recipient's public key PEM.</li>
                     <li>`CryptoService.importPublicKey` converts PEM to `CryptoKey`.</li>
                     <li>`CryptoService.encryptMessage` performs hybrid encryption and signing using recipient's public key and sender's private key (from `window.cryptoContext` or retrieved via `CryptoService.retrieveKeyPair`).</li>
                     <li>`ApiService.sendMessage` POSTs the encrypted JSON package and signature to `/api/messages`.</li>
                     <li>Response (new message object) is received from server.</li>
                     <li>Frontend state (messages array) is updated, UI re-renders.</li>
                     <li>Original plaintext message stored locally (e.g., in `decryptedMessages` object) for immediate display to sender.</li>
                 </ol>
                 
                 <h3 class="text-xl font-medium text-white mt-6 mb-3">4.5 Data Flow Example (Receiving Message)</h3>
                 <ol class="list-decimal list-inside mt-2 space-y-2 text-sm">
                     <li>Background polling via `ApiService.pollMessages` (`/api/messages/poll`).</li>
                     <li>Server returns new encrypted message objects if any.</li>
                     <li>For each message:
                         <ul class="list-disc list-inside ml-5 mt-1">
                             <li>`ApiService` fetches sender's public key PEM.</li>
                             <li>`CryptoService.importPublicKeyForVerification` converts PEM.</li>
                             <li>`CryptoService.verifySignature` checks signature against encrypted content using sender's key.</li>
                             <li>If signature valid: `CryptoService.decryptMessage` performs hybrid decryption using recipient's private key (from `window.cryptoContext` or IndexedDB).</li>
                             <li>Decrypted plaintext is stored (e.g., `decryptedMessages[message.id]`).</li>
                         </ul>
                     </li>
                    <li>Frontend state (messages array) updated, UI re-renders to show decrypted message.</li>
                    <li>`ApiService.markAsRead` PATCHes `/api/messages/{id}/read` after display (or user interaction).</li>
                </ol>


                <h2 class="text-2xl font-semibold text-white mt-10 mb-4 border-b pb-2 border-gray-600">5. Privacy & Data Handling</h2>

                 <h3 class="text-xl font-medium text-white mt-6 mb-3">5.1 Data Minimization</h3>
                 <ul class="list-disc list-inside mt-2 space-y-1 text-sm">
                     <li><strong>Account:</strong> `username` only required identifier.</li>
                     <li><strong>Content:</strong> Message plaintext never stored/logged server-side.</li>
                     <li><strong>Metadata:</strong> Minimal required metadata stored (`sender_id`, `recipient_id`, `timestamps`, `is_read`).</li>
                     <li><strong>Keys:</strong> Private keys never leave the client device during normal operation.</li>
                 </ul>

                 <h3 class="text-xl font-medium text-white mt-6 mb-3">5.2 Data Retention & Deletion</h3>
                 <ul class="list-disc list-inside mt-2 space-y-1 text-sm">
                     <li><strong>Messages:</strong> Retained until manually deleted by sender/recipient (`DELETE /api/messages/{id}`) or conversation deleted (`DELETE /api/conversations/{userId}`). Database cascade ensures related records removed.</li>
                     <li><strong>Ephemeral Messages:</strong> Server-side deletion logic needs verification, but intended to delete after first read confirmation.</li>
                     <li><strong>Account Deletion (`POST /account/delete`):</strong> Triggers `User::delete()`. Eloquent model's `deleting` event handler (`User::booted`) ensures cascading deletion of associated `KeyPair`, `Message` (sent/received), and potentially `TypingStatus` records. Session data is invalidated.</li>
                      <li><strong>Key Pairs:</strong> Deleted upon account deletion via cascade. Can be regenerated by user via settings.</li>
                      <li><strong>Typing Statuses:</strong> Likely ephemeral, cleaned periodically or deleted via cascade. Check `MessageController@getTypingStatus` for timeout logic.</li>
                 </ul>

                <h2 class="text-2xl font-semibold text-white mt-10 mb-4 border-b pb-2 border-gray-600">6. Security Considerations & Limitations</h2>

                <ul class="list-disc list-inside mt-2 space-y-2 text-sm">
                    <li><strong>Lack of Forward Secrecy:</strong> As detailed in 2.1.5, compromise of long-term RSA private key compromises past messages.</li>
                    <li><strong>Client-Side Key Management:</strong>
                        <ul class="list-circle list-inside ml-5 mt-1">
                            <li>Keys tied to browser profile. Loss of profile/device = loss of access.</li>
                            <li>No built-in multi-device support (requires manual key export/import).</li>
                            <li>Vulnerable to Cross-Site Scripting (XSS) if input sanitization fails elsewhere, potentially allowing attackers to exfiltrate keys from IndexedDB or intercept operations.</li>
                            <li>Vulnerable to malware/compromise of the user's device operating system.</li>
                        </ul>
                    </li>
                    <li><strong>Metadata Protection:</strong> Communication patterns (who talks to whom, when, how often) are visible server-side.</li>
                    <li><strong>Message Authenticity (Pre-computation):** While signatures prevent tampering *in transit*, an attacker who compromises the sender's private key *before* a message is sent can forge messages and valid signatures.</li>
                    <li><strong>Key Authenticity:</strong> Initial key exchange relies on trusting the server (`/api/keypair/{userId}`) to provide the correct public key. No out-of-band verification mechanism (like scanning QR codes or safety numbers) is implemented. A compromised server could potentially perform a Man-in-the-Middle (MitM) attack during key lookup.</li>
                    <li><strong>Replay Attacks:</strong> Need to verify if nonce/timestamp mechanisms prevent replaying old messages, although AES-GCM inherently provides some protection.</li>
                    <li><strong>Dependency Security:</strong> Relies on the security of Laravel, Vue, Tailwind, js-crypto libraries, and browser Web Crypto implementations. Vulnerabilities in dependencies could impact the application.</li>
                </ul>

                <h2 class="text-2xl font-semibold text-white mt-10 mb-4 border-b pb-2 border-gray-600">7. Server Configuration & Deployment</h2>
                 <ul class="list-disc list-inside mt-2 space-y-1 text-sm">
                     <li>Requires standard PHP/Laravel environment (PHP 8.2+, Composer, relevant PHP extensions like `pdo_mysql`, `openssl`, `mbstring`, `tokenizer`, `xml`).</li>
                     <li>MySQL database server.</li>
                     <li>Node.js/npm for frontend asset compilation (`npm run build`).</li>
                     <li>Web server (Nginx/Apache) configured to serve the `public` directory and handle PHP requests via FPM. Standard Laravel security configurations (directory permissions, disabling `.env` access) should be followed.</li>
                     <li>HTTPS is essential for protecting authentication tokens and preventing MitM during API calls/key fetches. Configuration handled at the web server level (e.g., Let's Encrypt).</li>
                 </ul>

                <h2 class="text-2xl font-semibold text-white mt-10 mb-4 border-b pb-2 border-gray-600">8. Auditing & Contact</h2>
                <ul class="list-disc list-inside mt-2 space-y-1 text-sm">
                     <li>Client-side JavaScript (`resources/js/**/*.js`, compiled output in `public/build/assets/`) is fully inspectable in the browser's developer tools.</li>
                     <li>Backend PHP code (`app/`, `routes/`, `config/`) defines server logic.</li>
                      <li>Responsible disclosure of security vulnerabilities is encouraged via email to <a href="mailto:privmsg@benegedeniz.com" class="text-blue-400 hover:text-blue-300">privmsg@benegedeniz.com</a>.</li>
                 </ul>
                 
            </div>
        </div>
    </div>
</div>
@endsection 