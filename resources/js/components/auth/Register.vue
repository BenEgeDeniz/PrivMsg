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
          <!-- Use items-stretch to make input and button the same height -->
          <div class="mt-1 relative rounded-md shadow-sm flex items-stretch">
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
              class="pl-10 block w-full px-3 py-2 border border-gray-700 rounded-l-md shadow-sm bg-gray-800 text-white focus:outline-none focus:ring-blue-500 focus:border-blue-500"
              autocomplete="username"
            />
            <button
              type="button"
              @click="generateRandomUsername"
              class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-700 rounded-r-md bg-gray-700 text-gray-300 hover:bg-gray-600 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
              title="Generate Random Username"
            >
              <!-- Simpler refresh SVG icon -->
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m-15.357-2A8.001 8.001 0 0019.418 15m0 0H15" />
              </svg>
            </button>
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

        <!-- Proof of Work CAPTCHA -->
        <div class="bg-gray-800 bg-opacity-50 p-4 rounded-md border border-gray-700">
          <div class="flex items-center justify-between mb-2">
            <h3 class="text-sm font-medium text-gray-300">Human Verification</h3>
            <div class="text-xs text-gray-400" v-if="powChallenge && !powComplete">
              Solving...
            </div>
            <div class="text-xs text-green-400" v-if="powComplete">
              <div class="flex items-center">
                <svg class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                Verification Complete
              </div>
            </div>
          </div>
          
          <div v-if="!powChallenge && !powComplete" class="text-center">
            <p class="text-xs text-gray-400 mb-3">To protect our service from spam, we require a quick verification step before registration.</p>
            <button 
              type="button" 
              @click="fetchPowChallenge().then(() => solvePowChallenge())"
              class="w-full py-2 px-3 border border-transparent text-xs rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:ring-blue-500 focus:ring-offset-gray-800">
              Start Verification
            </button>
          </div>
          
          <div v-else-if="powSolving && !powComplete" class="text-center">
            <p class="text-xs text-gray-400 mb-3">Please wait while we verify your browser. This process should take a few seconds...</p>
            <div class="w-full bg-gray-700 rounded-full h-2.5 mb-2 overflow-hidden">
              <div class="bg-blue-600 h-2.5 rounded-full" :style="{ width: (powProgress * 100) + '%' }"></div>
            </div>
            <p class="text-xs text-gray-500">{{Math.round(powProgress * 100)}}% complete</p>
          </div>
          
          <div v-else-if="powComplete" class="text-xs text-gray-400">
            <p>Verification successful! You can now create your account.</p>
          </div>
        </div>
        
        <div>
          <button
            type="submit"
            :disabled="isRegistering || !acceptTerms || !powComplete"
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

