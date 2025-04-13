<template>
  <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl border border-gray-700 p-8 max-w-md mx-auto shadow-xl">
    <!-- Background decorative elements -->
    <div class="absolute inset-0 overflow-hidden opacity-5 rounded-xl pointer-events-none">
      <div class="absolute -top-10 -left-10 w-40 h-40 bg-blue-600 rounded-full filter blur-3xl opacity-20"></div>
      <div class="absolute top-20 -right-10 w-40 h-40 bg-blue-800 rounded-full filter blur-3xl opacity-20"></div>
    </div>

    <h2 class="text-2xl font-bold mb-2 text-white text-center">Create Your Account</h2>
    <p class="text-gray-400 text-sm text-center mb-6">Join PrivMsg for secure encrypted messaging</p>
    
    <!-- Registration Complete -->
    <div v-if="registrationComplete" class="text-center">
      <div class="flex items-center justify-center mb-4">
        <div class="rounded-full bg-green-900 bg-opacity-30 p-4">
          <svg class="h-12 w-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
        </div>
      </div>
      <h3 class="text-xl font-semibold text-white mb-2">Registration Complete!</h3>
      <p class="text-gray-400 mb-6">
        Your account has been created and your encryption keys have been generated.
      </p>
      <a href="/messages" class="inline-block w-full py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-150 ease-in-out">
        Go to Messages
      </a>
    </div>
    
    <!-- Registration Form -->
    <div v-else class="relative z-10">
      <div v-if="errors.length > 0" class="bg-red-900 border border-red-800 text-white px-4 py-3 rounded-md mb-6">
        <ul class="list-disc list-inside">
          <li v-for="(error, index) in errors" :key="index">{{ error }}</li>
        </ul>
      </div>
      
      <form @submit.prevent="registerUser" class="space-y-6">
        <div>
          <label for="username" class="block text-sm font-medium text-gray-300">Username</label>
          <div class="mt-1 relative rounded-md shadow-sm">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
              </svg>
            </div>
            <input 
              id="username" 
              type="text" 
              v-model="username" 
              required 
              class="pl-10 mt-1 block w-full px-3 py-2 border border-gray-700 rounded-md shadow-sm bg-gray-800 text-white focus:outline-none focus:ring-blue-500 focus:border-blue-500"
              autocomplete="username"
            />
          </div>
        </div>
        
        <div>
          <label for="password" class="block text-sm font-medium text-gray-300">Password</label>
          <div class="mt-1 relative rounded-md shadow-sm">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
              </svg>
            </div>
            <input 
              id="password" 
              type="password" 
              v-model="password" 
              required 
              class="pl-10 mt-1 block w-full px-3 py-2 border border-gray-700 rounded-md shadow-sm bg-gray-800 text-white focus:outline-none focus:ring-blue-500 focus:border-blue-500"
              autocomplete="new-password"
            />
          </div>
          <p class="mt-1 text-xs text-gray-400">Password must be at least 8 characters long.</p>
        </div>
        
        <div>
          <label for="password_confirmation" class="block text-sm font-medium text-gray-300">Confirm Password</label>
          <div class="mt-1 relative rounded-md shadow-sm">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
              </svg>
            </div>
            <input 
              id="password_confirmation" 
              type="password" 
              v-model="passwordConfirmation" 
              required 
              class="pl-10 mt-1 block w-full px-3 py-2 border border-gray-700 rounded-md shadow-sm bg-gray-800 text-white focus:outline-none focus:ring-blue-500 focus:border-blue-500"
              autocomplete="new-password"
            />
          </div>
        </div>
        
        <div class="bg-gray-800 bg-opacity-50 p-4 rounded-md border border-gray-700">
          <div class="relative flex items-start mb-4">
            <div class="flex items-center h-5">
              <input 
                id="terms" 
                type="checkbox" 
                v-model="acceptTerms"
                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-700 rounded bg-gray-800"
              />
            </div>
            <div class="ml-3 text-sm">
              <label for="terms" class="font-medium text-gray-300">I understand that:</label>
              <ul class="list-disc list-inside ml-1 mt-1 text-xs text-gray-400">
                <li>My messages are encrypted in my browser</li>
                <li>If I lose my encryption keys, I will not be able to read previous messages</li>
                <li>No one can recover my messages if I lose my keys</li>
              </ul>
            </div>
          </div>
          
          <div class="text-xs text-gray-400">
            By creating an account, you agree to our 
            <a href="/terms" class="text-blue-500 hover:text-blue-400 hover:underline">Terms of Service</a> and 
            <a href="/privacy" class="text-blue-500 hover:text-blue-400 hover:underline">Privacy Policy</a>
          </div>
        </div>
        
        <div>
          <button
            type="submit"
            :disabled="isRegistering || !acceptTerms"
            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-150 ease-in-out"
          >
            <span v-if="isRegistering" class="flex items-center">
              <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              <span v-if="generatingKeys">Generating Encryption Keys...</span>
              <span v-else>Creating Account...</span>
            </span>
            <span v-else>Create Account</span>
          </button>
        </div>
        
        <div class="text-sm text-center text-gray-400">
          Already have an account? <a href="/login" class="font-medium text-blue-500 hover:text-blue-400">Sign in</a>
        </div>
      </form>
    </div>
    
    <!-- Key Backup Modal -->
    <div v-if="showBackupModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 p-4">
      <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-lg p-6 max-w-md w-full border border-gray-700 shadow-2xl">
        <div class="flex justify-between items-start mb-4">
          <h3 class="text-lg font-medium text-white">Important: Backup Your Private Key</h3>
          <div class="flex-shrink-0 bg-blue-600 rounded-full h-8 w-8 flex items-center justify-center">
            <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M18 8a6 6 0 01-7.743 5.743L10 14l-1 1-1 1H6v-1l1-1 1-1-1-1H6v-1h2l2-2h1l5-5V8zm-2.447.106a4 4 0 00.658-1.802 4 4 0 00-3.301-3.7 4 4 0 00-4.817 2.25 4.119 4.119 0 00-2.445 4.63 4 4 0 003.18 2.655L9.5 12l.5.5-.5.5-.5.5 1 1 .5-.5.5-.5.5-.5.5-.5 1-1-.5-.5-1-.5h-.5L9 10l-2 2H5V8h2l2-2h2v-.5l.5-.5.5-.5.5-.5L13 4l-.5.5-.5.5-.5.5-.5.5v2l1.5-1.5.5-.5 1-1-1 2z" clip-rule="evenodd"/>
            </svg>
          </div>
        </div>
        <p class="text-gray-400 text-sm mb-4">
          This private key is used to decrypt your messages. If you lose it, you will not be able to read your messages.
          Store it securely in a password manager or other secure location.
        </p>
        
        <div class="relative">
          <textarea
            readonly
            rows="4"
            class="block w-full px-3 py-2 border border-gray-700 rounded-md shadow-sm bg-gray-900 text-gray-300 font-mono text-xs"
            v-model="privateKeyBackup"
          ></textarea>
          <button
            @click="copyPrivateKey"
            class="absolute top-2 right-2 bg-gray-700 p-1 rounded hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
            title="Copy to clipboard"
          >
            <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path>
            </svg>
          </button>
        </div>
        
        <div class="mt-6 space-y-4">
          <div class="bg-blue-900 bg-opacity-20 p-4 rounded-md border border-blue-800">
            <div class="flex items-center">
              <svg class="h-5 w-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              <span class="text-sm font-medium text-blue-100">Security Recommendation</span>
            </div>
            <p class="mt-2 text-xs text-blue-200">
              Consider storing this key in a secure password manager or encrypted file. Never share this private key with anyone else.
            </p>
          </div>
          
          <div class="relative flex items-start">
            <div class="flex items-center h-5">
              <input 
                id="key-backup-confirm" 
                type="checkbox" 
                v-model="keyBackupConfirmed"
                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-700 rounded bg-gray-800"
              />
            </div>
            <div class="ml-3 text-sm">
              <label for="key-backup-confirm" class="font-medium text-gray-300">I have copied and saved my private key</label>
            </div>
          </div>
          
          <button
            @click="completeRegistration"
            :disabled="!keyBackupConfirmed"
            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 transition-all duration-150 ease-in-out"
          >
            Continue to Messages
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import cryptoService from '../../services/CryptoService';
import toastService from '../../services/ToastService';

