<template>
  <footer class="bg-primary text-primary-foreground">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <!-- Company Info -->
        <div class="col-span-1">
          <div class="flex items-center gap-3 mb-4">
            <img 
              v-if="websiteSettings?.logoUrl" 
              :src="websiteSettings.logoUrl" 
              :alt="websiteSettings?.siteName || websiteSettings?.site_name" 
              class="h-10 w-auto"
            />
            <h3 class="text-2xl font-bold text-primary-foreground">
              {{ websiteSettings?.siteName || websiteSettings?.site_name || 'Company Name' }}
            </h3>
          </div>
          <p class="text-sm text-primary-foreground/80">
            {{ websiteSettings?.siteDescription || websiteSettings?.site_description || 'Providing innovative solutions for your business needs. Let\'s grow together.' }}
          </p>
        </div>

        <!-- Quick Links -->
        <div class="col-span-1">
          <h4 class="text-lg font-semibold text-primary-foreground mb-4">Quick Links</h4>
          <ul class="space-y-2">
            <li v-for="item in navigation" :key="item.name">
              <a :href="item.href" class="text-sm text-primary-foreground/80 hover:text-primary-foreground transition-colors">
                {{ item.name }}
              </a>
            </li>
          </ul>
        </div>

        <!-- Services -->
        <div class="col-span-1">
          <h4 class="text-lg font-semibold text-primary-foreground mb-4">Services</h4>
          <ul class="space-y-2">
            <li v-for="service in services" :key="service">
              <a href="#" class="text-sm text-primary-foreground/80 hover:text-primary-foreground transition-colors">
                {{ service }}
              </a>
            </li>
          </ul>
        </div>

        <!-- Contact -->
        <div class="col-span-1">
          <h4 class="text-lg font-semibold text-primary-foreground mb-4">Contact Us</h4>
          <ul class="space-y-2 text-sm text-primary-foreground/80">
            <li>Email: {{ websiteSettings?.contactEmail || 'info@company.com' }}</li>
            <li>Phone: +1234567890</li>
            <li>Address: Your Address Here</li>
          </ul>
        </div>
      </div>

      <div class="border-t border-primary-foreground/20 mt-12 pt-8 text-sm text-center text-primary-foreground/80">
        <p>{{ websiteSettings?.copyright || `© ${new Date().getFullYear()} ${websiteSettings?.siteName || websiteSettings?.site_name || 'Company Name'}. All rights reserved.` }}</p>
      </div>
    </div>
  </footer>
</template>

<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

const page = usePage()
const websiteSettings = computed(() => page.props.websiteSettings)

const navigation = computed(() => [
  { name: 'Home', href: '/' },
  { name: 'About', href: '/about' },
  { name: 'Services', href: '/services' },
  { name: 'Contact', href: '/contact' }
])

const services = [
  'Web Development',
  'Mobile Development',
  'UI/UX Design',
  'Digital Marketing'
]
</script> 