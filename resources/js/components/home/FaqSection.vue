<template>
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
</template>

<script setup>
import { ref } from 'vue'

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