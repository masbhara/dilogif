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
  
  // Get size classes from buttonVariants
  const sizeClasses = buttonVariants({ 
    variant: undefined, // Tak perlu warna dari variant
    size: props.size 
  });
  
  // Map variant ke tema yang sesuai jika menggunakan variant bawaan
  let themeClasses = '';
  if (props.variant) {
    // Gunakan tema yang sesuai dengan variant, jika ada
    switch (props.variant) {
      case 'default':
        themeClasses = cn(theme.bg, theme.hover, theme.text, theme.focusRing);
        break;
      case 'destructive':
        themeClasses = cn(buttonTheme.danger.bg, buttonTheme.danger.hover, buttonTheme.danger.text, buttonTheme.danger.focusRing);
        break;
      case 'outline':
        themeClasses = cn(buttonTheme.outline.bg, buttonTheme.outline.hover, buttonTheme.outline.text, buttonTheme.outline.border, buttonTheme.outline.focusRing);
        break;
      case 'secondary':
        themeClasses = cn(buttonTheme.secondary.bg, buttonTheme.secondary.hover, buttonTheme.secondary.text, buttonTheme.secondary.focusRing);
        break;
      case 'ghost':
        themeClasses = cn(buttonTheme.ghost.bg, buttonTheme.ghost.hover, buttonTheme.ghost.text, buttonTheme.ghost.focusRing);
        break;
      case 'link':
        themeClasses = cn(buttonTheme.link.bg, buttonTheme.link.hover, buttonTheme.link.text, buttonTheme.link.focusRing);
        break;
      case 'success':
        themeClasses = cn(buttonTheme.success.bg, buttonTheme.success.hover, buttonTheme.success.text, buttonTheme.success.focusRing);
        break;
      case 'warning':
        themeClasses = cn(buttonTheme.warning.bg, buttonTheme.warning.hover, buttonTheme.warning.text, buttonTheme.warning.focusRing);
        break;
      default:
        themeClasses = cn(theme.bg, theme.hover, theme.text, theme.focusRing);
    }
  } else {
    // Jika tidak ada variant yang ditentukan, gunakan colorScheme
    themeClasses = cn(theme.bg, theme.hover, theme.text, theme.focusRing);
  }
  
  return cn(
    sizeClasses,
    themeClasses,
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
