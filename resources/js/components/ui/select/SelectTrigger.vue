<script setup lang="ts">
import { inject, computed, ref, onMounted, onBeforeUnmount } from 'vue'
import { ChevronDown } from 'lucide-vue-next'
import { cn } from '@/lib/utils'

const props = defineProps({
  placeholder: {
    type: String,
    default: 'Select an option'
  },
  disabled: {
    type: Boolean,
    default: false
  },
  class: {
    type: String,
    default: ''
  }
})

// Mendapatkan state dari parent Select
const selectValue: any = inject('select-value', { value: ref(''), update: (val: any) => {} })
const isOpen: any = inject('select-is-open', ref(false))
const selectedLabel: any = inject('select-selected-label', ref(''))

// Toggle dropdown
const toggleSelect = () => {
  if (props.disabled) return
  isOpen.value = !isOpen.value
}

// Ref untuk elemen trigger
const triggerRef = ref<HTMLElement | null>(null)

// Menghitung posisi dan ukuran dropdown
const updatePosition = () => {
  if (!triggerRef.value) return
  
  const rect = triggerRef.value.getBoundingClientRect()
  document.documentElement.style.setProperty('--trigger-width', `${rect.width}px`)
  document.documentElement.style.setProperty('--content-top', `${rect.bottom + window.scrollY}px`)
  document.documentElement.style.setProperty('--content-left', `${rect.left + window.scrollX}px`)
  
  // Hitung ruang yang tersedia di bawah
  const availableHeight = window.innerHeight - rect.bottom - 10
  document.documentElement.style.setProperty('--available-height', `${Math.min(availableHeight, 300)}px`)
}

// Update posisi saat dropdown dibuka
const watchOpen = (val: boolean) => {
  if (val) {
    updatePosition()
    window.addEventListener('resize', updatePosition)
    window.addEventListener('scroll', updatePosition)
  } else {
    window.removeEventListener('resize', updatePosition)
    window.removeEventListener('scroll', updatePosition)
  }
}

// Watch untuk isOpen
onMounted(() => {
  watchOpen(isOpen.value)
})

onBeforeUnmount(() => {
  window.removeEventListener('resize', updatePosition)
  window.removeEventListener('scroll', updatePosition)
})
</script>

<template>
  <div ref="triggerRef">
    <div 
      @click="toggleSelect" 
      class="flex w-full items-center justify-between gap-2 rounded-md border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 px-3 py-2 text-sm shadow-sm hover:border-gray-300 dark:hover:border-gray-600 focus:outline-none focus-visible:outline-none focus-visible:ring-0 disabled:cursor-not-allowed disabled:opacity-50 cursor-pointer h-9 select-trigger"
      :class="[
        props.class,
        props.disabled ? 'opacity-50 cursor-not-allowed' : '',
        isOpen.value ? 'border-primary dark:border-primary' : ''
      ]"
      tabindex="0"
    >
      <span class="truncate" :class="{ 'text-muted-foreground': !selectValue.value.value }">
        {{ selectedLabel.value || props.placeholder }}
      </span>
      <ChevronDown 
        class="h-4 w-4 opacity-50 transition-transform" 
        :class="{ 'rotate-180': isOpen.value }" 
      />
    </div>
  </div>
</template>

<style scoped>
/* Atasi outline browser secara global */
*:focus {
  outline: none !important;
  outline-color: transparent !important;
  outline-style: none !important;
  outline-width: 0 !important;
  box-shadow: none !important;
  -webkit-tap-highlight-color: transparent !important;
  -webkit-focus-ring-color: transparent !important;
}

/* Untuk semua browser */
div:focus,
div:focus-visible,
div:focus-within,
div:active {
  outline: none !important;
  outline-color: transparent !important;
  box-shadow: none !important;
  border-color: var(--primary) !important;
}

/* Fix untuk Firefox */
div:-moz-focusring {
  outline: none !important;
  outline-color: transparent !important;
}

/* Atasi semua warna outlines */
.select-trigger {
  -webkit-tap-highlight-color: transparent !important;
  outline: none !important;
  user-select: none !important;
}

.select-trigger:focus,
.select-trigger:focus-visible,
.select-trigger:focus-within,
.select-trigger:active {
  outline: none !important;
  outline-color: transparent !important;
  box-shadow: none !important;
  border-color: var(--primary) !important;
}

/* Fix Safari fokus */
.select-trigger::-webkit-focus-inner {
  border: 0 !important;
}

/* Fix Chrome dan Safari */
.select-trigger {
  -webkit-appearance: none !important;
}
</style>
