<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

interface Props {
  href: string;
  method?: 'get' | 'post' | 'put' | 'patch' | 'delete';
  as?: string;
}

defineProps<Props>();
</script>

<template>
  <Link 
    :href="href" 
    :method="method" 
    :as="as"
    class="text-primary-600 hover:text-primary-500 underline underline-offset-4"
  >
    <slot />
  </Link>
</template> 