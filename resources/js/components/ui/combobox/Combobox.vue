<template>
  <div class="relative w-full">
    <div
      class="relative w-full cursor-default overflow-hidden rounded-md border border-input bg-background text-sm ring-offset-background focus:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
    >
      <div class="flex items-center">
        <input
          type="text"
          :placeholder="placeholder"
          class="w-full bg-background py-2 pl-3 pr-10 text-sm outline-none disabled:cursor-not-allowed disabled:opacity-50"
          :value="inputValue"
          @input="filterOptions"
          @focus="open = true"
        />
        <button
          type="button"
          class="absolute inset-y-0 right-0 flex h-full items-center pr-3"
          @click="toggleOpen"
        >
          <ChevronDown v-if="!open" class="h-4 w-4 opacity-60" />
          <ChevronUp v-else class="h-4 w-4 opacity-60" />
        </button>
      </div>
    </div>

    <div
      v-if="open"
      class="absolute z-50 mt-1 max-h-60 w-full overflow-auto rounded-md border bg-popover text-popover-foreground shadow-md"
    >
      <div 
        v-if="filteredOptions.length > 0" 
        class="overflow-y-auto py-1"
      >
        <div
          v-for="option in filteredOptions"
          :key="option.value"
          class="relative flex cursor-pointer select-none items-center rounded-sm px-3 py-2 text-sm hover:bg-muted"
          :class="{ 'bg-muted': option.value === modelValue }"
          @click="selectOption(option)"
        >
          {{ option.label }}
          <Check 
            v-if="option.value === modelValue" 
            class="ml-auto h-4 w-4"
          />
        </div>
      </div>
      <div
        v-else
        class="py-6 text-center text-sm"
      >
        Tidak ada hasil
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted, onBeforeUnmount } from 'vue';
import { ChevronDown, ChevronUp, Check } from 'lucide-vue-next';

const props = defineProps({
  modelValue: {
    type: [String, Number, null],
    default: null
  },
  options: {
    type: Array,
    default: () => []
  },
  placeholder: {
    type: String,
    default: "Pilih opsi..."
  }
});

const emit = defineEmits(['update:modelValue']);

const open = ref(false);
const searchQuery = ref('');
const filteredOptions = ref([...props.options]);

const inputValue = computed(() => {
  if (!props.modelValue) return '';
  const option = props.options.find(opt => opt.value === props.modelValue);
  return option ? option.label : '';
});

function filterOptions(e) {
  searchQuery.value = e.target.value;
  if (!searchQuery.value) {
    filteredOptions.value = [...props.options];
    return;
  }
  
  filteredOptions.value = props.options.filter(option => 
    option.label.toLowerCase().includes(searchQuery.value.toLowerCase())
  );
}

function selectOption(option) {
  emit('update:modelValue', option.value);
  searchQuery.value = '';
  open.value = false;
}

function toggleOpen() {
  open.value = !open.value;
  if (!open.value) {
    searchQuery.value = '';
    filteredOptions.value = [...props.options];
  }
}

// Close dropdown when clicking outside
function handleClickOutside(event) {
  const combobox = event.target.closest('.relative.w-full');
  if (!combobox) {
    open.value = false;
    searchQuery.value = '';
    filteredOptions.value = [...props.options];
  }
}

onMounted(() => {
  document.addEventListener('click', handleClickOutside);
});

onBeforeUnmount(() => {
  document.removeEventListener('click', handleClickOutside);
});

// Update filtered options when props.options changes
watch(() => props.options, (newOptions) => {
  filteredOptions.value = [...newOptions];
}, { deep: true });
</script> 