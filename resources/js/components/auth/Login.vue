<template>
  <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl border border-gray-700 p-8 max-w-md mx-auto shadow-xl">
    <!-- Background decorative elements -->
    <div class="absolute inset-0 overflow-hidden opacity-5 rounded-xl pointer-events-none">
      <div class="absolute -top-10 -left-10 w-40 h-40 bg-blue-600 rounded-full filter blur-3xl opacity-20"></div>
      <div class="absolute top-20 -right-10 w-40 h-40 bg-blue-800 rounded-full filter blur-3xl opacity-20"></div>
    </div>
    
    <h2 class="text-2xl font-bold mb-2 text-white text-center">Welcome Back</h2>
    <p class="text-gray-400 text-sm text-center mb-6">Sign in to your secure messaging account</p>
    
    <div v-if="errorMessage" class="bg-red-900 border border-red-800 text-white px-4 py-3 rounded-md mb-6">
      {{ errorMessage }}
    </div>
    
    <form @submit.prevent="handleSubmit" class="space-y-6 relative z-10">
      <div>
        <label for="username" class="block text-sm font-medium text-gray-300">Username</label>
        <div class="mt-1 relative rounded-md shadow-sm">
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
            </svg>
          </div>
          <input 
            type="text" 
            id="username" 
            v-model="form.username" 
            class="pl-10 mt-1 block w-full px-3 py-2 border border-gray-700 rounded-md shadow-sm bg-gray-800 text-white focus:outline-none focus:ring-blue-500 focus:border-blue-500"
            required
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
            type="password" 
            id="password" 
            v-model="form.password" 
            class="pl-10 mt-1 block w-full px-3 py-2 border border-gray-700 rounded-md shadow-sm bg-gray-800 text-white focus:outline-none focus:ring-blue-500 focus:border-blue-500"
            required
            autocomplete="current-password"
          />
        </div>
      </div>
      
      <div class="flex items-center justify-between">
        <div class="flex items-center">
          <input 
            id="remember" 
            name="remember" 
            type="checkbox" 
            v-model="form.remember"
            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-700 bg-gray-800 rounded"
          />
          <label for="remember" class="ml-2 block text-sm text-gray-400">
            Remember me
          </label>
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
          <p class="text-xs text-gray-400 mb-3">To protect your account from automated attacks, please complete this verification.</p>
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
          <p>Verification successful! You can now sign in.</p>
        </div>
      </div>
      
      <div>
        <button 
          type="submit" 
          class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-md transition-all duration-150 ease-in-out"
          :disabled="isLoading || !powComplete"
        >
          <span class="absolute left-0 inset-y-0 flex items-center pl-3">
            <svg class="h-5 w-5 text-blue-400 group-hover:text-blue-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
            </svg>
          </span>
          <span v-if="isLoading" class="flex items-center">
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Signing in...
          </span>
          <span v-else>Sign in</span>
        </button>
      </div>
      
      <div class="text-sm text-center text-gray-400">
        Don't have an account? <a href="/register" class="font-medium text-blue-500 hover:text-blue-400">Create an account</a>
      </div>
      
      <div class="pt-4 border-t border-gray-700 mt-6">
        <p class="text-xs text-center text-gray-500">
          By signing in, you agree to our 
          <a href="/terms" class="text-blue-500 hover:text-blue-400">Terms of Service</a> and 
          <a href="/privacy" class="text-blue-500 hover:text-blue-400">Privacy Policy</a>
        </p>
      </div>
    </form>
  </div>
</template>

<script>
export default {
  data() {
    return {
      form: {
        username: '',
        password: '',
        remember: false
      },
      isLoading: false,
      errorMessage: '',
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
        this.errorMessage = 'Failed to fetch CAPTCHA challenge. Please try again.';
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
          
          let counter = 0;
          const maxAttempts = 10000000; // Limit to prevent infinite loops
          let nonce = noncePrefix;
          
          while (counter < maxAttempts) {
            // Try incremental suffixes
            nonce = noncePrefix + counter.toString(36);
            
            // Calculate hash
            const hash = await sha256(challenge + nonce);
            
            // Check if it meets the difficulty requirement
            if (hash.substring(0, difficulty) === requiredPrefix) {
              self.postMessage({ success: true, nonce, hash });
              return;
            }
            
            // Report progress periodically
            if (counter % 10000 === 0) {
              self.postMessage({ 
                success: false, 
                progress: Math.min(counter / 1000000, 0.95) // Cap at 95%
              });
            }
            
            counter++;
          }
          
          // If we reach here, we failed to find a solution
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
            this.errorMessage = error;
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
              this.errorMessage = error.message || 'Failed to verify CAPTCHA solution';
              
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
    
    async handleSubmit() {
      this.isLoading = true;
      this.errorMessage = '';
      
      // Verify proof of work CAPTCHA
      if (!this.powVerificationToken) {
        this.errorMessage = 'Please complete the verification first.';
        this.isLoading = false;
        return;
      }
      
      try {
        const response = await fetch('/login', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify({
            username: this.form.username,
            password: this.form.password,
            remember: this.form.remember ? 1 : 0,
            pow_token: this.powVerificationToken
          })
        });
        
        const data = await response.json();
        
        if (response.ok) {
          // Successful login - redirect to messages page
          window.location.href = '/messages';
          return;
        }
        
        // Handle error response
        if (data.errors) {
          const errorMessages = [];
          
          // Format Laravel validation errors
          Object.entries(data.errors).forEach(([field, messages]) => {
            if (Array.isArray(messages)) {
              messages.forEach(message => errorMessages.push(message));
            } else {
              errorMessages.push(messages);
            }
          });
          
          this.errorMessage = errorMessages.join(' ');
        } else if (data.message) {
          this.errorMessage = data.message;
        } else {
          this.errorMessage = 'Invalid credentials. Please try again.';
        }
      } catch (error) {
        console.error('Login error:', error);
        this.errorMessage = 'An unexpected error occurred. Please try again.';
      } finally {
        this.isLoading = false;
      }
    }
  }
};
</script>