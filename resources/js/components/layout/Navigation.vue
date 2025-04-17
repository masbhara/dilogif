<template>
  <header class="sticky top-0 z-50 w-full border-b bg-[#3B8BEE] shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex h-20 items-center justify-between">
        <div class="flex items-center">
          <a href="/" class="text-2xl font-bold text-[#FCFDFD]">
            Company Name
          </a>
        </div>
        
        <nav class="hidden md:flex items-center space-x-8">
          <a 
            v-for="item in navigation" 
            :key="item.name"
            :href="item.href"
            class="text-base font-medium text-[#FCFDFD] hover:text-[#FFFFFF] transition-colors"
            :class="{ 'text-[#FFFFFF]': item.current }"
          >
            {{ item.name }}
          </a>
        </nav>
      </div>
    </div>
  </header>
</template>

<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

const page = usePage()

const navigation = computed(() => [
  { name: 'Home', href: '/', current: page.url === '/' },
  { name: 'About', href: '/about', current: page.url === '/about' },
  { name: 'Services', href: '/services', current: page.url === '/services' },
  { name: 'Contact', href: '/contact', current: page.url === '/contact' }
])
</script> 