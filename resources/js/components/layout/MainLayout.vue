<template>
  <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
    <PublicNavigation />
    
    <!-- Page Content -->
    <main :class="[
      route().current('home') ? '' : 'pt-16' // Tambah padding-top yang lebih besar (7rem/112px)
    ]">
      <slot />
    </main>
    
    <!-- Footer -->
    <Footer />
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import PublicNavigation from '@/components/layout/PublicNavigation.vue';
import Footer from '@/components/layout/Footer.vue';
</script> 