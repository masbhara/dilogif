<template>
  <div class="min-h-screen bg-background">
    <PublicNavigation />
    
    <!-- Page Content -->
    <main :class="[
      route().current('home') ? '' : 'pt-16'
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