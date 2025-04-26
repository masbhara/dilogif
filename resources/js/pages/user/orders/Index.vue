<template>
  <Head title="Pesanan Saya" />
  
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 p-4 md:p-6">
      <!-- Header dengan judul halaman -->
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h1 class="text-2xl font-bold text-secondary-900 dark:text-white">Pesanan Saya</h1>
      </div>
      
      <!-- Filters Card -->
      <div class="bg-white dark:bg-slate-800 p-4 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700">
        <div class="flex flex-col sm:flex-row gap-4">
          <!-- Search Filter -->
          <div class="flex-1">
            <label for="search" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Cari</label>
            <div class="relative">
              <input 
                id="search"
                v-model="search" 
                type="text" 
                placeholder="Cari nomor pesanan..." 
                class="w-full h-9 pl-10 px-4 py-2 border border-slate-200 dark:border-slate-700 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100"
                @keyup.enter="applyFilters"
              />
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <SearchIcon class="h-4 w-4 text-slate-400" />
              </div>
            </div>
          </div>
          
          <!-- Status Filter -->
          <div class="w-full md:w-64">
            <label for="status" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Status</label>
            <div class="relative custom-select-container status-select-container" :class="{ 'active': isStatusSelectOpen }">
              <div 
                @click="toggleStatusSelect" 
                class="custom-select-trigger flex w-full items-center justify-between gap-2 rounded-md border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-3 py-2 text-sm shadow-sm hover:border-slate-300 dark:hover:border-slate-600 focus:outline-none focus:ring-0 disabled:cursor-not-allowed disabled:opacity-50 cursor-pointer h-9"
              >
                <span>{{ selectedStatusLabel }}</span>
                <ChevronDownIcon class="h-4 w-4 opacity-50 transition-transform" :class="{ 'rotate-180': isStatusSelectOpen }" />
              </div>
              
              <div 
                v-if="isStatusSelectOpen" 
                class="custom-select-dropdown bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-md shadow-lg mt-1 overflow-hidden z-50"
              >
                <div 
                  class="custom-select-option py-2 px-3 text-slate-900 dark:text-slate-100 hover:bg-slate-50 dark:hover:bg-slate-700 cursor-pointer text-sm"
                  @click="selectStatus('')"
                  :class="{ 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-300 font-medium': selectedStatus === '' }"
                >
                  Semua Status
                </div>
                <div 
                  v-for="(label, status) in statuses" 
                  :key="status"
                  @click="selectStatus(status)"
                  class="custom-select-option py-2 px-3 text-slate-900 dark:text-slate-100 hover:bg-slate-50 dark:hover:bg-slate-700 cursor-pointer text-sm"
                  :class="{ 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-300 font-medium': selectedStatus === status }"
                >
                  {{ label }}
                </div>
              </div>
            </div>
          </div>
          
          <!-- Filter Buttons -->
          <div class="flex items-end gap-2">
            <Button @click="resetFilters" variant="outline">Reset</Button>
          </div>
        </div>
      </div>
      
      <!-- Order List -->
      <div v-if="orders.data.length > 0" class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm rounded-lg border border-slate-200 dark:border-slate-700">
        <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
          <thead class="bg-slate-50 dark:bg-slate-700">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">No. Pesanan</th>
              <th class="px-6 py-3 text-center text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Total</th>
              <th class="px-6 py-3 text-center text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Status</th>
              <th class="px-6 py-3 text-center text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Tanggal</th>
              <th class="px-6 py-3 text-center text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Aksi</th>
            </tr>
          </thead>
          <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
            <tr v-for="order in orders.data" :key="order.id" class="hover:bg-slate-50 dark:hover:bg-slate-700">
              <!-- Order Number -->
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-primary-600 dark:text-primary-400">
                {{ order.order_number }}
              </td>
              
              <!-- Total -->
              <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium text-slate-900 dark:text-slate-200">
                {{ formatPrice(order.total_amount) }}
              </td>
              
              <!-- Status -->
              <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                <Badge 
                  variant="outline" 
                  :class="{
                    'border-yellow-400 text-yellow-600 bg-yellow-50 dark:bg-yellow-900/30 dark:text-yellow-300 dark:border-yellow-700': order.status === 'pending',
                    'border-blue-400 text-blue-600 bg-blue-50 dark:bg-blue-900/30 dark:text-blue-300 dark:border-blue-700': order.status === 'processing',
                    'border-purple-400 text-purple-600 bg-purple-50 dark:bg-purple-900/30 dark:text-purple-300 dark:border-purple-700': order.status === 'review',
                    'border-green-400 text-green-600 bg-green-50 dark:bg-green-900/30 dark:text-green-300 dark:border-green-700': order.status === 'completed',
                    'border-red-400 text-red-600 bg-red-50 dark:bg-red-900/30 dark:text-red-300 dark:border-red-700': order.status === 'cancelled'
                  }"
                >
                  {{ getStatusLabel(order.status) }}
                </Badge>
              </td>
              
              <!-- Date -->
              <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-slate-500 dark:text-slate-400">
                {{ formatDate(order.created_at) }}
              </td>
              
              <!-- Actions -->
              <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                <Link :href="route('orders.track', { order_number: order.order_number, customer_phone: order.customer_phone })" class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300">
                  <Button size="sm" variant="ghost" class="h-8 px-2 cursor-pointer">
                    <EyeIcon class="h-4 w-4" />
                    <span class="sr-only">Lihat Detail</span>
                  </Button>
                </Link>
              </td>
            </tr>
          </tbody>
        </table>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700">
          <Pagination :links="orders.links" />
        </div>
      </div>
      
      <!-- Empty State -->
      <div v-else class="text-center py-16 bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700">
        <div class="text-6xl mb-4">📦</div>
        <h3 class="text-xl font-semibold mb-2 text-slate-900 dark:text-white">Belum ada pesanan</h3>
        <p class="text-slate-500 dark:text-slate-400 mb-6 max-w-md mx-auto">Anda belum melakukan pemesanan atau tidak ada pesanan yang sesuai dengan filter yang dipilih.</p>
        <Button v-if="search || selectedStatus" @click="resetFilters">Reset Filter</Button>
        <div v-else>
          <Link :href="route('products.index')">
            <Button>Mulai Belanja</Button>
          </Link>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted, computed, nextTick, onUnmounted } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import Pagination from '@/components/Pagination.vue';
