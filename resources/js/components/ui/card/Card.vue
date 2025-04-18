<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'

const props = defineProps<{
  class?: HTMLAttributes['class']
}>()
</script>

<template>
  <div
    data-slot="card"
    :class="
      cn(
        'bg-secondary-50 dark:bg-secondary-900 text-secondary-900 dark:text-white flex flex-col gap-6 rounded-xl border border-secondary-200 dark:border-secondary-800 py-6 shadow-sm',
        props.class,
      )
    "
  >
    <slot />
  </div>
</template>
