/**
 * KeyStatus.vue - Key status and management component for dashboard
 */
<template>
  <div class="bg-gradient-to-br from-gray-800 to-gray-900 border border-gray-700 rounded-xl overflow-hidden shadow-lg">
    <div class="p-6">
      <h2 class="text-xl font-semibold text-white mb-4 flex items-center">
        <svg class="h-5 w-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
        </svg>
        Encryption Keys
      </h2>
      
      <!-- Loading state -->
      <div v-if="loading" class="py-6 flex justify-center items-center">
        <svg class="animate-spin h-8 w-8 text-blue-500 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span class="text-gray-300">Checking encryption keys...</span>
      </div>
      
      <!-- Error state -->
      <div v-else-if="error" class="bg-red-900 bg-opacity-50 border border-red-800 text-white px-5 py-4 rounded-lg">
        <div class="flex items-start">
          <svg class="h-5 w-5 text-red-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
          </svg>
          <div>
            <p class="font-medium">Error: {{ error }}</p>
            <p class="text-sm mt-2">Please try reloading the page or contact support if the issue persists.</p>
          </div>
        </div>
      </div>
      
      <!-- Key information card -->
      <div v-else class="space-y-6">
        <!-- Key cards in side-by-side grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:items-start">
          <!-- Local key status -->
          <div class="border border-gray-700 rounded-lg overflow-hidden bg-gray-800 bg-opacity-50 self-start">
            <div class="p-4 bg-gray-800">
              <h3 class="text-white font-medium flex items-center">
                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                </svg>
                <span>Local Private Key</span>
                <span 
                  :class="localKeysPresent ? 'bg-green-500' : 'bg-red-500'"
                  class="ml-2 inline-block h-3 w-3 rounded-full"
                ></span>
              </h3>
            </div>
            <div class="p-4">
              <p class="text-gray-300 text-sm mb-4">
                <span v-if="localKeysPresent">
                  Your private encryption key is present in this browser.
                  Your messages can be decrypted.
                </span>
                <span v-else>
                  No encryption key found in this browser.
                  You won't be able to decrypt your messages.
                </span>
              </p>
              
              <div v-if="localKeysPresent" class="flex flex-wrap gap-2">
                <button 
                  @click="showExportModal = true"
                  class="px-3 py-1.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white text-sm rounded-md hover:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-150 ease-in-out flex items-center"
                >
                  <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                  </svg>
                  Export Key
                </button>
                <button 
                  @click="confirmResetKeys"
                  class="px-3 py-1.5 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white text-sm rounded-md hover:shadow-md focus:outline-none focus:ring-2 focus:ring-red-500 transition-all duration-150 ease-in-out flex items-center"
                >
                  <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                  </svg>
                  Reset Keys
                </button>
              </div>
              
              <div v-else class="flex flex-wrap gap-2">
                <button 
                  @click="showImportModal = true"
                  class="px-3 py-1.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white text-sm rounded-md hover:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-150 ease-in-out flex items-center"
                >
                  <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                  </svg>
                  Restore Private Key
                </button>
                <button 
                  @click="generateNewKeys"
                  class="px-3 py-1.5 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white text-sm rounded-md hover:shadow-md focus:outline-none focus:ring-2 focus:ring-green-500 transition-all duration-150 ease-in-out flex items-center"
                >
                  <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                  </svg>
                  Generate Fresh Keys
                </button>
              </div>
              
              <!-- Warning message - only show when server key exists but local key doesn't -->
              <div v-if="!localKeysPresent && serverKeyPresent" class="w-full mt-2 p-3 border border-yellow-600 bg-yellow-900 bg-opacity-30 rounded-md">
                <div class="flex items-center">
                  <svg class="h-5 w-5 text-yellow-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                  </svg>
                  <strong class="text-yellow-500">Note:</strong>
                </div>
                <p class="ml-7 text-sm text-yellow-100 mt-1">A public key is registered on the server.</p>
                <p class="ml-7 mt-2 text-sm">
                  <span class="text-yellow-400">→ Choose "Restore Private Key"</span> if you have a backup of your previous key.
                </p>
                <p class="ml-7 text-sm">
                  <span class="text-yellow-400">→ Choose "Generate Fresh Keys"</span> only if you've lost your key (you'll lose access to previous messages).
                </p>
              </div>
              
              <!-- Success message - only show when both local and server keys are present -->
              <div v-if="localKeysPresent && serverKeyPresent" class="mt-2 p-3 bg-gray-800 rounded-md border border-gray-700 text-sm text-gray-400">
                <div class="flex items-center mb-1">
                  <svg class="h-4 w-4 text-green-400 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                  </svg>
                  <strong>Ready for Encrypted Messaging</strong>
                </div>
                <p class="ml-5.5">Your private key is registered on this browser. You can now exchange encrypted messages with other users.</p>
              </div>
              
              <!-- Info message - only show when neither key exists -->
              <div v-if="!localKeysPresent && !serverKeyPresent" class="w-full mt-2 p-3 bg-gray-800 rounded-md border border-gray-700 text-xs text-gray-400">
                <div class="flex items-center mb-1">
                  <svg class="h-4 w-4 text-blue-400 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                  <strong>Key Restoration Options:</strong>
                </div>
                <p class="ml-5.5 mb-1.5"><strong>Restore:</strong> If you have a backup of your previous private key, use this to decrypt existing messages.</p>
                <p class="ml-5.5"><strong>Generate:</strong> Create new keys if you don't have a backup or want to start fresh.</p>
              </div>
            </div>
          </div>
          
          <!-- Server key status -->
          <div class="border border-gray-700 rounded-lg overflow-hidden bg-gray-800 bg-opacity-50 self-start">
            <div class="p-4 bg-gray-800">
              <h3 class="text-white font-medium flex items-center">
                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                </svg>
                <span>Server Public Key</span>
                <span 
                  :class="serverKeyPresent ? 'bg-green-500' : 'bg-red-500'"
                  class="ml-2 inline-block h-3 w-3 rounded-full"
                ></span>
              </h3>
            </div>
            <div class="p-4">
              <p class="text-gray-300 text-sm mb-4">
                <span v-if="serverKeyPresent">
                  Your public key is registered on the server.
                  Others can send you encrypted messages.
                </span>
                <span v-else>
                  No public key registered on the server.
                  You need to generate or import keys to receive messages.
                </span>
              </p>
              
              <div v-if="!serverKeyPresent && localKeysPresent" class="flex flex-wrap gap-2">
                <button 
                  @click="uploadPublicKey"
                  class="px-3 py-1.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white text-sm rounded-md hover:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-150 ease-in-out flex items-center"
                >
                  <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                  </svg>
                  Upload Public Key
                </button>
              </div>
              
              <!-- Add some filler content to balance the height of both cards -->
              <div v-if="serverKeyPresent" class="mt-2 p-3 bg-gray-800 rounded-md border border-gray-700 text-sm text-gray-400">
                <div class="flex items-center mb-1">
                  <svg class="h-4 w-4 text-green-400 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                  </svg>
                  <strong>Ready for Encrypted Messaging</strong>
                </div>
                <p class="ml-5.5">Your public key is active on the server. You can now exchange encrypted messages with other users.</p>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Key usage information -->
        <div class="bg-blue-900 bg-opacity-20 p-4 rounded-md border border-blue-800 text-sm">
          <h4 class="font-medium text-white mb-2 flex items-center">
            <svg class="h-4 w-4 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            About Your Encryption Keys
          </h4>
          <ul class="list-disc list-inside space-y-1 text-gray-300 ml-6">
            <li>Your private key never leaves your browser</li>
            <li>Messages are encrypted/decrypted directly in your browser</li>
            <li>If you use a different browser, you'll need to import your private key</li>
            <li>Always keep a backup of your private key in a secure location</li>
          </ul>
        </div>
      </div>
    </div>
    
    <!-- Export Private Key Modal -->
    <div v-if="showExportModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 p-4">
      <div class="bg-gray-800 rounded-lg p-6 max-w-md w-full">
        <h3 class="text-lg font-medium text-white mb-4">Export Private Key</h3>
        <p class="text-gray-400 text-sm mb-4">
          This is your private encryption key. Store it securely in a password manager or other secure location.
          You'll need this key if you want to access your messages from another device.
        </p>
        
        <div class="relative">
          <textarea
            readonly
            rows="6"
            class="block w-full px-3 py-2 border border-gray-700 rounded-md shadow-sm bg-gray-900 text-gray-300 font-mono text-xs"
            v-model="exportedPrivateKey"
          ></textarea>
          <button
            @click="copyPrivateKey"
            class="absolute top-2 right-2 bg-gray-700 p-1 rounded hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            title="Copy to clipboard"
          >
            <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path>
            </svg>
          </button>
        </div>
        
        <div class="mt-6 flex justify-end">
          <button
            @click="showExportModal = false"
            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
          >
            Close
          </button>
        </div>
      </div>
    </div>
    
    <!-- Import Private Key Modal -->
    <div v-if="showImportModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 p-4">
      <div class="bg-gray-800 rounded-lg p-6 max-w-md w-full">
        <h3 class="text-lg font-medium text-white mb-4">Restore Private Key</h3>
        <p class="text-gray-400 text-sm mb-4">
          Paste your previously backed up private key below to restore access to your encrypted messages.
          This will only work if the private key matches the public key registered on the server.
        </p>
        
        <div v-if="importError" class="bg-red-900 bg-opacity-50 border border-red-800 text-white px-4 py-3 rounded-md mb-4 text-sm">
          {{ importError }}
        </div>
        
        <div>
          <textarea
            rows="6"
            class="block w-full px-3 py-2 border border-gray-700 rounded-md shadow-sm bg-gray-900 text-gray-300 font-mono text-xs"
            v-model="privateKeyInput"
            placeholder="-----BEGIN PRIVATE KEY-----&#10;...&#10;-----END PRIVATE KEY-----"
          ></textarea>
        </div>
        
        <div class="mt-6 flex justify-end space-x-3">
          <button
            @click="showImportModal = false"
            class="px-4 py-2 border border-gray-600 text-gray-300 rounded hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
          >
            Cancel
          </button>
          <button
            @click="importPrivateKey"
            :disabled="importingKey"
            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50"
          >
            <span v-if="importingKey">
              <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              Restoring...
            </span>
            <span v-else>Restore Key</span>
          </button>
        </div>
      </div>
    </div>
    
    <!-- Confirm Reset Modal -->
    <div v-if="showResetConfirmModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 p-4">
      <div class="bg-gray-800 rounded-lg p-6 max-w-md w-full">
        <h3 class="text-lg font-medium text-white mb-4">Confirm Key Reset</h3>
        <p class="text-gray-400 text-sm mb-4">
          <strong class="text-red-400">Warning:</strong> Resetting your keys will remove your current encryption keys from this browser.
          You will lose access to all previously encrypted messages unless you have a backup of your private key.
        </p>
        
        <p class="text-gray-400 text-sm mb-4">
          Are you sure you want to continue?
        </p>
        
        <div class="mt-6 flex justify-end space-x-3">
          <button
            @click="showResetConfirmModal = false"
            class="px-4 py-2 border border-gray-600 text-gray-300 rounded hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
          >
            Cancel
          </button>
          <button
            @click="resetKeys"
            class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500"
          >
            Reset Keys
          </button>
        </div>
      </div>
    </div>
    
    <!-- Generate New Keys Modal -->
    <div v-if="showGenerateModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 p-4">
      <div class="bg-gray-800 rounded-lg p-6 max-w-md w-full">
        <h3 class="text-lg font-medium text-white mb-4">Generate New Encryption Keys</h3>
        
        <div v-if="generatingKeys" class="text-center py-8">
          <svg class="animate-spin h-10 w-10 text-blue-500 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <p class="text-gray-300">Generating secure encryption keys...</p>
          <p class="text-gray-400 text-sm mt-2">This may take a few seconds.</p>
        </div>
        
        <div v-else-if="newKeyGenerated" class="space-y-4">
          <div class="bg-green-900 bg-opacity-30 border border-green-800 text-white px-4 py-3 rounded-md mb-4">
            <p>New encryption keys have been generated successfully!</p>
          </div>
          
          <p class="text-gray-400 text-sm">
            Important: Backup your private key now. You'll need it if you want to access your messages from another device.
          </p>
          
          <div class="relative">
            <textarea
              readonly
              rows="6"
              class="block w-full px-3 py-2 border border-gray-700 rounded-md shadow-sm bg-gray-900 text-gray-300 font-mono text-xs"
              v-model="newPrivateKey"
            ></textarea>
            <button
              @click="copyNewPrivateKey"
              class="absolute top-2 right-2 bg-gray-700 p-1 rounded hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
              title="Copy to clipboard"
            >
              <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path>
              </svg>
            </button>
          </div>
          
          <div class="relative flex items-start">
            <div class="flex items-center h-5">
              <input 
                id="key-backup-confirm" 
                type="checkbox" 
                v-model="keyBackupConfirmed"
                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-700 rounded bg-gray-800"
              >
            </div>
            <div class="ml-3 text-sm">
              <label for="key-backup-confirm" class="font-medium text-gray-300">I have copied and saved my private key</label>
            </div>
          </div>
        </div>
        
        <div v-else>
          <p class="text-gray-400 text-sm mb-4">
            You're about to generate new encryption keys. This will:
          </p>
          <ul class="list-disc list-inside text-gray-400 text-sm mb-4 space-y-1">
            <li>You will LOSE all recieved messages!</li>
            <li>Create a new encryption key pair for your account</li>
            <li>Register your public key on the server</li>
            <li>Store your private key securely in this browser</li>
            <li>Allow you to send and receive encrypted messages</li>
          </ul>
          <p class="text-gray-400 text-sm mb-4">
            Would you like to continue?
          </p>
        </div>
        
        <div class="mt-6 flex justify-end space-x-3">
          <button
            v-if="!newKeyGenerated"
            @click="showGenerateModal = false"
            class="px-4 py-2 border border-gray-600 text-gray-300 rounded hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
          >
            Cancel
          </button>
          <button
            v-if="!generatingKeys && !newKeyGenerated"
            @click="generateKeys"
            class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500"
          >
            Generate Keys
          </button>
          <button
            v-if="newKeyGenerated"
            @click="finishKeyGeneration"
            :disabled="!keyBackupConfirmed"
            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50"
          >
            Finish
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import cryptoService from '../services/CryptoService';
import apiService from '../services/ApiService';
import toastService from '../services/ToastService';

