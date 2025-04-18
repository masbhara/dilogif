<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'

const props = defineProps<{
  class?: HTMLAttributes['class']
}>()
</script>

<template>
  <div
    data-slot="card-title"
    :class="cn('leading-none text-lg font-medium text-secondary-950 dark:text-white', props.class)"
  >
    <slot />
  </div>
</template>
