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
        <div class="bg-white dark:bg-slate-800 p-4 rounded-lg shadow-sm mb-6 border border-slate-200 dark:border-slate-700">
          <div class="flex flex-col md:flex-row gap-4">
            <!-- Search Filter -->
            <div class="flex-1">
              <label for="search" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Cari</label>
              <div class="relative">
                <input 
                  id="search"
                  v-model="search" 
                  type="text" 
                  placeholder="Nomor pesanan atau nama pelanggan" 
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
              <select 
                id="status"
                v-model="selectedStatus" 
                class="w-full h-9 px-4 py-2 border border-slate-200 dark:border-slate-700 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100"
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
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm overflow-hidden border border-slate-200 dark:border-slate-700">
          <div v-if="orders.data.length > 0">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
              <thead class="bg-slate-50 dark:bg-slate-800/60">
                <tr>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                    Nomor Pesanan
                  </th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                    Pelanggan
                  </th>
                  <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                    Total
                  </th>
                  <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                    Status
                  </th>
                  <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                    Tanggal
                  </th>
                  <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                    Aksi
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                <tr v-for="order in orders.data" :key="order.id" class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                  <!-- Order Number -->
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-primary-600 dark:text-primary-400">
                    {{ order.order_number }}
                  </td>
                  
                  <!-- Customer -->
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-slate-900 dark:text-slate-100">{{ order.customer_name }}</div>
                    <div class="text-sm text-slate-500 dark:text-slate-400">{{ order.customer_phone }}</div>
                  </td>
                  
                  <!-- Total -->
                  <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium text-slate-900 dark:text-slate-100">
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
                  <td class="py-4 px-4 text-right">
                    <div class="flex items-center justify-end gap-1">
                      <Link :href="route('admin.orders.show', order.id)" class="text-primary-500 hover:text-primary-600 dark:text-primary-400 dark:hover:text-primary-300">
                        Lihat
                      </Link>
                      <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                          <MenuTriggerButton />
                        </DropdownMenuTrigger>
                        <DropdownMenuContent align="end">
                          <DropdownMenuItem>
                            <Link :href="route('admin.orders.show', order.id)" class="flex w-full">
                              Lihat detail
                            </Link>
                          </DropdownMenuItem>
                        </DropdownMenuContent>
                      </DropdownMenu>
                    </div>
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
import { SearchIcon, EyeIcon, BarChart3Icon, MoreHorizontal } from 'lucide-vue-next';
import { DropdownMenu, DropdownMenuContent, DropdownMenuTrigger, DropdownMenuItem } from '@/components/ui/dropdown-menu';
import { MenuTriggerButton } from '@/components/ui/menu';
import currencies from "@/lib/currency.js";

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