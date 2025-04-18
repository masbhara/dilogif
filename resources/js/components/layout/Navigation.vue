<template>
  <header class="sticky top-0 z-50 w-full bg-[#3B8BEE]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex h-20 items-center justify-between">
        <!-- Logo dan Nama Website -->
        <div class="flex items-center">
          <Link :href="route('home')" class="flex items-center gap-2">
            <img 
              v-if="websiteSettings?.logoUrl" 
              :src="websiteSettings.logoUrl" 
              :alt="websiteSettings?.siteName || websiteSettings?.site_name" 
              class="h-8 w-auto"
            />
            <span class="text-2xl font-bold text-[#FCFDFD]">
              {{ websiteSettings?.siteName || websiteSettings?.site_name || 'Company Name' }}
            </span>
          </Link>
        </div>

        <!-- Desktop Navigation -->
        <div class="hidden md:flex items-center">
          <!-- Main Navigation -->
          <nav class="flex items-center space-x-8 mr-8">
            <Link 
              v-for="item in mainNavigation" 
              :key="item.name"
              :href="route(item.route)"
              class="text-base font-medium text-[#FCFDFD] hover:text-[#FFFFFF] transition-colors"
              :class="{ 'text-[#FFFFFF]': item.current }"
            >
              {{ item.name }}
            </Link>
          </nav>

          <!-- Cart and Auth Navigation -->
          <div class="flex items-center space-x-4">
            <!-- Shopping Cart -->
            <Link 
              :href="route('cart.index')" 
              class="text-[#FCFDFD] hover:text-white relative"
            >
              <ShoppingCartIcon class="h-6 w-6" />
              <span 
                v-if="cartCount > 0" 
                class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold"
              >
                {{ cartCount }}
              </span>
            </Link>
            
            <Link 
              :href="route('login')"
              class="text-base font-medium text-[#FCFDFD] hover:text-[#3B8BEE] transition-colors px-4 py-2 rounded-lg border border-[#FCFDFD] hover:bg-[#FCFDFD] hover:text-[#3B8BEE]"
            >
              Login
            </Link>
          </div>
        </div>

        <!-- Mobile Menu Button -->
        <div class="flex md:hidden items-center space-x-4">
          <!-- Mobile Cart Icon -->
          <Link 
            :href="route('cart.index')" 
            class="text-[#FCFDFD] hover:text-white relative mr-2"
          >
            <ShoppingCartIcon class="h-6 w-6" />
            <span 
              v-if="cartCount > 0" 
              class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold"
            >
              {{ cartCount }}
            </span>
          </Link>
          
          <button 
            @click="isOpen = !isOpen" 
            type="button" 
            class="text-[#FCFDFD] hover:text-white"
            aria-controls="mobile-menu" 
            aria-expanded="false"
          >
            <span class="sr-only">Buka menu utama</span>
            <!-- Icon when menu is closed -->
            <svg 
              v-if="!isOpen"
              class="h-6 w-6" 
              fill="none" 
              viewBox="0 0 24 24" 
              stroke="currentColor"
            >
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <!-- Icon when menu is open -->
            <svg 
              v-else
              class="h-6 w-6" 
              fill="none" 
              viewBox="0 0 24 24" 
              stroke="currentColor"
            >
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!-- Mobile Menu -->
    <div 
      v-show="isOpen" 
      class="md:hidden bg-[#3B8BEE]"
      id="mobile-menu"
    >
      <div class="px-4 pt-2 pb-4 space-y-2">
        <!-- Main Navigation Mobile -->
        <Link 
          v-for="item in mainNavigation" 
          :key="item.name"
          :href="route(item.route)"
          class="block px-3 py-2 text-base font-medium text-[#FCFDFD] hover:text-white transition-colors"
          :class="{ 'text-white': item.current }"
          @click="isOpen = false"
        >
          {{ item.name }}
        </Link>

        <!-- Auth Navigation Mobile -->
        <div class="pt-4 space-y-2 border-t border-[#FCFDFD]/20">
          <Link 
            :href="route('login')"
            class="block px-3 py-2 text-base font-medium text-[#FCFDFD] hover:text-white transition-colors"
            @click="isOpen = false"
          >
            Login
          </Link>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { usePage, Link } from '@inertiajs/vue3'
import { ShoppingCartIcon } from 'lucide-vue-next'

const page = usePage()
const isOpen = ref(false)
const cartCount = ref(0)

// Mengambil websiteSettings dari props Inertia
const websiteSettings = computed(() => page.props.websiteSettings)

// Memisahkan navigasi utama
const mainNavigation = computed(() => [
  { name: 'Home', route: 'home', current: page.url === '/' },
  { name: 'Products', route: 'products.index', current: page.url.startsWith('/products') },
  { name: 'About', route: 'about', current: page.url === '/about' },
  { name: 'Services', route: 'services', current: page.url === '/services' },
  { name: 'Contact', route: 'contact', current: page.url === '/contact' }
])

// Fungsi untuk memperbarui jumlah keranjang
const updateCartCount = (count) => {
  cartCount.value = count
}

// Mendapatkan jumlah keranjang saat komponen dimuat
onMounted(() => {
  // Expose updateCartCount function globally
  window.updateCartCount = updateCartCount
  
  // Get initial cart count
  if (page.props.cartCount !== undefined) {
    cartCount.value = page.props.cartCount
  }
})
</script> 