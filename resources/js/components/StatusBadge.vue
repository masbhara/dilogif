<script setup>
import { Badge } from '@/components/ui/badge';
import { computed } from 'vue';

const props = defineProps({
  status: {
    type: String,
    required: true
  },
  statusMap: {
    type: Object,
    default: () => ({})
  },
  showDot: {
    type: Boolean,
    default: true
  }
});

const badgeClass = computed(() => {
  const statusStyles = {
    active: 'bg-emerald-100 text-emerald-900 dark:bg-green-900 dark:text-green-300 border border-emerald-300 dark:border-green-800',
    inactive: 'bg-amber-100 text-amber-900 dark:bg-yellow-900 dark:text-yellow-300 border border-amber-300 dark:border-yellow-800',
    blocked: 'bg-rose-100 text-rose-900 dark:bg-red-900 dark:text-red-300 border border-rose-300 dark:border-red-800',
    rejected: 'bg-slate-100 text-slate-900 dark:bg-gray-900 dark:text-gray-300 border border-slate-300 dark:border-gray-800',
    pending: 'bg-amber-100 text-amber-900 dark:bg-yellow-900 dark:text-yellow-300 border border-amber-300 dark:border-yellow-800',
    processing: 'bg-blue-100 text-blue-900 dark:bg-blue-900 dark:text-blue-300 border border-blue-300 dark:border-blue-800',
    review: 'bg-purple-100 text-purple-900 dark:bg-purple-900 dark:text-purple-300 border border-purple-300 dark:border-purple-800',
    completed: 'bg-emerald-100 text-emerald-900 dark:bg-green-900 dark:text-green-300 border border-emerald-300 dark:border-green-800',
    cancelled: 'bg-rose-100 text-rose-900 dark:bg-red-900 dark:text-red-300 border border-rose-300 dark:border-red-800',
    published: 'bg-emerald-100 text-emerald-900 dark:bg-green-900 dark:text-green-300 border border-emerald-300 dark:border-green-800',
    draft: 'bg-amber-100 text-amber-900 dark:bg-yellow-900 dark:text-yellow-300 border border-amber-300 dark:border-yellow-800',
    default: 'bg-slate-100 text-slate-900 dark:bg-gray-900 dark:text-gray-300 border border-slate-300 dark:border-gray-800'
  };
  
  return statusStyles[props.status] || statusStyles.default;
});

const dotClass = computed(() => {
  return {
    'bg-emerald-600 dark:bg-emerald-400': ['active', 'completed', 'published'].includes(props.status),
    'bg-amber-600 dark:bg-amber-400': ['inactive', 'pending', 'draft'].includes(props.status),
    'bg-rose-600 dark:bg-rose-400': ['blocked', 'cancelled'].includes(props.status),
    'bg-blue-600 dark:bg-blue-400': props.status === 'processing',
    'bg-purple-600 dark:bg-purple-400': props.status === 'review',
    'bg-slate-600 dark:bg-slate-400': !['active', 'inactive', 'blocked', 'pending', 'processing', 'completed', 'cancelled', 'published', 'draft', 'review', 'rejected'].includes(props.status)
  };
});

const statusLabel = computed(() => {
  return props.statusMap[props.status] || props.status;
});
</script>

<template>
  <div class="inline-flex w-fit">
    <Badge 
      variant="outline" 
      :class="[badgeClass, showDot ? 'inline-flex items-center gap-1.5' : '']" 
      class="px-2.5 py-0.5 rounded-full text-xs font-medium w-fit"
    >
      <span v-if="showDot" class="size-2 rounded-full" :class="dotClass"></span>
      <span>{{ statusLabel }}</span>
    </Badge>
  </div>
</template> 