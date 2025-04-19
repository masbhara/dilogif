<template>
  <div
    :class="[
      'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold transition-colors focus-ring',
      variantClasses,
    ]"
  >
    <slot />
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  variant: {
    type: String,
    default: 'default',
    validator: (value) => ['default', 'primary', 'secondary', 'destructive', 'outline', 'success', 'warning', 'ghost'].includes(value),
  },
});

const variantClasses = computed(() => {
  switch (props.variant) {
    case 'default':
      return 'bg-primary-600 text-white hover:bg-primary-700';
    case 'primary':
      return 'bg-primary-600 text-white hover:bg-primary-700';
    case 'secondary':
      return 'bg-secondary-600 text-white hover:bg-secondary-700';
    case 'destructive':
      return 'bg-danger-600 text-white hover:bg-danger-700';
    case 'success':
      return 'bg-success-600 text-white hover:bg-success-700';
    case 'warning':
      return 'bg-warning-500 text-white hover:bg-warning-600';
    case 'outline':
      return 'border border-primary-300 dark:border-primary-700 bg-transparent text-primary-700 dark:text-primary-400 hover:bg-primary-50 dark:hover:bg-primary-950/50';
    case 'ghost':
      return 'bg-primary-100 dark:bg-primary-900/50 text-primary-700 dark:text-primary-400 hover:bg-primary-200 dark:hover:bg-primary-900';
    default:
      return 'bg-primary-600 text-white hover:bg-primary-700';
  }
});
</script>
