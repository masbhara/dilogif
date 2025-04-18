<script setup lang="ts">
import { inject, computed, ref } from 'vue'

const props = defineProps({
  placeholder: {
    type: String,
    default: 'Pilih opsi'
  }
})

// Mendapatkan nilai dari parent Select
const selectValue = inject('select-value', { value: ref(''), update: () => {} })

// Mencari label yang sesuai dengan value 
const displayValue = computed(() => {
  return selectValue.value.value || props.placeholder
})
</script>

<template>
  <span class="block truncate">
    {{ displayValue }}
  </span>
</template>
