<template>
  <section class="relative min-h-[80vh] flex items-center">
    <!-- Background Image with Overlay -->
    <div class="absolute inset-0 z-0">
      <img 
        src="/images/bg-home.jpg" 
        alt="Background" 
        class="w-full h-full object-cover opacity-80"
      />
      <div 
        class="absolute inset-0 bg-primary/50 mix-blend-multiply dark:bg-background/90 dark:mix-blend-normal transition-colors"
      ></div>
    </div>

    <!-- Content -->
    <div class="relative z-10 w-full pt-28 pb-20">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
          <div>
            <h1 class="text-4xl md:text-5xl font-bold mb-6 text-white">
              Buat Bisnis Anda Makin Dikenal Luas, Mau?
            </h1>
            <p class="text-lg mb-8 text-white/90">
              Kami hadir untuk membantu bisnis Anda untuk berkembang, 
              melalui jasa digital marketing yang profesional.
            </p>
            <div class="flex flex-wrap gap-4">
              <Button variant="default" class="px-8 py-4" @click.prevent> Cek Sekarang </Button>
              <Button variant="outline" class="px-8 py-4 text-white border-white hover:bg-white hover:text-primary transition-all" @click.prevent> Tanya CS </Button>
            </div>
          </div>
          <div class="hidden md:block">
            <img 
              src="/images/hero-dashboard.svg" 
              alt="Dashboard Preview" 
              class="w-full h-auto max-w-lg mx-auto"
            />
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import Button from '@/components/ui/button/Button.vue'
</script> 