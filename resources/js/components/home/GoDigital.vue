<template>
  <section class="py-20 bg-background">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
        <div>
          <h2 class="text-4xl font-bold text-primary mb-4">
            Saatnya Go Digital..
          </h2>
          <p class="text-lg text-foreground mb-8">
            Optimalkan pemasaran digital bisnis Anda, untuk mendapatkan omset berlipat ganda
          </p>
          <Button variant="default" class="inline-flex items-center gap-2 px-6 py-3" @click.prevent>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Form Konsultasi
          </Button>
        </div>
        <div class="relative">
          <img src="/images/hero-dashboard.svg" alt="Business Statistics" class="w-full h-auto rounded-lg shadow-lg" />
          <div class="absolute inset-0 flex items-center justify-center">
            <Button variant="icon" class="w-16 h-16 bg-primary rounded-full flex items-center justify-center shadow-lg hover:bg-opacity-90 transition-colors">
              <svg class="w-8 h-8 text-primary-foreground" fill="currentColor" viewBox="0 0 24 24">
                <path d="M8 5v14l11-7z" />
              </svg>
            </Button>
          </div>
        </div>
      </div>

      <!-- Process Steps -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-20">
        <div class="text-center">
          <h3 class="text-xl font-bold text-primary mb-3">Siap Konsultasi</h3>
          <p class="text-foreground">
            Mau tanya-tanya sebelum order, kami sediakan ruang diskusi via WhatsApp atau Google Meet
          </p>
        </div>
        <div class="text-center">
          <h3 class="text-xl font-bold text-primary mb-3">Siap Membantu</h3>
          <p class="text-foreground">
            Pelayanan kepada client oleh tim Dilogif ketika mengalami kesulitan pasca order project
          </p>
        </div>
        <div class="text-center">
          <h3 class="text-xl font-bold text-primary mb-3">Siap Amanah</h3>
          <p class="text-foreground">
            Transaksi dijelaskan di awal akad order, garansi project selesai sesuai jadwal
          </p>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import Button from '@/components/ui/button/Button.vue'
</script> 