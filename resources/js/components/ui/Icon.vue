<script setup lang="ts">
import { cn } from '@/lib/utils';
import { computed } from 'vue';
import type { FunctionalComponent } from 'vue';
import type { LucideProps } from 'lucide-vue-next';
import { 
  ChevronDown, 
  ChevronUp, 
  Check, 
  Search, 
  X,
  ChevronRight,
  ChevronLeft,
  Home
} from 'lucide-vue-next';

interface Props {
  name: 'chevron-down' | 'chevron-up' | 'check' | 'search' | 'x' | 'chevron-right' | 'chevron-left' | 'home';
  class?: string;
  size?: string | number;
  strokeWidth?: string | number;
  color?: string;
}

const props = withDefaults(defineProps<Props>(), {
  size: 24,
  strokeWidth: 2,
});

const className = computed(() => cn('h-4 w-4', props.class));

type IconMap = Record<Props['name'], FunctionalComponent<LucideProps>>;

const iconMap: IconMap = {
  'chevron-down': ChevronDown,
  'chevron-up': ChevronUp,
  'check': Check,
  'search': Search,
  'x': X,
  'chevron-right': ChevronRight,
  'chevron-left': ChevronLeft,
  'home': Home
};

const iconComponent = computed(() => {
  return iconMap[props.name];
});
</script>

<template>
  <component 
    :is="iconComponent" 
    :class="className" 
    :size="typeof size === 'string' ? parseInt(size, 10) : size" 
    :stroke-width="typeof strokeWidth === 'string' ? parseInt(strokeWidth, 10) : strokeWidth" 
    :color="color" 
  />
</template>
