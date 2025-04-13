<template>
  <div>
    <!-- This component handles client-side actions for account deletion -->
    <div v-if="deletionStatus" class="p-4 bg-green-900 bg-opacity-50 text-green-100 rounded-md border border-green-800 mb-4">
      <div class="flex">
        <svg class="h-5 w-5 text-green-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span>{{ deletionStatus }}</span>
      </div>
    </div>
    
    <div v-if="error" class="p-4 bg-red-900 bg-opacity-50 text-red-100 rounded-md border border-red-800 mb-4">
      <div class="flex">
        <svg class="h-5 w-5 text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
        </svg>
        <span>{{ error }}</span>
      </div>
    </div>
  </div>
</template>

<script>
import CryptoService from '../services/CryptoService';

export default {
  data() {
    return {
      username: '',
      error: null,
      deletionStatus: null,
      cryptoService: CryptoService
    };
  },
  
  mounted() {
    // Get username from meta tag
    const usernameMeta = document.querySelector('meta[name="username"]');
    if (usernameMeta) {
      this.username = usernameMeta.getAttribute('content');
    }
    
    // Listen for the form submission event
    this.setupFormHandler();
  },
  
  methods: {
    /**
     * Set up a form submission handler to clean client-side data
     * when the account is deleted
     */
    setupFormHandler() {
      const form = document.querySelector('form[action*="account/delete"]');
      if (!form) return;
      
      form.addEventListener('submit', async (event) => {
        // Don't prevent default - let the form submit normally to the server
        // This is just to handle the client-side data deletion
        
        try {
          // Delete the user's keys from IndexedDB
          if (this.username) {
            await this.cryptoService.deleteUserData(this.username);
            console.log('Local user data deleted');
          }
        } catch (error) {
          console.error('Error deleting local user data:', error);
          // Don't prevent form submission even if local deletion fails
        }
      });
    }
  }
};
</script> 