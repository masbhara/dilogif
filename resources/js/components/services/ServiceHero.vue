<template>
  <section class="py-20 bg-[#f9f9f9]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
        <div class="lg:col-span-5 order-2 lg:order-1">
          <h1 class="text-4xl md:text-5xl font-bold mb-6 text-[#343434]">
            Layanan <span class="text-[#3B8BEE]">Profesional</span> untuk Bisnis Anda
          </h1>
          <div class="w-20 h-1.5 bg-[#3B8BEE] mb-6"></div>
          <p class="text-lg mb-8 text-[#343434]/80">
            Kami menyediakan berbagai layanan digital yang dapat disesuaikan dengan kebutuhan bisnis Anda. Dari pengembangan website hingga strategi pemasaran digital.
          </p>
          
          <!-- Service Stats -->
          <div class="grid grid-cols-2 gap-6 mb-8">
            <div class="bg-white p-4 rounded-lg shadow-sm">
              <span class="block text-3xl font-bold text-[#3B8BEE] mb-1">50+</span>
              <span class="text-sm text-[#343434]">Project Selesai</span>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-sm">
              <span class="block text-3xl font-bold text-[#3B8BEE] mb-1">100%</span>
              <span class="text-sm text-[#343434]">Kepuasan Klien</span>
            </div>
          </div>
          
          <a 
            href="#service-list" 
            class="inline-flex items-center gap-2 bg-[#3B8BEE] text-white px-6 py-3 rounded-lg font-medium hover:bg-[#2A7AD9] transition-colors"
          >
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/>
              <polyline points="14 2 14 8 20 8"/>
              <line x1="16" y1="13" x2="8" y2="13"/>
              <line x1="16" y1="17" x2="8" y2="17"/>
              <line x1="10" y1="9" x2="8" y2="9"/>
            </svg>
            Lihat Semua Layanan
          </a>
        </div>
        
        <div class="lg:col-span-7 order-1 lg:order-2">
          <div class="relative">
            <div class="absolute -top-6 -left-6 w-20 h-20 bg-[#3B8BEE] opacity-20 rounded-full"></div>
            <div class="absolute -bottom-10 -right-10 w-32 h-32 bg-[#3B8BEE] opacity-10 rounded-full"></div>
            
            <img 
              src="/images/hero-dashboard.svg" 
              alt="Layanan Digital" 
              class="w-full h-auto rounded-lg shadow-xl relative z-10"
            />
            
            <!-- Floating Elements -->
            <div class="absolute top-10 -right-8 bg-white p-3 rounded-lg shadow-lg hidden lg:block">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-[#3B8BEE]/20 flex items-center justify-center">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#3B8BEE" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 20h9"/> <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/>
                  </svg>
                </div>
                <div>
                  <span class="block text-sm font-medium text-[#343434]">UI/UX Design</span>
                  <span class="block text-xs text-[#343434]/60">Professional</span>
                </div>
              </div>
            </div>
            
            <div class="absolute -bottom-5 left-10 bg-white p-3 rounded-lg shadow-lg hidden lg:block">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-[#3B8BEE]/20 flex items-center justify-center">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#3B8BEE" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 19l7-7 3 3-7 7-3-3z"/> <path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z"/> <path d="M2 2l7.586 7.586"/> <circle cx="11" cy="11" r="2"/>
                  </svg>
                </div>
                <div>
                  <span class="block text-sm font-medium text-[#343434]">Branding</span>
                  <span class="block text-xs text-[#343434]/60">Identity Design</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template> 