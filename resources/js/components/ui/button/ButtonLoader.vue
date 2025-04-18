<script setup lang="ts">
import { computed } from 'vue';
import { getSpinnerClasses } from './theme';

interface Props {
  colorScheme?: string;
  size?: 'sm' | 'md' | 'lg';
}

const props = withDefaults(defineProps<Props>(), {
  colorScheme: 'primary',
  size: 'md'
});

// Menentukan ukuran spinner berdasarkan prop size
const spinnerSize = computed(() => {
  switch (props.size) {
    case 'sm': return 'h-3 w-3';
    case 'lg': return 'h-5 w-5';
    default: return 'h-4 w-4'; // medium
  }
});

// Mendapatkan kelas spinner berdasarkan colorScheme
const spinnerClass = computed(() => {
  return getSpinnerClasses(props.colorScheme as any);
});
</script>

<template>
  <div :class="[
    spinnerSize, 
    'mr-2 animate-spin rounded-full border-2', 
    spinnerClass
  ]"></div>
</template> 