<template>
  <div>
    <Link v-if="as === 'button'" :href="href" :method="method" :as="as" class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
      <slot />
    </Link>
    <Link v-else :href="href" class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
      <slot />
    </Link>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  href: {
    type: String,
    required: true
  },
  as: {
    type: String,
    default: 'a'
  },
  method: {
    type: String,
    default: 'get'
  }
});
</script> 