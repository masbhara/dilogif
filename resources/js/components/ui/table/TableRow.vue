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
    :class="cn('hover:bg-secondary-100 dark:hover:bg-secondary-800 data-[state=selected]:bg-secondary-200 dark:data-[state=selected]:bg-secondary-700 border-b border-secondary-200 dark:border-secondary-800 transition-colors', props.class)"
  >
    <slot />
  </tr>
</template>