export default {
  data() {
    return {
      // Key status
      loading: true,
      error: null,
      localKeysPresent: false,
      serverKeyPresent: false,
      username: document.querySelector('meta[name="username"]')?.getAttribute('content') || localStorage.getItem('username') || null,
      
      // Export modal
      showExportModal: false,
      exportedPrivateKey: '',
      
      // Import modal
      showImportModal: false,
      privateKeyInput: '',
      importError: null,
      importingKey: false,
      
      // Reset modal
      showResetConfirmModal: false,
      
      // Generate modal
      showGenerateModal: false,
      generatingKeys: false,
      newKeyGenerated: false,
      newPrivateKey: '',
      keyBackupConfirmed: false
    };
  },
  async mounted() {
    // If username is not set, try to get it from the page or other elements
    if (!this.username) {
      // Try to extract username from welcome message or other DOM elements
      const welcomeHeading = document.querySelector('h2.text-xl.font-semibold');
      if (welcomeHeading && welcomeHeading.textContent.includes('Welcome,')) {
        const match = welcomeHeading.textContent.match(/Welcome,\s+(\w+)/);
        if (match && match[1]) {
          this.username = match[1].trim();
          localStorage.setItem('username', this.username);
        }
      }
    }
    
    await this.checkKeyStatus();
  },
  methods: {
    // Check the status of keys both locally and on the server
    async checkKeyStatus() {
      try {
        this.loading = true;
        this.error = null;
        
        // Check if we have keys in the browser's crypto context
        if (window.cryptoContext && window.cryptoContext.privateKey) {
          this.localKeysPresent = true;
          this.exportedPrivateKey = window.cryptoContext.exportedPrivateKey;
        } else if (this.username) {
          // Try to retrieve keys from IndexedDB
          try {
            const storedKeys = await cryptoService.retrieveKeyPair(this.username);
            
            if (storedKeys) {
              // We have keys in browser storage
              window.cryptoContext = {
                privateKey: storedKeys.privateKey,
                publicKey: storedKeys.publicKey,
                exportedPrivateKey: storedKeys.exportedPrivateKey,
                exportedPublicKey: storedKeys.exportedPublicKey
              };
              this.localKeysPresent = true;
              this.exportedPrivateKey = storedKeys.exportedPrivateKey;
            }
          } catch (dbError) {
            console.error('Error retrieving keys from IndexedDB:', dbError);
            // Continue execution to check server key status
          }
        } else {
          console.warn('Username not found. Cannot check key status in IndexedDB.');
        }
        
        // Check if we have a public key on the server
        try {
          const keyStatus = await apiService.checkKeyPairStatus();
          this.serverKeyPresent = keyStatus.has_key_pair;
        } catch (apiError) {
          console.error('Error checking server key status:', apiError);
          throw apiError; // Re-throw to be caught by the outer try/catch
        }
      } catch (error) {
        console.error('Error checking key status:', error);
        this.error = 'Failed to check encryption key status. Please reload the page.';
      } finally {
        this.loading = false;
      }
    },
    
    // Copy the private key to clipboard
    copyPrivateKey() {
      navigator.clipboard.writeText(this.exportedPrivateKey)
        .then(() => {
          toastService.success('Private key copied to clipboard. Store it securely!');
        })
        .catch(err => {
          console.error('Could not copy text: ', err);
        });
    },
    
    // Copy the newly generated private key to clipboard
    copyNewPrivateKey() {
      navigator.clipboard.writeText(this.newPrivateKey)
        .then(() => {
          toastService.success('Private key copied to clipboard. Store it securely!');
        })
        .catch(err => {
          console.error('Could not copy text: ', err);
        });
    },
    
    // Show confirmation modal for key reset
    confirmResetKeys() {
      this.showResetConfirmModal = true;
    },
    
    // Reset keys (remove from browser storage)
    async resetKeys() {
      try {
        // Open database
        const dbName = 'PrivMsgEncryptionKeys';
        const request = indexedDB.open(dbName, 1);
        
        request.onsuccess = (event) => {
          const db = event.target.result;
          const transaction = db.transaction(['keys'], 'readwrite');
          const store = transaction.objectStore('keys');
          
          // Delete the keys for this user
          const deleteRequest = store.delete(this.username);
          
          deleteRequest.onsuccess = () => {
            // Clear the crypto context
            window.cryptoContext = null;
            
            // Update state
            this.localKeysPresent = false;
            this.showResetConfirmModal = false;
            
            // Show message
            toastService.success('Your encryption keys have been removed from this browser.');
          };
          
          deleteRequest.onerror = () => {
            console.error('Error deleting keys from IndexedDB');
            toastService.error('Failed to reset keys. Please try again.');
          };
        };
        
        request.onerror = (event) => {
          console.error('Error opening database:', event.target.error);
          toastService.error('Failed to reset keys. Please try again.');
        };
      } catch (error) {
        console.error('Error resetting keys:', error);
        toastService.error('Failed to reset keys. Please try again.');
      }
    },
    
    // Import a private key
    async importPrivateKey() {
      try {
        this.importError = null;
        this.importingKey = true;
        
        // Validate key format
        if (!this.privateKeyInput.includes('-----BEGIN PRIVATE KEY-----') ||
            !this.privateKeyInput.includes('-----END PRIVATE KEY-----')) {
          this.importError = 'Invalid private key format. Please check your key and try again.';
          this.importingKey = false;
          return;
        }
        
        // Import the private key
        const privateKey = await cryptoService.importPrivateKey(this.privateKeyInput);
        
        // Get key pair status from server
        const keyStatus = await apiService.checkKeyPairStatus();
        
        if (keyStatus.has_key_pair) {
          // We have a public key on the server
          const publicKey = await cryptoService.importPublicKey(keyStatus.public_key);
          
          // Store the key pair in IndexedDB
          await cryptoService.storeKeyPair(
            privateKey,
            publicKey,
            this.username
          );
          
          // Set the global crypto context
          window.cryptoContext = {
            privateKey: privateKey,
            publicKey: publicKey,
            exportedPrivateKey: this.privateKeyInput,
            exportedPublicKey: keyStatus.public_key
          };
          
          // Update state
          this.localKeysPresent = true;
          this.serverKeyPresent = true;
          this.exportedPrivateKey = this.privateKeyInput;
          this.showImportModal = false;
          
          // Show success message
          toastService.success('Private key imported successfully!');
          
        } else {
          this.importError = 'No public key found on the server. Please generate new keys instead.';
        }
      } catch (error) {
        console.error('Error importing private key:', error);
        this.importError = 'Failed to import private key. The key may be invalid or in an unsupported format.';
      } finally {
        this.importingKey = false;
      }
    },
    
    // Upload the local public key to the server
    async uploadPublicKey() {
      try {
        if (!window.cryptoContext || !window.cryptoContext.exportedPublicKey) {
          toastService.error('No public key available to upload.');
          return;
        }
        
        await apiService.storePublicKey(window.cryptoContext.exportedPublicKey);
        
        // Update state
        this.serverKeyPresent = true;
        
        // Show success message
        toastService.success('Public key uploaded successfully!');
      } catch (error) {
        console.error('Error uploading public key:', error);
        toastService.error('Failed to upload public key. Please try again.');
      }
    },
    
    // Show the generate keys modal
    generateNewKeys() {
      this.showGenerateModal = true;
      this.newKeyGenerated = false;
      this.generatingKeys = false;
      this.keyBackupConfirmed = false;
    },
    
    // Generate new encryption keys
    async generateKeys() {
      try {
        this.generatingKeys = true;
        
        // Generate a new key pair
        const keyPair = await cryptoService.generateKeyPair();
        
        // Export the private key for backup
        const exportedPrivateKey = await cryptoService.exportPrivateKey(keyPair.privateKey);
        
        // Store the key pair in IndexedDB
        await cryptoService.storeKeyPair(
          keyPair.privateKey,
          keyPair.publicKey,
          this.username
        );
        
        // Store only the public key on the server
        await apiService.storePublicKey(keyPair.exportedPublicKey);
        
        // Set the global crypto context
        window.cryptoContext = {
          privateKey: keyPair.privateKey,
          publicKey: keyPair.publicKey,
          exportedPrivateKey: exportedPrivateKey,
          exportedPublicKey: keyPair.exportedPublicKey
        };
        
        // Update state
        this.localKeysPresent = true;
        this.serverKeyPresent = true;
        this.exportedPrivateKey = exportedPrivateKey;
        this.newPrivateKey = exportedPrivateKey;
        this.newKeyGenerated = true;
        
      } catch (error) {
        console.error('Error generating encryption keys:', error);
        toastService.error('Failed to generate encryption keys. Please try again.');
        this.showGenerateModal = false;
      } finally {
        this.generatingKeys = false;
      }
    },
    
    // Finish the key generation process
    finishKeyGeneration() {
      if (!this.keyBackupConfirmed) {
        toastService.error('Please confirm that you have backed up your private key.');
        return;
      }
      
      this.showGenerateModal = false;
      this.newKeyGenerated = false;
    }
  }
};
</script>