<template>
  <nav :class="[
    'fixed w-full z-50 transition-all duration-300',
    route().current('home')
      ? isScrolled 
        ? 'bg-background shadow-sm' 
        : 'bg-transparent'
      : 'bg-background shadow-sm'
  ]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <div class="flex">
          <!-- Logo -->
          <div class="shrink-0 flex items-center">
            <Link :href="route('home')">
              <ApplicationLogo :class="[
                'block h-9 w-auto fill-current',
                route().current('home') && !isScrolled
                  ? 'text-white'
                  : 'text-primary'
              ]" />
            </Link>
          </div>

          <!-- Navigation Links -->
          <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
            <NavLink 
              v-for="(item, index) in navigation" 
              :key="index"
              :href="route(item.route)"
              :active="route().current(item.route)"
              class="inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 transition duration-150 ease-in-out"
              :class="[
                route().current('home') && !isScrolled
                  ? [
                      'text-white',
                      'hover:text-primary-500',
                      route().current(item.route)
                        ? 'border-b-2 border-red-200'
                        : 'border-b-2 border-transparent'
                    ]
                    : [
                        'text-foreground',
                        'hover:text-primary',
                        route().current(item.route)
                          ? 'border-b-2 border-primary'
                          : 'border-b-2 border-transparent'
                      ]
              ]"
            >
              {{ item.name }}
            </NavLink>
          </div>
        </div>

        <div class="hidden sm:flex sm:items-center sm:ml-6 space-x-4">
          <!-- Darkmode Toggle -->
          <button
            @click="toggleDarkMode"
            class="p-2 rounded-full transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-primary"
            :class="[
              route().current('home') && !isScrolled
                ? 'text-white hover:bg-white/10'
                : 'text-foreground hover:bg-muted'
            ]"
            aria-label="Toggle dark mode"
          >
            <span v-if="isDark" class="block">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m8.66-13.66l-.71.71M4.05 19.95l-.71.71M21 12h-1M4 12H3m16.66 5.66l-.71-.71M4.05 4.05l-.71-.71M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
              </svg>
            </span>
            <span v-else class="block">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12.79A9 9 0 1111.21 3a7 7 0 109.79 9.79z" />
              </svg>
            </span>
          </button>

          <!-- Cart -->
          <Link 
            :href="route('cart.index')" 
            class="relative p-2 rounded-full transition-all duration-300"
            :class="[
              route().current('home') && !isScrolled
                ? 'text-white hover:text-white/80 hover:bg-white/10'
                : 'text-foreground hover:text-primary hover:bg-muted'
            ]"
          >
            <ShoppingCartIcon class="h-6 w-6" />
            <span 
              v-if="cartCount > 0" 
              class="absolute -top-1 -right-1 bg-primary text-primary-foreground text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center shadow-sm"
            >
              {{ cartCount }}
            </span>
          </Link>

          <!-- Login/Register or User Menu -->
          <div v-if="$page.props.auth.user">
            <Dropdown align="right" width="48">
              <template #trigger>
                <button
                  class="flex items-center transition duration-150 ease-in-out"
                  :class="[
                    route().current('home') && !isScrolled
                      ? 'text-white hover:text-white/80'
                      : 'text-foreground hover:text-primary'
                  ]"
                >
                  <span class="text-sm font-medium">{{ $page.props.auth.user.name }}</span>
                  <ChevronDownIcon class="ml-2 h-4 w-4" />
                </button>
              </template>

              <template #content>
                <div class="bg-background rounded-lg shadow-lg ring-1 ring-border ring-opacity-5 py-1">
                  <DropdownLink :href="route('dashboard')" class="block px-4 py-2 text-sm text-foreground hover:bg-muted hover:text-primary">
                    Dashboard
                  </DropdownLink>
                  <DropdownLink :href="route('orders.index')" class="block px-4 py-2 text-sm text-foreground hover:bg-muted hover:text-primary">
                    Pesanan Saya
                  </DropdownLink>
                  <DropdownLink :href="route('logout')" method="post" as="button" class="block w-full text-left px-4 py-2 text-sm text-foreground hover:bg-muted hover:text-primary">
                    Logout
                  </DropdownLink>
                </div>
              </template>
            </Dropdown>
          </div>
          <div v-else class="flex items-center space-x-4">
            <Link :href="route('login')">
              <Button
                variant="default"
                size="sm"
                class="font-medium"
              >
                Masuk
              </Button>
            </Link>
          </div>
        </div>

        <!-- Mobile menu button & darkmode toggle -->
        <div class="flex items-center sm:hidden gap-2">
          <!-- Darkmode Toggle Mobile (sebelah hamburger) -->
          <button
            @click="toggleDarkMode"
            class="p-2 rounded-full transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-primary text-foreground hover:bg-muted"
            aria-label="Toggle dark mode"
          >
            <span v-if="isDark" class="block">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m8.66-13.66l-.71.71M4.05 19.95l-.71.71M21 12h-1M4 12H3m16.66 5.66l-.71-.71M4.05 4.05l-.71-.71M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
              </svg>
            </span>
            <span v-else class="block">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12.79A9 9 0 1111.21 3a7 7 0 109.79 9.79z" />
              </svg>
            </span>
          </button>
          <!-- Mobile menu button -->
          <button
            @click="showingNavigationDropdown = !showingNavigationDropdown"
            class="inline-flex items-center justify-center p-2 rounded-lg transition-colors duration-300"
            :class="[
              route().current('home') && !isScrolled
                ? 'text-white hover:text-white/80 hover:bg-white/10'
                : 'text-foreground hover:text-primary hover:bg-muted'
            ]"
          >
            <span class="sr-only">Open main menu</span>
            <Bars3Icon v-if="!showingNavigationDropdown" class="h-6 w-6" />
            <XMarkIcon v-else class="h-6 w-6" />
          </button>
        </div>
      </div>
    </div>

    <!-- Mobile menu -->
    <div
      :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
      class="fixed top-0 left-0 w-full z-50 sm:hidden"
    >
      <!-- Tombol Close Mobile -->
      <button
        @click="showingNavigationDropdown = false"
        class="absolute right-4 top-4 z-50 p-2 rounded-full bg-background shadow hover:bg-muted transition-colors"
        aria-label="Tutup menu"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-foreground" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
      <div class="pb-3 space-y-1 bg-background shadow-lg">
        <div class="pt-2 pb-3 space-y-1 bg-background shadow-lg">
          <ResponsiveNavLink 
            v-for="(item, index) in navigation" 
            :key="index"
            :href="route(item.route)"
            :active="route().current(item.route)"
            class="block pl-3 pr-4 py-2 text-base font-medium border-l-4 transition duration-150 ease-in-out"
            :class="[
              route().current(item.route)
                ? 'border-primary text-primary bg-muted'
                : 'border-transparent text-foreground hover:text-primary hover:bg-muted hover:border-primary'
            ]"
          >
            {{ item.name }}
          </ResponsiveNavLink>
        </div>

        <!-- Mobile user menu -->
        <div class="pt-4 pb-1 border-t border-border bg-background">
          <div v-if="$page.props.auth.user" class="px-4">
            <div class="font-medium text-base text-foreground">
              {{ $page.props.auth.user.name }}
            </div>
            <div class="font-medium text-sm text-muted-foreground">
              {{ $page.props.auth.user.email }}
            </div>
          </div>

          <div class="mt-3 space-y-1">
            <template v-if="$page.props.auth.user">
              <ResponsiveNavLink
                v-for="(item, index) in userNavigation"
                :key="index"
                :href="route(item.route)"
                :method="item.method"
                as="button"
                class="block w-full text-left pl-3 pr-4 py-2 text-base font-medium text-foreground hover:text-primary hover:bg-muted"
              >
                {{ item.name }}
              </ResponsiveNavLink>
            </template>
            <template v-else>
              <ResponsiveNavLink
                :href="route('login')"
                class="block pl-3 pr-4 py-2 text-base font-medium text-foreground hover:text-primary hover:bg-muted"
              >
                Masuk
              </ResponsiveNavLink>
            </template>
          </div>
        </div>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import ApplicationLogo from '@/components/ApplicationLogo.vue';
