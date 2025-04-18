<script setup lang="ts">
import { computed, provide, reactive, ref, watch, nextTick, onMounted, onUnmounted } from 'vue'

const props = defineProps({
  modelValue: {
    type: [String, Number],
    default: ''
  },
  disabled: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['update:modelValue'])

// Reactive reference to the model value
const value = computed({
  get: () => props.modelValue,
  set: (val) => emit('update:modelValue', val)
})

// Trigger dan content refs untuk positioning
const triggerRef = ref<HTMLElement | null>(null)
const isOpen = ref(false)

// Menghitung posisi dropdown
const calculatePosition = async () => {
  await nextTick()
  
  if (!triggerRef.value) return
  
  // Dapatkan elemen DOM dengan aman
  const triggerEl = triggerRef.value
  if (!triggerEl) return
  
  const rect = triggerEl.getBoundingClientRect()
  
  // Set CSS variables untuk positioning
  document.documentElement.style.setProperty('--trigger-width', `${rect.width}px`)
  document.documentElement.style.setProperty('--content-top', `${rect.bottom + window.scrollY}px`)
  document.documentElement.style.setProperty('--content-left', `${rect.left + window.scrollX}px`)
  document.documentElement.style.setProperty('--available-height', `${window.innerHeight - rect.bottom - 10}px`)
}

// Listen for trigger clicks
const handleTriggerClick = async () => {
  isOpen.value = !isOpen.value
  
  // Jika dropdown dibuka, hitung posisinya
  if (isOpen.value) {
    await calculatePosition()
  }
}

// Reposition on window resize
onMounted(() => {
  window.addEventListener('resize', () => {
    if (isOpen.value) {
      calculatePosition()
    }
  })
  
  // Tambahkan event listener untuk scroll
  window.addEventListener('scroll', () => {
    if (isOpen.value) {
      calculatePosition()
    }
  }, true) // Capture phase untuk mendeteksi semua event scroll
})

onUnmounted(() => {
  window.removeEventListener('resize', () => {
    if (isOpen.value) {
      calculatePosition()
    }
  })
  
  window.removeEventListener('scroll', () => {
    if (isOpen.value) {
      calculatePosition()
    }
  }, true)
})

// Provide a way to update the value for child components
provide('select-value', {
  value,
  update: (newValue: string | number) => {
    value.value = newValue
  }
})

// Provide dropdown state
provide('select-is-open', isOpen)

// Provide disabled state
provide('select-disabled', ref(props.disabled))
</script>

<template>
  <div class="relative">
    <slot name="trigger" :open="isOpen" :onClick="handleTriggerClick">
      <component 
        :is="$slots.default ? 'div' : 'button'" 
        ref="triggerRef"
        @click="handleTriggerClick"
        class="w-full"
      >
        <slot />
      </component>
    </slot>
  </div>
</template>
