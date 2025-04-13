<template>
  <div class="flex flex-col items-center justify-center min-h-[60vh] text-center px-4">
    <h1 class="text-4xl font-bold text-red-500 mb-4">404</h1>
    <h2 class="text-2xl font-semibold mb-6">Page Not Found</h2>
    <p class="text-gray-400 mb-8">The page you're looking for doesn't exist or has been moved.</p>
    <router-link to="/" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg">
      Return to Home
    </router-link>
  </div>
</template>

<script>
export default {
  name: 'PageNotFound'
}
</script>