import Dropdown from '@/components/Dropdown.vue';
import DropdownLink from '@/components/DropdownLink.vue';
import NavLink from '@/components/NavLink.vue';
import ResponsiveNavLink from '@/components/ResponsiveNavLink.vue';
import { ShoppingCartIcon, ChevronDownIcon, Bars3Icon, XMarkIcon } from '@heroicons/vue/24/outline';
import { cartCount, initializeCartCount } from '@/event-bus';
import { Button } from '@/components/ui/button';

const showingNavigationDropdown = ref(false);
const isScrolled = ref(false);
const isDark = ref(false);

// Initialize cart count from page props
const page = usePage()
if (page.props.cartCount !== undefined) {
  initializeCartCount(page.props.cartCount)
}

const navigation = [
  { name: 'Beranda', route: 'home' },
  { name: 'Produk', route: 'products.index' },
  { name: 'Layanan', route: 'services' },
  { name: 'Tentang Kami', route: 'about' },
  { name: 'Kontak', route: 'contact' }
];

const userNavigation = [
  { name: 'Dashboard', route: 'dashboard' },
  { name: 'Pesanan Saya', route: 'orders.index' },
  { name: 'Logout', route: 'logout', method: 'post' }
];

const handleScroll = () => {
  isScrolled.value = window.scrollY > 0;
};

function setDarkMode(val) {
  isDark.value = val;
  if (val) {
    document.documentElement.classList.add('dark');
    localStorage.setItem('theme', 'dark');
  } else {
    document.documentElement.classList.remove('dark');
    localStorage.setItem('theme', 'light');
  }
}

function toggleDarkMode() {
  setDarkMode(!isDark.value);
}

onMounted(() => {
  window.addEventListener('scroll', handleScroll);
  handleScroll();
  // Cek preferensi user
  const theme = localStorage.getItem('theme');
  if (theme === 'dark') setDarkMode(true);
  else if (theme === 'light') setDarkMode(false);
  else {
    // Ikuti preferensi sistem
    setDarkMode(window.matchMedia('(prefers-color-scheme: dark)').matches);
  }
  window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
    if (!localStorage.getItem('theme')) setDarkMode(e.matches);
  });
});

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll);
});
</script> 