import { SearchIcon, EyeIcon, ChevronDownIcon } from 'lucide-vue-next';

const props = defineProps({
  orders: Object,
  filters: Object,
  statuses: Object
});

// Breadcrumbs untuk navigasi
const breadcrumbs = [
  {
    title: 'Dashboard',
    href: route('dashboard'),
  },
  {
    title: 'Pesanan Saya',
    href: route('orders.index'),
  },
];

// State
const search = ref(props.filters?.search || '');
const selectedStatus = ref(props.filters?.status || '');

// Status dropdown state
const isStatusSelectOpen = ref(false);
const statusSelectRef = ref(null);

// Computed property untuk label status terpilih
const selectedStatusLabel = computed(() => {
  if (!selectedStatus.value) return 'Semua Status';
  return props.statuses[selectedStatus.value] || 'Semua Status';
});

// Setup dan cleanup event listener
onMounted(() => {
  document.addEventListener('click', handleStatusClickOutside);
  
  nextTick(() => {
    statusSelectRef.value = document.querySelector('.status-select-container');
  });
});

onUnmounted(() => {
  document.removeEventListener('click', handleStatusClickOutside);
});

// Toggle dropdown status
const toggleStatusSelect = () => {
  isStatusSelectOpen.value = !isStatusSelectOpen.value;
};

// Pilih status
const selectStatus = (value) => {
  selectedStatus.value = value;
  isStatusSelectOpen.value = false;
  applyFilters();
};

// Handle click outside untuk status
const handleStatusClickOutside = (evt) => {
  if (statusSelectRef.value && !statusSelectRef.value.contains(evt.target)) {
    isStatusSelectOpen.value = false;
  }
};

// Methods
const formatPrice = (price) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0
  }).format(price);
};

const formatDate = (dateString) => {
  const options = { year: 'numeric', month: 'long', day: 'numeric' };
  return new Date(dateString).toLocaleDateString('id-ID', options);
};

const getStatusLabel = (status) => {
  if (!status) return '';
  if (!props.statuses) return status;
  return props.statuses[status] || status;
};

const applyFilters = () => {
  router.get(
    route('orders.index'),
    { 
      search: search.value, 
      status: selectedStatus.value 
    },
    { 
      preserveState: true,
      replace: true,
      only: ['orders']
    }
  );
};

const resetFilters = () => {
  search.value = '';
  selectedStatus.value = '';
  
  // Pastikan UI diupdate sebelum mengirim request
  nextTick(() => {
    // Gunakan router.visit untuk memuat ulang halaman tanpa filter
    router.visit(route('orders.index'), {
      preserveScroll: false
    });
  });
};

onMounted(() => {
  // Set filters from URL if any
  if (props.filters) {
    search.value = props.filters.search || '';
    selectedStatus.value = props.filters.status || '';
  }
});
</script>

<style>
/* Custom select styling */
.custom-select-container {
  position: relative;
  width: 100%;
  -webkit-tap-highlight-color: transparent;
  border-radius: 0.375rem;
}

.custom-select-dropdown {
  position: absolute;
  top: 100%;
  left: 0;
  width: 100%;
  max-height: 200px;
  overflow-y: auto;
  animation: slideDown 0.15s ease-out;
  z-index: 50;
}

.custom-select-option:first-child {
  border-top-left-radius: 0.375rem;
  border-top-right-radius: 0.375rem;
}

.custom-select-option:last-child {
  border-bottom-left-radius: 0.375rem;
  border-bottom-right-radius: 0.375rem;
}

@keyframes slideDown {
  from { opacity: 0; transform: translateY(-5px); }
  to { opacity: 1; transform: translateY(0); }
}

/* Perbaikan outline saat fokus */
.custom-select-trigger {
  outline: none !important;
  -webkit-appearance: none;
  -webkit-tap-highlight-color: transparent !important;
}

.custom-select-trigger:focus,
.custom-select-trigger:focus-visible,
.custom-select-trigger:active,
.custom-select-trigger:hover,
.custom-select-trigger:-moz-focusring {
  outline: none !important;
  box-shadow: none !important;
  border-color: #0ea5e9 !important;
}

/* Fix untuk Firefox */
.custom-select-trigger:-moz-focusring {
  outline: none !important;
}

/* Fix untuk Safari dan Chrome */
.custom-select-trigger::-webkit-focus-inner {
  border: 0;
}

/* Fix tambahan untuk Chrome */
*:focus {
  outline-color: transparent !important;
}
</style> 