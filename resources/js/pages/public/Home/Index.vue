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
    <section class="bg-[#3B8BEE] text-[#FCFDFD] py-20">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
          <div>
            <h1 class="text-4xl md:text-5xl font-bold mb-6">
              Buat Bisnis Anda Makin Dikenal Luas, Mau?
            </h1>
            <p class="text-lg mb-8">
              Kami hadir untuk membantu bisnis Anda untuk berkembang, 
              melalui jasa digital marketing yang profesional.
            </p>
            <div class="flex gap-4">
              <a href="#" class="bg-white text-[#3B8BEE] px-6 py-3 rounded-lg font-medium hover:bg-opacity-90 transition-colors">
                Cek Sekarang
              </a>
              <a href="#" class="border border-white px-6 py-3 rounded-lg font-medium hover:bg-white hover:text-[#3B8BEE] transition-colors">
                Tanya CS
              </a>
            </div>
          </div>
          <div class="hidden md:block">
            <img src="/images/hero-dashboard.svg" alt="Dashboard Preview" class="w-full h-auto" />
          </div>
        </div>
      </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="py-20 bg-[#FCFDFD]">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-center text-[#343434] mb-12">
          Mengapa Bisnis Harus Dikenal Banyak Orang?
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div class="p-6 rounded-lg bg-white shadow-sm">
            <div class="w-12 h-12 bg-[#3B8BEE] rounded-full flex items-center justify-center mb-4">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
              </svg>
            </div>
            <h3 class="text-xl font-semibold mb-3 text-[#343434]">Peringkat Tidak Bagus</h3>
            <p class="text-[#343434]">
              Jika bisnis Anda tidak dikenal, maka akan sulit untuk mendapatkan pelanggan yang potensial.
            </p>
          </div>
          <div class="p-6 rounded-lg bg-white shadow-sm">
            <div class="w-12 h-12 bg-[#3B8BEE] rounded-full flex items-center justify-center mb-4">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
              </svg>
            </div>
            <h3 class="text-xl font-semibold mb-3 text-[#343434]">Bisa Meningkatkan Omzet</h3>
            <p class="text-[#343434]">
              Dengan dikenal banyak orang, potensi mendapatkan pelanggan semakin tinggi.
            </p>
          </div>
          <div class="p-6 rounded-lg bg-white shadow-sm">
            <div class="w-12 h-12 bg-[#3B8BEE] rounded-full flex items-center justify-center mb-4">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
              </svg>
            </div>
            <h3 class="text-xl font-semibold mb-3 text-[#343434]">Bisnis Bisa Berkembang</h3>
            <p class="text-[#343434]">
              Saat bisnis Anda dikenal luas, peluang untuk berkembang semakin besar.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- Services Section -->
    <section class="py-20 bg-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-center text-[#343434] mb-4">
          Solusi Untuk Bisnis Anda
        </h2>
        <p class="text-center text-[#343434] mb-12">
          Jadikan bisnis Anda agar semakin dikenal publik dengan profesional
        </p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div class="bg-white p-6 rounded-lg shadow-sm">
            <img src="/images/web-design.svg" alt="Web Design" class="w-full h-auto mb-4" />
            <h3 class="text-xl font-semibold mb-2 text-[#343434]">Web Design</h3>
            <p class="text-[#343434] mb-4">
              Website yang menarik untuk meningkatkan kredibilitas bisnis Anda
            </p>
            <a href="#" class="text-[#3B8BEE] font-medium hover:underline">Learn More →</a>
          </div>
          <div class="bg-white p-6 rounded-lg shadow-sm">
            <img src="/images/logo-design.svg" alt="Logo Design" class="w-full h-auto mb-4" />
            <h3 class="text-xl font-semibold mb-2 text-[#343434]">Logo Design</h3>
            <p class="text-[#343434] mb-4">
              Logo yang profesional untuk meningkatkan brand awareness
            </p>
            <a href="#" class="text-[#3B8BEE] font-medium hover:underline">Learn More →</a>
          </div>
          <div class="bg-white p-6 rounded-lg shadow-sm">
            <img src="/images/content-creation.svg" alt="Content Creation" class="w-full h-auto mb-4" />
            <h3 class="text-xl font-semibold mb-2 text-[#343434]">Content Creation</h3>
            <p class="text-[#343434] mb-4">
              Konten yang menarik untuk meningkatkan engagement
            </p>
            <a href="#" class="text-[#3B8BEE] font-medium hover:underline">Learn More →</a>
          </div>
        </div>
      </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-20 bg-[#3B8BEE]">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-center text-white mb-12">FAQ's</h2>
        <div class="max-w-3xl mx-auto space-y-4">
          <div v-for="(faq, index) in faqs" :key="index" class="bg-white rounded-lg">
            <button 
              class="w-full px-6 py-4 text-left flex justify-between items-center"
              @click="toggleFaq(index)"
            >
              <span class="font-medium text-[#343434]">{{ faq.question }}</span>
              <svg 
                class="w-5 h-5 text-[#3B8BEE] transform transition-transform"
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
              class="px-6 pb-4 text-[#343434]"
            >
              {{ faq.answer }}
            </div>
          </div>
        </div>
      </div>
    </section>
  </MainLayout>
</template> 