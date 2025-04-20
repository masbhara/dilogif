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
    :class="cn('bg-white dark:bg-slate-800 border-b border-slate-200 dark:border-slate-800 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors', props.class)"
  >
    <slot />
  </tr>
</template>
