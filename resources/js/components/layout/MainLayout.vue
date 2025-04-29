<template>
  <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
    <PublicNavigation />
    
    <!-- Page Content -->
    <main>
      <slot />
    </main>
    
    <!-- Footer -->
    <Footer />
  </div>
</template>

<script setup>
import PublicNavigation from '@/components/layout/PublicNavigation.vue';
import Footer from '@/components/layout/Footer.vue';
</script> 