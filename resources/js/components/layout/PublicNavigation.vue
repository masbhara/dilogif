<template>
  <nav :class="[
    'fixed w-full z-50 transition-all duration-300',
    isScrolled ? 'bg-white/95 dark:bg-gray-900/95 backdrop-blur-sm shadow-md' : 'bg-transparent'
  ]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <div class="flex">
          <!-- Logo -->
          <div class="shrink-0 flex items-center">
            <Link :href="route('home')">
              <ApplicationLogo :class="[
                'block h-9 w-auto fill-current transition-colors duration-300',
                isScrolled ? 'text-gray-800 dark:text-white' : 'text-white'
              ]" />
            </Link>
          </div>

          <!-- Navigation Links -->
          <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
            <NavLink :href="route('home')" :active="route().current('home')" :class="[
              'transition-colors duration-300',
              isScrolled ? 'text-gray-800 dark:text-white hover:text-gray-600 dark:hover:text-gray-300' : 'text-white hover:text-gray-200'
            ]">
              Beranda
            </NavLink>
            <NavLink :href="route('products.index')" :active="route().current('products.index')" :class="[
              'transition-colors duration-300',
              isScrolled ? 'text-gray-800 dark:text-white hover:text-gray-600 dark:hover:text-gray-300' : 'text-white hover:text-gray-200'
            ]">
              Produk
            </NavLink>
            <NavLink :href="route('about')" :active="route().current('about')" :class="[
              'transition-colors duration-300',
              isScrolled ? 'text-gray-800 dark:text-white hover:text-gray-600 dark:hover:text-gray-300' : 'text-white hover:text-gray-200'
            ]">
              Tentang Kami
            </NavLink>
            <NavLink :href="route('contact')" :active="route().current('contact')" :class="[
              'transition-colors duration-300',
              isScrolled ? 'text-gray-800 dark:text-white hover:text-gray-600 dark:hover:text-gray-300' : 'text-white hover:text-gray-200'
            ]">
              Kontak
            </NavLink>
          </div>
        </div>

        <div class="hidden sm:flex sm:items-center sm:ml-6">
          <!-- Cart -->
          <Link :href="route('cart.index')" :class="[
            'relative p-2 transition-colors duration-300',
            isScrolled ? 'text-gray-800 dark:text-white hover:text-gray-600 dark:hover:text-gray-300' : 'text-white hover:text-gray-200'
          ]">
            <ShoppingCartIcon class="h-6 w-6" />
            <span v-if="cartCount > 0" class="absolute -top-1 -right-1 bg-primary-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
              {{ cartCount }}
            </span>
          </Link>

          <!-- Login/Register or User Menu -->
          <div class="ml-3 relative">
            <div v-if="$page.props.auth.user">
              <Dropdown align="right" width="48">
                <template #trigger>
                  <span class="inline-flex rounded-md">
                    <button
                      type="button"
                      :class="[
                        'inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md focus:outline-none transition-colors duration-300',
                        isScrolled ? 'text-gray-800 dark:text-white hover:text-gray-600 dark:hover:text-gray-300' : 'text-white hover:text-gray-200'
                      ]"
                    >
                      {{ $page.props.auth.user.name }}

                      <svg
                        class="ml-2 -mr-0.5 h-4 w-4"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                      >
                        <path
                          fill-rule="evenodd"
                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                          clip-rule="evenodd"
                        />
                      </svg>
                    </button>
                  </span>
                </template>

                <template #content>
                  <DropdownLink :href="route('dashboard')">
                    Dashboard
                  </DropdownLink>
                  <DropdownLink :href="route('orders.index')">
                    Pesanan Saya
                  </DropdownLink>
                  <DropdownLink :href="route('logout')" method="post" as="button">
                    Logout
                  </DropdownLink>
                </template>
              </Dropdown>
            </div>
            <div v-else>
              <Link
                :href="route('login')"
                :class="[
                  'text-sm underline transition-colors duration-300',
                  isScrolled ? 'text-gray-800 dark:text-white hover:text-gray-600 dark:hover:text-gray-300' : 'text-white hover:text-gray-200'
                ]"
              >
                Login
              </Link>

              <Link
                :href="route('register')"
                :class="[
                  'ml-4 text-sm underline transition-colors duration-300',
                  isScrolled ? 'text-gray-800 dark:text-white hover:text-gray-600 dark:hover:text-gray-300' : 'text-white hover:text-gray-200'
                ]"
              >
                Register
              </Link>
            </div>
          </div>
        </div>

        <!-- Hamburger -->
        <div class="-mr-2 flex items-center sm:hidden">
          <button
            @click="showingNavigationDropdown = !showingNavigationDropdown"
            :class="[
              'inline-flex items-center justify-center p-2 rounded-md focus:outline-none transition duration-150 ease-in-out',
              isScrolled ? 'text-gray-800 dark:text-white hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' : 'text-white hover:text-gray-200 hover:bg-white/10'
            ]"
          >
            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
              <path
                :class="{
                  hidden: showingNavigationDropdown,
                  'inline-flex': !showingNavigationDropdown,
                }"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M4 6h16M4 12h16M4 18h16"
              />
              <path
                :class="{
                  hidden: !showingNavigationDropdown,
                  'inline-flex': showingNavigationDropdown,
                }"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M6 18L18 6M6 6l12 12"
              />
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div
      :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
      class="sm:hidden bg-white/95 dark:bg-gray-900/95 backdrop-blur-sm"
    >
      <div class="pt-2 pb-3 space-y-1">
        <ResponsiveNavLink :href="route('home')" :active="route().current('home')">
          Beranda
        </ResponsiveNavLink>
        <ResponsiveNavLink :href="route('products.index')" :active="route().current('products.index')">
          Produk
        </ResponsiveNavLink>
        <ResponsiveNavLink :href="route('about')" :active="route().current('about')">
          Tentang Kami
        </ResponsiveNavLink>
        <ResponsiveNavLink :href="route('contact')" :active="route().current('contact')">
          Kontak
        </ResponsiveNavLink>
      </div>

      <!-- Responsive Settings Options -->
      <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
        <div v-if="$page.props.auth.user" class="px-4">
          <div class="font-medium text-base text-gray-800 dark:text-gray-200">
            {{ $page.props.auth.user.name }}
          </div>
          <div class="font-medium text-sm text-gray-500">{{ $page.props.auth.user.email }}</div>
        </div>

        <div class="mt-3 space-y-1">
          <ResponsiveNavLink v-if="$page.props.auth.user" :href="route('dashboard')">
            Dashboard
          </ResponsiveNavLink>
          <ResponsiveNavLink v-if="$page.props.auth.user" :href="route('orders.index')">
            Pesanan Saya
          </ResponsiveNavLink>
          <ResponsiveNavLink v-if="$page.props.auth.user" :href="route('logout')" method="post" as="button">
            Logout
          </ResponsiveNavLink>
          <ResponsiveNavLink v-else :href="route('login')">
            Login
          </ResponsiveNavLink>
          <ResponsiveNavLink v-else :href="route('register')">
            Register
          </ResponsiveNavLink>
        </div>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import ApplicationLogo from '@/components/ApplicationLogo.vue';
import Dropdown from '@/components/Dropdown.vue';
import DropdownLink from '@/components/DropdownLink.vue';
import NavLink from '@/components/NavLink.vue';
import ResponsiveNavLink from '@/components/ResponsiveNavLink.vue';
import { ShoppingCartIcon } from '@heroicons/vue/24/outline';

const showingNavigationDropdown = ref(false);
const isScrolled = ref(false);

const handleScroll = () => {
  isScrolled.value = window.scrollY > 0;
};

onMounted(() => {
  window.addEventListener('scroll', handleScroll);
  handleScroll(); // Check initial scroll position
});

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll);
});

defineProps({
  cartCount: {
    type: Number,
    default: 0
  }
});
</script> 