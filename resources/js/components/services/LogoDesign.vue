<template>
  <section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
        <!-- Content -->
        <div>
          <h2 class="text-3xl font-bold text-[#3B8BEE] mb-6">Logo Design</h2>
          <p class="text-[#343434]/80 mb-6">
            Kami menerima pembuatan logo dengan berbagai jenisnya, sesuai dengan kebutuhan Anda, dengan kelengkapan :
          </p>
          
          <div class="space-y-3">
            <div class="flex items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#3B8BEE]" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
              </svg>
              <span>Desain Logo</span>
            </div>
            <div class="flex items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#3B8BEE]" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
              </svg>
              <span>Dokumentasi (Guideline)</span>
            </div>
            <div class="flex items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#3B8BEE]" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
              </svg>
              <span>Mockup Media Logo</span>
            </div>
          </div>
          
          <div class="mt-8">
            <Button variant="primary" class="inline-flex items-center px-6 py-3" as="a" href="/contact">
              Order Logo
            </Button>
          </div>
        </div>
        
        <!-- Image -->
        <div>
          <div class="relative">
            <img 
              src="/images/hero-dashboard.svg" 
              alt="Logo Design" 
              class="w-full h-auto rounded-lg shadow-md"
            />
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import Button from '@/components/ui/button/Button.vue'
</script> 