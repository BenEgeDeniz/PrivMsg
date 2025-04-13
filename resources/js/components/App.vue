<template>
  <div class="min-h-screen bg-gray-900 text-white">
    <header v-if="isAuthenticated" class="bg-gray-800 p-4 shadow-md">
      <div class="container mx-auto flex justify-between items-center">
        <h1 class="text-xl font-bold">PrivMsg - Secure Messaging</h1>
        <div class="flex gap-4">
          <span v-if="user" class="text-gray-300">{{ user.username }}</span>
          <button @click="logout" class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded-md text-sm font-medium">
            Logout
          </button>
        </div>
      </div>
    </header>
    
    <main class="container mx-auto p-4">
      <router-view />
    </main>
    
    <footer class="bg-gray-800 p-4 text-center text-gray-400 text-sm mt-8">
      <p>Military-grade E2EE Messaging - All messages are end-to-end encrypted</p>
    </footer>
  </div>
</template>

<script>
import { computed } from 'vue';
import { useStore } from 'vuex';
import { useRouter } from 'vue-router';

export default {
  name: 'App',
  setup() {
    const store = useStore();
    const router = useRouter();
    
    // Computed
    const user = computed(() => store.state.user);
    const isAuthenticated = computed(() => store.state.authenticated);
    
    // Methods
    const logout = async () => {
      await store.dispatch('logout');
      router.push('/login');
    };
    
    return {
      user,
      isAuthenticated,
      logout
    };
  }
}
</script>