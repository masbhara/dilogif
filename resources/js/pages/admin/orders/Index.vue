<template>
  <Head title="Kelola Pesanan" />

  <AppLayout>
    <template #header>
      <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <h2 class="text-xl font-semibold leading-tight">
          Kelola Pesanan
        </h2>
        <div class="flex items-center gap-2">
          <Link :href="route('admin.orders.statistics')">
            <Button variant="outline" size="sm" class="h-9">
              <BarChart3Icon class="h-4 w-4 mr-2" />
              Statistik
            </Button>
          </Link>
        </div>
      </div>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Filters -->
        <div class="bg-white p-4 rounded-lg shadow-sm mb-6">
          <div class="flex flex-col md:flex-row gap-4">
            <!-- Search Filter -->
            <div class="flex-1">
              <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
              <div class="relative">
                <input 
                  id="search"
                  v-model="search" 
                  type="text" 
                  placeholder="Nomor pesanan atau nama pelanggan" 
                  class="w-full h-9 pl-10 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500"
                  @keyup.enter="applyFilters"
                />
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <SearchIcon class="h-4 w-4 text-gray-400" />
                </div>
              </div>
            </div>
            
            <!-- Status Filter -->
            <div class="w-full md:w-64">
              <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
              <select 
                id="status"
                v-model="selectedStatus" 
                class="w-full h-9 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500"
              >
                <option value="">Semua Status</option>
                <option v-for="(label, status) in statuses" :key="status" :value="status">
                  {{ label }}
                </option>
              </select>
            </div>
            
            <!-- Filter Buttons -->
            <div class="flex items-end gap-2">
              <Button @click="applyFilters" class="h-9">Filter</Button>
              <Button @click="resetFilters" variant="outline" class="h-9">Reset</Button>
            </div>
          </div>
        </div>
        
        <!-- Orders Table -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
          <div v-if="orders.data.length > 0">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Nomor Pesanan
                  </th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Pelanggan
                  </th>
                  <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Total
                  </th>
                  <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Status
                  </th>
                  <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Tanggal
                  </th>
                  <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Aksi
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="order in orders.data" :key="order.id" class="hover:bg-gray-50">
                  <!-- Order Number -->
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-primary-600">
                    {{ order.order_number }}
                  </td>
                  
                  <!-- Customer -->
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">{{ order.customer_name }}</div>
                    <div class="text-sm text-gray-500">{{ order.customer_phone }}</div>
                  </td>
                  
                  <!-- Total -->
                  <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                    {{ formatPrice(order.total_amount) }}
                  </td>
                  
                  <!-- Status -->
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                    <Badge 
                      variant="outline" 
                      :class="{
                        'border-yellow-400 text-yellow-600 bg-yellow-50': order.status === 'pending',
                        'border-blue-400 text-blue-600 bg-blue-50': order.status === 'processing',
                        'border-purple-400 text-purple-600 bg-purple-50': order.status === 'review',
                        'border-green-400 text-green-600 bg-green-50': order.status === 'completed',
                        'border-red-400 text-red-600 bg-red-50': order.status === 'cancelled'
                      }"
                    >
                      {{ getStatusLabel(order.status) }}
                    </Badge>
                  </td>
                  
                  <!-- Date -->
                  <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                    {{ formatDate(order.created_at) }}
                  </td>
                  
                  <!-- Actions -->
                  <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                    <Link :href="route('admin.orders.show', order.id)" class="text-primary-600 hover:text-primary-900">
                      <Button size="sm" variant="ghost" class="h-8 px-2">
                        <EyeIcon class="h-4 w-4" />
                        <span class="sr-only">Lihat</span>
                      </Button>
                    </Link>
                  </td>
                </tr>
              </tbody>
            </table>
            
            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
              <Pagination :links="orders.links" />
            </div>
          </div>
          
          <!-- Empty State -->
          <div v-else class="text-center py-16">
            <div class="text-6xl mb-4">📋</div>
            <h3 class="text-xl font-semibold mb-2">Tidak ada pesanan ditemukan</h3>
            <p class="text-gray-500 mb-6 max-w-md mx-auto">Belum ada pesanan yang sesuai dengan filter yang Anda pilih.</p>
            <Button @click="resetFilters">Reset Filter</Button>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import Pagination from '@/components/Pagination.vue';
import { SearchIcon, EyeIcon, BarChart3Icon } from 'lucide-vue-next';

const props = defineProps({
  orders: Object,
  filters: Object,
  statuses: Object
});

// State
const search = ref(props.filters?.search || '');
const selectedStatus = ref(props.filters?.status || '');

// Methods
const formatPrice = (price) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0
  }).format(price);
};

const formatDate = (dateString) => {
  const date = new Date(dateString);
  return new Intl.DateTimeFormat('id-ID', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  }).format(date);
};

const getStatusLabel = (status) => {
  return props.statuses[status] || status;
};

const applyFilters = () => {
  router.get(route('admin.orders.index'), {
    search: search.value,
    status: selectedStatus.value
  }, {
    preserveState: true,
    replace: true
  });
};

const resetFilters = () => {
  search.value = '';
  selectedStatus.value = '';
  applyFilters();
};

// Initialize filters from URL
onMounted(() => {
  if (props.filters) {
    search.value = props.filters.search || '';
    selectedStatus.value = props.filters.status || '';
  }
});
</script> 