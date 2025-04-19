<script setup lang="ts">
import { cn } from '@/lib/utils'
import { ref, inject, onMounted, onUnmounted, reactive, watch } from 'vue'

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

// State untuk posisi
const position = reactive({
  width: '12rem',
  maxHeight: '16rem',
  top: '0px',
  left: '0px'
})

// Update posisi saat nilai berubah
const updatePosition = inject('select-update-position', () => {})

// Mendapatkan nilai posisi yang dihitung
const triggerWidth = inject('select-trigger-width', ref('12rem'))
const contentTop = inject('select-content-top', ref('0px'))
const contentLeft = inject('select-content-left', ref('0px'))
const availableHeight = inject('select-available-height', ref('16rem'))

// Update position reactively
onMounted(() => {
  const unwatch = watch(
    [triggerWidth, contentTop, contentLeft, availableHeight],
    ([width, top, left, height]) => {
      position.width = width
      position.top = top
      position.left = left
      position.maxHeight = height
    },
    { immediate: true }
  )
})

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
      class="fixed z-[9999] min-w-[8rem] overflow-hidden rounded-md border border-input bg-white dark:bg-gray-900 text-gray-900 dark:text-white shadow-md animate-in fade-in-0 zoom-in-95 slide-in-from-top-2"
      :class="props.class"
      :style="{
        width: position.width,
        maxHeight: position.maxHeight,
        top: position.top,
        left: position.left
      }"
    >
      <div class="p-1 overflow-y-auto" :style="{ maxHeight: position.maxHeight }">
        <slot />
      </div>
    </div>
  </Teleport>
</template>
