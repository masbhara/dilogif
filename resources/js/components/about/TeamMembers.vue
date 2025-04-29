<script setup>
import Button from '@/components/ui/button/Button.vue'

const team = [
  {
    name: 'John Doe',
    position: 'CEO & Founder',
    image: '/images/hero-dashboard.svg',
    bio: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.'
  },
  {
    name: 'Jane Smith',
    position: 'CTO',
    image: '/images/hero-dashboard.svg',
    bio: 'Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'
  },
  // Tambahkan anggota tim lainnya sesuai kebutuhan
]
</script>

<template>
  <section class="py-12 bg-background ">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center">
        <h2 class="text-3xl font-bold text-foreground sm:text-4xl">Our Team</h2>
        <p class="mt-4 text-lg text-muted-foreground">Meet the amazing people behind our success</p>
      </div>
      <div class="mt-12 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
        <div v-for="member in team" :key="member.name" class="bg-background rounded-lg shadow-lg overflow-hidden">
          <img :src="member.image" :alt="member.name" class="w-full h-64 object-cover">
          <div class="p-6">
            <h3 class="text-xl font-semibold text-foreground">{{ member.name }}</h3>
            <p class="mt-1 text-sm text-muted-foreground">{{ member.position }}</p>
            <p class="mt-3 text-base text-muted-foreground">{{ member.bio }}</p>
          </div>
        </div>
      </div>
    </div>
  </section>

    <!-- CTA Section -->
    <section class="py-20 bg-background ">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="bg-gradient-to-r from-primary to-primary/90 rounded-2xl overflow-hidden shadow-lg">
        <div class="grid grid-cols-1 lg:grid-cols-2 items-center">
          <!-- CTA Content -->
          <div class="p-8 lg:p-12">
            <h2 class="text-3xl font-bold text-white mb-4">Siap Memulai Perjalanan Digital Anda?</h2>
            <p class="text-white/80 mb-8 text-lg">
              Kami siap membantu Anda menghadapi tantangan digital dan mengubahnya menjadi peluang untuk pertumbuhan bisnis.
            </p>
            
            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-4">
              <Button variant="secondary" class="px-8 py-4 inline-flex items-center gap-2" as="a" href="/contact">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M14 9a2 2 0 0 1-2 2H6l-4 4V4c0-1.1.9-2 2-2h8a2 2 0 0 1 2 2v5Z"/>
                  <path d="M18 9h2a2 2 0 0 1 2 2v11l-4-4h-6a2 2 0 0 1-2-2v-1"/>
                </svg>
                Hubungi Kami
              </Button>
              <Button variant="outline" class="px-8 py-4 inline-flex items-center gap-2 border-white text-white hover:bg-white hover:text-primary transition-all" as="a" href="/services">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="m7 10 5 5 5-5"/>
                  <path d="M12 15V3"/>
                </svg>
                Lihat Layanan Kami
              </Button>
            </div>
          </div>
          
          <!-- Decorative Illustration -->
          <div class="hidden lg:flex justify-end">
            <div class="relative p-12">
              <!-- Decorative Elements -->
              <div class="absolute top-0 left-0 w-40 h-40 bg-white opacity-5 rounded-full"></div>
              <div class="absolute bottom-0 right-0 w-60 h-60 bg-white opacity-5 rounded-full"></div>
              
              <!-- Illustration -->
              <img 
                src="/images/hero-dashboard.svg" 
                alt="Start Your Digital Journey" 
                class="w-full max-w-md relative z-10"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template> 