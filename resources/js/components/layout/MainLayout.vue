<template>
  <div class="min-h-screen bg-[#FCFDFD] text-[#343434]">
    <Navigation />
    
    <main>
      <slot />
    </main>

    <Footer />
    
    <!-- WhatsApp Floating Button -->
    <WhatsAppButton />
  </div>
</template>

<script setup>
import Navigation from '@/components/layout/Navigation.vue'
import Footer from '@/components/layout/Footer.vue'
import WhatsAppButton from '@/components/WhatsAppButton.vue'
</script> 