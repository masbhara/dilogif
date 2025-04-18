<script setup lang="ts">
import { cn } from '@/lib/utils'
import { ref, inject, defineEmits, provide, onMounted } from 'vue'
import { ChevronDown } from 'lucide-vue-next'

const props = defineProps({
  placeholder: {
    type: String,
    default: 'Pilih opsi'
  },
  class: {
    type: String,
    default: ''
  },
  size: {
    type: String,
    default: 'default',
    validator: (value: string) => ['sm', 'default'].includes(value)
  }
})

const emit = defineEmits(['click'])

// Mendapatkan nilai dari parent Select
const selectValue = inject('select-value', { value: ref(''), update: () => {} })
const selectDisabled = inject('select-disabled', ref(false))

// State untuk dropdown
const isOpen = ref(false)

// Toggle dropdown
const toggleDropdown = () => {
  if (selectDisabled.value) return
  isOpen.value = !isOpen.value
  emit('click')
}

// Provide isOpen state to other components
provide('select-is-open', isOpen)

// Menambahkan focus dan blur tracking
const isFocused = ref(false)
</script>

<template>
  <button
    type="button"
    role="combobox"
    :aria-expanded="isOpen"
    class="border-input data-[placeholder]:text-muted-foreground focus-visible:border-ring focus-visible:ring-ring/50 aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive dark:bg-input/30 dark:hover:bg-input/50 flex w-full items-center justify-between gap-2 rounded-md border bg-transparent px-3 py-2 text-sm whitespace-nowrap shadow-xs transition-[color,box-shadow] outline-none focus-visible:ring-[3px] disabled:cursor-not-allowed disabled:opacity-50 cursor-pointer"
    :class="[
      size === 'sm' ? 'h-8' : 'h-9',
      props.class
    ]"
    @click.prevent="toggleDropdown"
    @mousedown.prevent
    :disabled="selectDisabled"
  >
    <slot>
      <span v-if="selectValue.value.value" class="flex items-center gap-2 line-clamp-1">
        {{ selectValue.value.value }}
      </span>
      <span v-else class="text-muted-foreground">
        {{ placeholder }}
      </span>
    </slot>
    <ChevronDown class="size-4 opacity-50 ml-auto shrink-0" />
  </button>
</template>
