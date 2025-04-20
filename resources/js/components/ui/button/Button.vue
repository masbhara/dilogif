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
  // Mendapatkan tema warna dari buttonTheme
  const themeKey = props.colorScheme && props.colorScheme in buttonTheme 
                  ? props.colorScheme 
                  : 'primary'; // Selalu fallback ke primary jika tidak valid
  
  const theme = buttonTheme[themeKey];
  
  // Jika variant selain 'default', gunakan struktur dari buttonVariants
  // tapi tetap gunakan warna dari theme.ts
  if (props.variant && props.variant !== 'default') {
    // Hanya ambil sizing dan positioning dari buttonVariants
    const baseClasses = buttonVariants({ 
      variant: undefined, // Tidak menggunakan variant styles
      size: props.size 
    });
    
    // Gunakan warna dari theme
    return cn(
      baseClasses,
      theme.bg,
      theme.hover,
      theme.text,
      theme.focusRing,
      theme.disabled,
      props.class
    );
  }
  
  // Untuk variant default atau tidak ada variant, tetap gunakan warna dari theme
  return cn(
    buttonVariants({ 
      variant: undefined, // Tidak menggunakan variant styles
      size: props.size 
    }),
    theme.bg,
    theme.hover,
    theme.text,
    theme.focusRing,
    theme.disabled,
    props.class
  );
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
