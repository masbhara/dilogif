<template>
  <div class="relative">
    <div @click="open = ! open">
      <slot name="trigger"></slot>
    </div>

    <div v-show="open" 
         class="absolute z-50 mt-2 rounded-md shadow-lg"
         @click="open = false"
         :class="[widthClass, alignmentClasses]">
      <div class="rounded-md ring-1 ring-black ring-opacity-5 bg-white py-1">
        <slot></slot>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';

const props = defineProps({
  align: {
    type: String,
    default: 'right'
  },
  width: {
    type: String,
    default: '48'
  },
  contentClasses: {
    type: Array,
    default: () => ['py-1', 'bg-white']
  }
});

const open = ref(false);

const closeOnEscape = (e) => {
  if (open.value && e.key === 'Escape') {
    open.value = false;
  }
};

onMounted(() => {
  document.addEventListener('keydown', closeOnEscape);
});

onUnmounted(() => {
  document.removeEventListener('keydown', closeOnEscape);
});

const widthClass = computed(() => {
  return {
    '48': 'w-48',
  }[props.width.toString()];
});

const alignmentClasses = computed(() => {
  if (props.align === 'left') {
    return 'origin-top-left left-0';
  } else if (props.align === 'right') {
    return 'origin-top-right right-0';
  } else {
    return 'origin-top';
  }
});

defineExpose({ open });
</script> 