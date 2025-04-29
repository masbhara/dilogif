<script setup>
import { ref } from 'vue'
import MainLayout from '@/components/layout/MainLayout.vue'

const faqs = ref([
  {
    question: 'Bagaimana sistem pembayarannya?',
    answer: 'Kami menerima pembayaran melalui transfer bank dan e-wallet.',
    isOpen: false
  },
  {
    question: 'Berapa lama waktu pengerjaan project?',
    answer: 'Waktu pengerjaan tergantung pada kompleksitas project, biasanya 2-4 minggu.',
    isOpen: false
  },
  {
    question: 'Apakah semua project dikerjakan dilogif?',
    answer: 'Ya, semua project dikerjakan oleh tim internal dilogif.',
    isOpen: false
  },
  {
    question: 'Tim Dilogif online 24 jam?',
    answer: 'Tim support kami online dari Senin-Jumat, 09.00-17.00 WIB.',
    isOpen: false
  }
])

const toggleFaq = (index) => {
  faqs.value[index].isOpen = !faqs.value[index].isOpen
}
</script>

<template>
  <MainLayout>
    <!-- Hero Section -->
    <section class="relative min-h-[80vh] flex items-center">
      <!-- Background Image with Overlay -->
      <div class="absolute inset-0 z-0">
        <img 
          src="/images/bg-home.jpg" 
          alt="Background" 
          class="w-full h-full object-cover"
        />
        <div 
          class="absolute inset-0 bg-primary/80 mix-blend-multiply dark:bg-background/90 dark:mix-blend-normal transition-colors"
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
                <a 
                  href="#" 
                  class="bg-white text-primary px-8 py-4 rounded-lg font-medium hover:bg-opacity-90 transition-colors"
                >
                  Cek Sekarang
                </a>
                <a 
                  href="#" 
                  class="border-2 border-white text-white px-8 py-4 rounded-lg font-medium hover:bg-white hover:text-primary transition-all"
                >
                  Tanya CS
                </a>
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

    <!-- Why Choose Us Section -->
    <section class="py-20 bg-muted">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-center text-foreground mb-12">
          Mengapa Bisnis Harus Dikenal Banyak Orang?
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div class="p-6 rounded-lg bg-background shadow-sm">
            <div class="w-12 h-12 bg-primary rounded-full flex items-center justify-center mb-4">
              <svg class="w-6 h-6 text-primary-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
              </svg>
            </div>
            <h3 class="text-xl font-semibold mb-3 text-foreground">Peringkat Tidak Bagus</h3>
            <p class="text-foreground">
              Jika bisnis Anda tidak dikenal, maka akan sulit untuk mendapatkan pelanggan yang potensial.
            </p>
          </div>
          <div class="p-6 rounded-lg bg-background shadow-sm">
            <div class="w-12 h-12 bg-primary rounded-full flex items-center justify-center mb-4">
              <svg class="w-6 h-6 text-primary-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
              </svg>
            </div>
            <h3 class="text-xl font-semibold mb-3 text-foreground">Bisa Meningkatkan Omzet</h3>
            <p class="text-foreground">
              Dengan dikenal banyak orang, potensi mendapatkan pelanggan semakin tinggi.
            </p>
          </div>
          <div class="p-6 rounded-lg bg-background shadow-sm">
            <div class="w-12 h-12 bg-primary rounded-full flex items-center justify-center mb-4">
              <svg class="w-6 h-6 text-primary-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
              </svg>
            </div>
            <h3 class="text-xl font-semibold mb-3 text-foreground">Bisnis Bisa Berkembang</h3>
            <p class="text-foreground">
              Saat bisnis Anda dikenal luas, peluang untuk berkembang semakin besar.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- Go Digital Section -->
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
            <a href="#" class="inline-flex items-center gap-2 bg-primary text-primary-foreground px-6 py-3 rounded-lg font-medium hover:bg-opacity-90 transition-colors">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
              Form Konsultasi
            </a>
          </div>
          <div class="relative">
            <img src="/images/hero-dashboard.svg" alt="Business Statistics" class="w-full h-auto rounded-lg shadow-lg" />
            <div class="absolute inset-0 flex items-center justify-center">
              <button class="w-16 h-16 bg-primary rounded-full flex items-center justify-center shadow-lg hover:bg-opacity-90 transition-colors">
                <svg class="w-8 h-8 text-primary-foreground" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M8 5v14l11-7z" />
                </svg>
              </button>
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

    <!-- Services Section -->
    <section class="py-20 bg-background">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-center text-foreground mb-4">
          Solusi Untuk Bisnis Anda
        </h2>
        <p class="text-center text-foreground mb-12">
          Jadikan bisnis Anda agar semakin dikenal publik dengan profesional
        </p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div class="bg-background p-6 rounded-lg shadow-sm">
            <img src="/images/web-design.svg" alt="Web Design" class="w-full h-auto mb-4" />
            <h3 class="text-xl font-semibold mb-2 text-foreground">Web Design</h3>
            <p class="text-foreground mb-4">
              Website yang menarik untuk meningkatkan kredibilitas bisnis Anda
            </p>
            <a href="#" class="text-primary font-medium hover:underline">Learn More →</a>
          </div>
          <div class="bg-background p-6 rounded-lg shadow-sm">
            <img src="/images/logo-design.svg" alt="Logo Design" class="w-full h-auto mb-4" />
            <h3 class="text-xl font-semibold mb-2 text-foreground">Logo Design</h3>
            <p class="text-foreground mb-4">
              Logo yang profesional untuk meningkatkan brand awareness
            </p>
            <a href="#" class="text-primary font-medium hover:underline">Learn More →</a>
          </div>
          <div class="bg-background p-6 rounded-lg shadow-sm">
            <img src="/images/content-creation.svg" alt="Content Creation" class="w-full h-auto mb-4" />
            <h3 class="text-xl font-semibold mb-2 text-foreground">Content Creation</h3>
            <p class="text-foreground mb-4">
              Konten yang menarik untuk meningkatkan engagement
            </p>
            <a href="#" class="text-primary font-medium hover:underline">Learn More →</a>
          </div>
        </div>
      </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-20 bg-muted">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-center text-foreground mb-12">FAQ's</h2>
        <div class="max-w-3xl mx-auto space-y-4">
          <div v-for="(faq, index) in faqs" :key="index" class="bg-background rounded-lg shadow-sm">
            <button 
              class="w-full px-6 py-4 text-left flex justify-between items-center"
              @click="toggleFaq(index)"
            >
              <span class="font-medium text-foreground">{{ faq.question }}</span>
              <svg 
                class="w-5 h-5 text-primary transform transition-transform"
                :class="{ 'rotate-180': faq.isOpen }"
                fill="none" 
                stroke="currentColor" 
                viewBox="0 0 24 24"
              >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
            </button>
            <div 
              v-show="faq.isOpen"
              class="px-6 pb-4 text-foreground"
            >
              {{ faq.answer }}
            </div>
          </div>
        </div>
      </div>
    </section>
  </MainLayout>
</template> 