<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'

const props = defineProps<{
  class?: HTMLAttributes['class']
}>()
</script>

<template>
  <tr
    data-slot="table-row"
    :class="cn('bg-white dark:bg-secondary-900 border-b border-secondary-200 dark:border-secondary-800 hover:bg-secondary-100 dark:hover:bg-secondary-800 transition-colors', props.class)"
  >
    <slot />
  </tr>
</template>
