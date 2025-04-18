<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'
import { Primitive, type PrimitiveProps } from 'reka-ui'
import { type ButtonVariants, buttonVariants } from '.'
import { buttonTheme, getButtonClasses } from './theme'
import { computed } from 'vue'

interface Props extends PrimitiveProps {
  variant?: ButtonVariants['variant']
  size?: ButtonVariants['size']
  class?: HTMLAttributes['class']
  colorScheme?: keyof typeof buttonTheme
}

const props = withDefaults(defineProps<Props>(), {
  as: 'button',
  colorScheme: 'primary'
})

// Mengkombinasikan kelas dari buttonVariants dan buttonTheme
const buttonClass = computed(() => {
  // Jika colorScheme diisi, gunakan tema dari buttonTheme
  if (props.colorScheme && props.colorScheme in buttonTheme) {
    const theme = buttonTheme[props.colorScheme];
    
    // Gabungkan dengan buttonVariants untuk size/layout
    return cn(
      buttonVariants({ variant: undefined, size: props.size }),  // Tidak menyertakan variant
      theme.bg,
      theme.hover,
      theme.text,
      props.class
    );
  }
  
  // Fallback ke style asli jika colorScheme tidak ada
  return cn(buttonVariants({ variant: props.variant, size: props.size }), props.class);
});
</script>

<template>
  <Primitive
    data-slot="button"
    :as="as"
    :as-child="asChild"
    :class="buttonClass"
  >
    <slot />
  </Primitive>
</template>
