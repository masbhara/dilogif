<template>
  <section class="py-20 bg-gray-50 dark:bg-background">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-slate-900 rounded-2xl p-8 lg:p-12 shadow-sm">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
          <!-- Contact Header -->
          <div>
            <div class="flex items-center gap-3 mb-6">
              <div class="w-12 h-12 rounded-full bg-primary flex items-center justify-center text-primary-foreground">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                </svg>
              </div>
              <h2 class="text-2xl font-bold text-foreground">Hubungi Kami</h2>
            </div>
            
            <h1 class="text-4xl font-bold leading-tight mb-6 text-foreground">
              Mari Diskusikan <span class="text-primary">Project Anda</span> Bersama Kami
            </h1>
            
            <p class="text-lg mb-8 text-muted-foreground">
              Tim kami siap membantu menjawab pertanyaan dan memberikan konsultasi untuk kebutuhan digital bisnis Anda.
            </p>
            
            <!-- Contact Information Cards -->
            <div class="space-y-4 mb-8">
              <div class="bg-background p-4 rounded-lg shadow-sm flex items-start gap-4">
                <div class="w-10 h-10 rounded-full bg-primary/10 flex-shrink-0 flex items-center justify-center text-primary">
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                    <polyline points="22,6 12,13 2,6"></polyline>
                  </svg>
                </div>
                <div>
                  <h3 class="font-medium text-foreground">Email</h3>
                  <a href="mailto:info@dilogif.com" class="text-primary hover:underline">info@dilogif.com</a>
                </div>
              </div>
              
              <div class="bg-background p-4 rounded-lg shadow-sm flex items-start gap-4">
                <div class="w-10 h-10 rounded-full bg-primary/10 flex-shrink-0 flex items-center justify-center text-primary">
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M15.05 5A5 5 0 0 1 19 8.95M15.05 1A9 9 0 0 1 23 8.94m-1 7.98v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                  </svg>
                </div>
                <div>
                  <h3 class="font-medium text-foreground">Telepon</h3>
                  <a href="tel:+6281234567890" class="text-primary hover:underline">+62 812 3456 7890</a>
                </div>
              </div>
            </div>
            
            <!-- Social Media Links -->
            <div>
              <h3 class="font-medium text-foreground mb-3">Media Sosial</h3>
              <div class="flex gap-3">
                <a href="#" class="w-10 h-10 rounded-full bg-primary flex items-center justify-center text-primary-foreground hover:bg-primary/90 transition-colors">
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                  </svg>
                </a>
                <a href="#" class="w-10 h-10 rounded-full bg-primary flex items-center justify-center text-primary-foreground hover:bg-primary/90 transition-colors">
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                    <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                    <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                  </svg>
                </a>
                <a href="#" class="w-10 h-10 rounded-full bg-primary flex items-center justify-center text-primary-foreground hover:bg-primary/90 transition-colors">
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path>
                  </svg>
                </a>
              </div>
            </div>
          </div>
          
          <!-- Contact Form Preview -->
          <div class="relative">
            <div class="absolute -top-6 -right-6 w-24 h-24 bg-primary opacity-10 rounded-full"></div>
            <div class="absolute -bottom-6 -left-6 w-16 h-16 bg-primary opacity-15 rounded-full"></div>
            
            <div class="bg-background p-6 lg:p-8 rounded-xl shadow-lg relative z-10">
              <h3 class="text-xl font-bold mb-6 text-foreground">Formulir Kontak</h3>
              
              <div class="space-y-4 mb-6">
                <div class="grid grid-cols-2 gap-4">
                  <div class="bg-muted rounded-lg h-12"></div>
                  <div class="bg-muted rounded-lg h-12"></div>
                </div>
                <div class="bg-muted rounded-lg h-12"></div>
                <div class="bg-muted rounded-lg h-36"></div>
              </div>
              
              <Button variant="primary" class="w-full py-3 px-4" as="a" href="#contact-form">
                Isi Formulir Sekarang
              </Button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import Button from '@/components/ui/button/Button.vue'
</script> 