// Add lists for random username generation
const adjectives = ["Quick", "Lazy", "Sleepy", "Noisy", "Hungry", "Brave", "Calm", "Eager", "Fancy", "Happy", "Jolly", "Kind", "Lively", "Nice", "Proud", "Silly", "Witty", "Zealous"];
const nouns = ["Fox", "Dog", "Cat", "Bear", "Lion", "Tiger", "Wolf", "Puma", "Hawk", "Eagle", "Pigeon", "Duck", "Goose", "Owl", "Crow", "Raven", "Finch", "Sparrow"];


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
      errors: [],
      // Proof of Work CAPTCHA data
      powChallenge: null,
      powNoncePrefix: null,
      powDifficulty: 4,
      powSolving: false,
      powComplete: false,
      powVerificationToken: null,
      powProgress: 0,
      powWorker: null
    };
  },
  methods: {
    generateRandomUsername() {
      const randomAdjective = adjectives[Math.floor(Math.random() * adjectives.length)];
      const randomNoun = nouns[Math.floor(Math.random() * nouns.length)];
      const randomNumber = Math.floor(100 + Math.random() * 900); // Generates a 3-digit number
      this.username = `${randomAdjective}${randomNoun}${randomNumber}`;
    },
    
    // Proof of Work CAPTCHA methods
    async fetchPowChallenge() {
      try {
        console.log('[PoW] Fetching challenge from server...');
        const response = await fetch('/api/pow/challenge');
        if (!response.ok) {
          throw new Error('Failed to fetch CAPTCHA challenge');
        }
        
        const data = await response.json();
        this.powChallenge = data.challenge;
        this.powNoncePrefix = data.noncePrefix;
        
        // Enforce a reasonable difficulty for mobile devices (max 2)
        // Check if we're likely on a mobile device based on screen size
        const isMobileDevice = window.innerWidth < 768;
        this.powDifficulty = isMobileDevice ? Math.min(2, data.difficulty) : data.difficulty;
        
        console.log('[PoW] Challenge received:', {
          challenge: this.powChallenge,
          noncePrefix: this.powNoncePrefix,
          serverDifficulty: data.difficulty,
          actualDifficulty: this.powDifficulty, 
          isMobileDevice: isMobileDevice,
          screenWidth: window.innerWidth
        });
        
        this.powComplete = false;
        this.powProgress = 0;
        this.powVerificationToken = null;
        
        return data;
      } catch (error) {
        this.errors.push('Failed to fetch CAPTCHA challenge. Please try again.');
        return null;
      }
    },
    
    async solvePowChallenge() {
      if (!this.powChallenge || !this.powNoncePrefix) {
        await this.fetchPowChallenge();
      }
      
      if (!this.powChallenge || !this.powNoncePrefix) {
        return;
      }
      
      this.powSolving = true;
      
      // Create a Web Worker to solve the proof of work without blocking the UI
      const workerCode = `
        // SHA-256 hash function using Web Crypto API
        async function sha256(message) {
          // Convert string to an ArrayBuffer
          const encoder = new TextEncoder();
          const data = encoder.encode(message);
          
          // Hash the message using Web Crypto API
          const hashBuffer = await crypto.subtle.digest('SHA-256', data);
          
          // Convert ArrayBuffer to hex string
          const hashArray = Array.from(new Uint8Array(hashBuffer));
          const hashHex = hashArray.map(b => b.toString(16).padStart(2, '0')).join('');
          
          return hashHex;
        }
        
        self.onmessage = async function(e) {
          const { challenge, noncePrefix, difficulty } = e.data;
          const requiredPrefix = '0'.repeat(difficulty);
          
          // Log worker start
          self.postMessage({ 
            type: 'log', 
            message: '[PoW Worker] Starting proof of work calculation',
            data: { challenge, noncePrefix, difficulty, requiredPrefix }
          });
          
          const startTime = performance.now();
          let counter = 0;
          const maxAttempts = 10000000; // Limit to prevent infinite loops
          let nonce = noncePrefix;
          let lastLogTime = startTime;
          
          while (counter < maxAttempts) {
            // Try incremental suffixes
            nonce = noncePrefix + counter.toString(36);
            
            // Calculate hash
            const hash = await sha256(challenge + nonce);
            
            // Check if it meets the difficulty requirement
            if (hash.substring(0, difficulty) === requiredPrefix) {
              const endTime = performance.now();
              const duration = (endTime - startTime) / 1000; // in seconds
              
              self.postMessage({ 
                type: 'log',
                message: '[PoW Worker] Solution found!', 
                data: {
                  nonce,
                  hash,
                  attemptsCount: counter,
                  duration: duration.toFixed(2) + 's'
                }
              });
              
              self.postMessage({ success: true, nonce, hash });
              return;
            }
            
            // Report progress periodically
            if (counter % 10000 === 0) {
              const currentTime = performance.now();
              
              // Log every ~3 seconds
              if (currentTime - lastLogTime > 3000) {
                lastLogTime = currentTime;
                const elapsedTime = (currentTime - startTime) / 1000; // in seconds
                const hashesPerSecond = Math.round(counter / elapsedTime);
                
                self.postMessage({
                  type: 'log',
                  message: '[PoW Worker] Working...',
                  data: {
                    attempts: counter,
                    elapsedTime: elapsedTime.toFixed(2) + 's',
                    hashesPerSecond: hashesPerSecond + ' hashes/s'
                  }
                });
              }
              
              self.postMessage({ 
                success: false, 
                progress: Math.min(counter / 1000000, 0.95) // Cap at 95%
              });
            }
            
            counter++;
          }
          
          // If we reach here, we failed to find a solution
          self.postMessage({ 
            type: 'log',
            message: '[PoW Worker] Failed after maximum attempts',
            data: { maxAttempts }
          });
          
          self.postMessage({ success: false, error: 'Failed to solve the CAPTCHA challenge' });
        };
      `;
      
      // Create a Blob containing the worker code
      const blob = new Blob([workerCode], { type: 'application/javascript' });
      const workerUrl = URL.createObjectURL(blob);
      
      this.powWorker = new Worker(workerUrl);
      
      return new Promise((resolve, reject) => {
        this.powWorker.onmessage = async (e) => {
          const { success, nonce, hash, progress, error } = e.data;
          
          if (progress !== undefined) {
            this.powProgress = progress;
            return;
          }
          
          if (error) {
            this.powSolving = false;
            this.errors.push(error);
            this.powWorker.terminate();
            this.powWorker = null;
            reject(error);
            return;
          }
          
          if (success) {
            try {
              // Verify the solution with the server
              const verifyResponse = await fetch('/api/pow/verify', {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/json',
                  'Accept': 'application/json',
                  'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                  challenge: this.powChallenge,
                  nonce: nonce
                })
              });
              
              const verifyData = await verifyResponse.json();
              
              if (!verifyResponse.ok || !verifyData.valid) {
                throw new Error(verifyData.message || 'Failed to verify CAPTCHA solution');
              }
              
              // Store the verification token
              this.powVerificationToken = verifyData.verification_token;
              this.powComplete = true;
              this.powSolving = false;
              this.powProgress = 1; // 100%
              
              // Clean up
              this.powWorker.terminate();
              this.powWorker = null;
              URL.revokeObjectURL(workerUrl);
              
              resolve(true);
            } catch (error) {
              this.powSolving = false;
              this.errors.push(error.message || 'Failed to verify CAPTCHA solution');
              
              // Clean up
              this.powWorker.terminate();
              this.powWorker = null;
              URL.revokeObjectURL(workerUrl);
              
              reject(error);
            }
          }
        };
        
        // Start the worker
        this.powWorker.postMessage({
          challenge: this.powChallenge,
          noncePrefix: this.powNoncePrefix,
          difficulty: this.powDifficulty
        });
      });
    },

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
      
      // Verify proof of work CAPTCHA
      if (!this.powVerificationToken) {
        this.errors.push('Please complete the CAPTCHA verification first.');
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
            password_confirmation: this.passwordConfirmation,
            pow_token: this.powVerificationToken
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