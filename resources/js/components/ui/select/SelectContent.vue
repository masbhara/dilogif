<script setup lang="ts">
import { cn } from '@/lib/utils'
import { ref, inject, onMounted, onUnmounted } from 'vue'

const props = defineProps({
  class: {
    type: String,
    default: ''
  }
})

// Mendapatkan state isOpen dari SelectTrigger
const isOpen = inject('select-is-open', ref(false))

// Ref untuk elemen dropdown
const contentRef = ref<HTMLElement | null>(null)

// Close dropdown ketika klik di luar
const handleClickOutside = (event: MouseEvent) => {
  if (!isOpen.value) return
  
  if (contentRef.value && !contentRef.value.contains(event.target as Node)) {
    // Pastikan klik diluar SelectTrigger dan SelectContent
    isOpen.value = false
  }
}

// Setup event listener
onMounted(() => {
  document.addEventListener('mousedown', handleClickOutside)
})

onUnmounted(() => {
  document.removeEventListener('mousedown', handleClickOutside)
})
</script>

<template>
  <Teleport to="body">
    <div 
      v-if="isOpen" 
      ref="contentRef" 
      class="fixed z-[9999] w-[var(--radix-select-trigger-width)] max-h-[var(--radix-select-content-available-height)] min-w-[8rem] overflow-hidden rounded-md border border-input bg-white dark:bg-gray-900 text-gray-900 dark:text-white shadow-md animate-in fade-in-0 zoom-in-95 slide-in-from-top-2"
      :class="props.class"
      style="--radix-select-trigger-width: var(--trigger-width, 12rem); --radix-select-content-available-height: var(--available-height, 16rem); top: var(--content-top, 0); left: var(--content-left, 0);"
    >
      <div class="p-1 overflow-y-auto max-h-[var(--radix-select-content-available-height)]">
        <slot />
      </div>
    </div>
  </Teleport>
</template>
