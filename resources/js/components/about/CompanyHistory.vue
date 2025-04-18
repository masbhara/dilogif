<template>
  <!-- Company Profile Banner -->
  <section class="bg-white py-16 lg:py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-center mb-10">
        <div class="bg-[#3B8BEE]/10 rounded-full px-6 py-1.5 inline-flex items-center gap-2">
          <span class="w-2.5 h-2.5 rounded-full bg-[#3B8BEE]"></span>
          <span class="text-sm font-medium text-[#3B8BEE]">Tentang Kami</span>
        </div>
      </div>
      
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center mb-16">
        <!-- Text Content -->
        <div>
          <h1 class="text-4xl lg:text-5xl font-bold text-[#343434] leading-tight mb-8">
            Menghadirkan <span class="text-[#3B8BEE]">Solusi Digital</span> untuk Bisnis Anda
          </h1>
          
          <div class="prose prose-lg text-[#343434]/80 max-w-none mb-8">
            <p>Dilogif didirikan dengan visi untuk membantu bisnis berkembang di era digital. Kami percaya bahwa teknologi yang tepat dapat mendorong pertumbuhan dan kesuksesan bisnis Anda.</p>
            <p>Tim kami terdiri dari para ahli yang berpengalaman dalam berbagai bidang digital. Dengan pendekatan yang kolaboratif, kami bekerja sama dengan klien untuk menciptakan solusi yang sesuai dengan kebutuhan mereka.</p>
          </div>
          
          <div class="grid grid-cols-2 gap-8">
            <div>
              <div class="text-3xl font-bold text-[#3B8BEE] mb-1">5+</div>
              <div class="text-sm text-[#343434]">Tahun Pengalaman</div>
            </div>
            <div>
              <div class="text-3xl font-bold text-[#3B8BEE] mb-1">200+</div>
              <div class="text-sm text-[#343434]">Klien Puas</div>
            </div>
          </div>
        </div>
        
        <!-- Visual Content -->
        <div class="relative">
          <div class="bg-[#3B8BEE]/10 rounded-tl-xl rounded-br-xl p-4 relative">
            <img 
              src="/images/hero-dashboard.svg" 
              alt="Tim Dilogif" 
              class="w-full h-auto object-cover rounded shadow-lg relative z-10"
            />
            
            <!-- Decorative Elements -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-[#3B8BEE]/20 rounded-full -translate-y-1/4 translate-x-1/4 z-0"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-[#3B8BEE]/15 rounded-full translate-y-1/4 -translate-x-1/4 z-0"></div>
          </div>
          
          <!-- Floating Badge -->
          <div class="absolute bottom-4 right-4 bg-white py-2 px-4 rounded-full shadow-lg flex items-center gap-3">
            <div class="w-8 h-8 bg-[#3B8BEE] rounded-full flex items-center justify-center">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
              </svg>
            </div>
            <span class="font-medium text-[#343434]">Pengalaman Terpercaya</span>
          </div>
        </div>
      </div>
    </div>
  </section>
  


  <!-- Values Section - Redesigned -->
  <section id="values" class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Section Header with Brand Colors -->
      <div class="flex flex-col items-center mb-16">
        <div class="bg-[#3B8BEE]/10 rounded-full px-6 py-1.5 mb-4 inline-flex items-center gap-2">
          <span class="w-2.5 h-2.5 rounded-full bg-[#3B8BEE]"></span>
          <span class="text-sm font-medium text-[#3B8BEE]">Nilai Kami</span>
        </div>
        <h2 class="text-3xl font-bold text-[#343434] mb-4 text-center">Nilai-Nilai yang Kami Pegang Teguh</h2>
        <div class="w-20 h-1 bg-[#3B8BEE] mb-6"></div>
        <p class="text-[#343434]/70 max-w-2xl text-center">
          Prinsip-prinsip yang menjadi pondasi Dilogif dalam memberikan layanan terbaik kepada klien.
        </p>
      </div>
      
      <!-- Values Cards with Improved Visual Design -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div v-for="(value, index) in values" :key="index" class="group">
          <div class="bg-white border border-gray-100 rounded-xl shadow-sm overflow-hidden transition-all duration-300 hover:shadow-md hover:border-[#3B8BEE]/20 h-full">
            <!-- Top Colored Bar -->
            <div class="h-2 bg-[#3B8BEE]"></div>
            
            <div class="p-8">
              <!-- Icon with Colored Background -->
              <div class="w-16 h-16 bg-[#3B8BEE]/10 rounded-full flex items-center justify-center mb-6 group-hover:bg-[#3B8BEE]/20 transition-colors duration-300">
                <component :is="value.icon" class="w-8 h-8 text-[#3B8BEE]" />
              </div>
              
              <!-- Value Title and Description -->
              <h3 class="text-xl font-bold text-[#343434] mb-3">{{ value.title }}</h3>
              <p class="text-[#343434]/70">{{ value.description }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>


</template>

<script setup>
import { h } from 'vue'


// Values with SVG Icon Components
const values = [
  {
    title: 'Kualitas',
    description: 'Kami berkomitmen untuk memberikan layanan berkualitas tinggi yang memenuhi standar industri dan kebutuhan klien.',
    icon: {
      render() {
        return h('svg', {
          xmlns: 'http://www.w3.org/2000/svg',
          width: '24',
          height: '24',
          viewBox: '0 0 24 24',
          fill: 'none',
          stroke: 'currentColor',
          'stroke-width': '2',
          'stroke-linecap': 'round',
          'stroke-linejoin': 'round'
        }, [
          h('polygon', { points: '12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26' })
        ])
      }
    }
  },
  {
    title: 'Kolaborasi',
    description: 'Kami bekerja bersama klien sebagai mitra, mendengarkan kebutuhan mereka dan menciptakan solusi yang sesuai.',
    icon: {
      render() {
        return h('svg', {
          xmlns: 'http://www.w3.org/2000/svg',
          width: '24',
          height: '24',
          viewBox: '0 0 24 24',
          fill: 'none',
          stroke: 'currentColor',
          'stroke-width': '2',
          'stroke-linecap': 'round',
          'stroke-linejoin': 'round'
        }, [
          h('path', { d: 'M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2' }),
          h('circle', { cx: '9', cy: '7', r: '4' }),
          h('path', { d: 'M23 21v-2a4 4 0 0 0-3-3.87' }),
          h('path', { d: 'M16 3.13a4 4 0 0 1 0 7.75' })
        ])
      }
    }
  },
  {
    title: 'Inovasi',
    description: 'Kami terus mencari cara baru dan lebih baik untuk menyelesaikan masalah dan menciptakan nilai bagi klien kami.',
    icon: {
      render() {
        return h('svg', {
          xmlns: 'http://www.w3.org/2000/svg',
          width: '24',
          height: '24',
          viewBox: '0 0 24 24',
          fill: 'none',
          stroke: 'currentColor',
          'stroke-width': '2',
          'stroke-linecap': 'round',
          'stroke-linejoin': 'round'
        }, [
          h('path', { d: 'M20 11.08V8l-6-6H6a2 2 0 0 0-2 2v16c0 1.1.9 2 2 2h6' }),
          h('path', { d: 'M14 3v5h5' }),
          h('path', { d: 'M18 20.24c.12.56.29 1.4.29 2.26 0 2.4-1.04 3.07-3.04 3.07-1.26 0-2.67-.97-2.67-2.35 0-1.19.95-2.11 2.13-2.11.88 0 1.63.71 1.63 1.63 0 .79-.65 1.44-1.44 1.44' })
        ])
      }
    }
  }
]
</script> 