export default {
  data() {
    return {
      username: '',
      password: '',
      passwordConfirmation: '',
      acceptTerms: false,
      isRegistering: false,
      generatingKeys: false,
      showBackupModal: false,
      keyBackupConfirmed: false,
      registrationComplete: false,
      privateKeyBackup: '',
      errors: []
    };
  },
  methods: {
    async registerUser() {
      this.errors = [];
      
      // Validate form
      if (this.password !== this.passwordConfirmation) {
        this.errors.push('Passwords do not match.');
        return;
      }
      
      if (this.password.length < 8) {
        this.errors.push('Password must be at least 8 characters.');
        return;
      }
      
      if (!this.acceptTerms) {
        this.errors.push('You must accept the terms.');
        return;
      }
      
      try {
        this.isRegistering = true;
        
        // Register the user with the server
        const response = await fetch('/register', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify({
            username: this.username,
            password: this.password,
            password_confirmation: this.passwordConfirmation
          })
        });
        
        const data = await response.json();
        
        if (!response.ok) {
          if (data.errors) {
            // Format Laravel validation errors
            Object.values(data.errors).forEach(errorArray => {
              errorArray.forEach(error => this.errors.push(error));
            });
          } else {
            this.errors.push(data.message || 'An error occurred during registration.');
          }
          this.isRegistering = false;
          return;
        }
        
        // Generate encryption keys
        this.generatingKeys = true;
        await this.generateEncryptionKeys();
        
      } catch (error) {
        console.error('Registration error:', error);
        this.errors.push('An unexpected error occurred. Please try again.');
        this.isRegistering = false;
      }
    },
    
    async generateEncryptionKeys() {
      try {
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
        const storeResponse = await fetch('/api/keypair', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify({
            public_key: keyPair.exportedPublicKey
          })
        });
        
        if (!storeResponse.ok) {
          throw new Error('Failed to store public key on server');
        }
        
        // Set the global crypto context
        window.cryptoKeys = {
          privateKey: keyPair.privateKey,
          publicKey: keyPair.publicKey,
          exportedPublicKey: keyPair.exportedPublicKey
        };
        
        // Show the backup modal
        this.privateKeyBackup = exportedPrivateKey;
        this.showBackupModal = true;
        
      } catch (error) {
        console.error('Error generating encryption keys:', error);
        this.errors.push('Failed to generate encryption keys. Please try again.');
        this.isRegistering = false;
        this.generatingKeys = false;
      }
    },
    
    copyPrivateKey() {
      navigator.clipboard.writeText(this.privateKeyBackup)
        .then(() => {
          toastService.success('Private key copied to clipboard. Store it securely!');
        })
        .catch(err => {
          console.error('Could not copy text: ', err);
        });
    },
    
    completeRegistration() {
      // Mark registration as complete
      this.showBackupModal = false;
      this.registrationComplete = true;
      this.isRegistering = false;
      this.generatingKeys = false;
    }
  }
};
</script>