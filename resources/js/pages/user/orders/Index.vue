<template>
  <AppLayout :breadcrumbs="[
    { title: 'Dashboard', href: route('dashboard') },
    { title: 'Pesanan Saya' }
  ]">
    <Head title="Pesanan Saya" />
    
    <div class="py-6 px-4 sm:px-6 lg:px-8">
      <div class="bg-secondary-50 rounded-lg shadow-sm">
        <div class="p-6">
          <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-slate-100 flex items-center">
              <ShoppingBag class="h-6 w-6 mr-2" />
              Pesanan Saya
            </h1>
          </div>
          
          <!-- Filters -->
          <div class="flex flex-col sm:flex-row gap-4 mb-6">
            <div class="flex-1">
              <div class="relative rounded-md shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <SearchIcon class="h-5 w-5 text-gray-400" />
                </div>
                <Input
                  v-model="search"
                  type="text"
                  placeholder="Cari nomor pesanan..."
                  class="pl-10"
                  @keyup.enter="applyFilters"
                />
              </div>
            </div>
            
            <div class="w-full sm:w-48">
              <Select v-model="selectedStatus" @update:modelValue="applyFilters">
                <SelectTrigger>
                  <SelectValue placeholder="Filter Status" />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="">Semua Status</SelectItem>
                  <SelectItem v-for="(label, status) in statuses" :key="status" :value="status">
                    {{ label }}
                  </SelectItem>
                </SelectContent>
              </Select>
            </div>
          </div>
          
          <!-- Order List -->
          <div v-if="orders.data.length > 0" class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm rounded-lg border border-gray-200 dark:border-slate-700">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-700 dark:text-slate-50">
              <thead class="bg-gray-50 dark:bg-slate-700">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-50 uppercase tracking-wider">No. Pesanan</th>
                  <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-slate-50 uppercase tracking-wider">Total</th>
                  <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-slate-50 uppercase tracking-wider">Status</th>
                  <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-slate-50 uppercase tracking-wider">Tanggal</th>
                  <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-slate-50 uppercase tracking-wider">Aksi</th>
                </tr>
              </thead>
              <tbody class="bg-white dark:bg-slate-800 divide-y divide-gray-200 dark:divide-slate-700 dark:text-slate-50">
                <tr v-for="order in orders.data" :key="order.id" class="hover:bg-gray-50 dark:hover:bg-slate-700">
                  <!-- Order Number -->
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-primary-600">
                    {{ order.order_number }}
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
                    <!-- Add proper route here once we have a user order detail view -->
                    <Link :href="route('orders.track', { order_number: order.order_number, customer_phone: order.customer_phone })" class="text-primary-600 hover:text-primary-900">
                      <Button size="sm" variant="ghost" class="h-8 px-2 dark:text-slate-50 dark:hover:text-slate-100 cursor-pointer">
                        <EyeIcon class="h-4 w-4" />
                        <span class="sr-only">Lihat Detail</span>
                      </Button>
                    </Link>
                  </td>
                </tr>
              </tbody>
            </table>
            
            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200 dark:border-slate-700">
              <Pagination :links="orders.links" />
            </div>
          </div>
          
          <!-- Empty State -->
          <div v-else class="text-center py-16">
            <div class="text-6xl mb-4">📦</div>
            <h3 class="text-xl font-semibold mb-2">Belum ada pesanan</h3>
            <p class="text-gray-500 mb-6 max-w-md mx-auto">Anda belum melakukan pemesanan atau tidak ada pesanan yang sesuai dengan filter yang dipilih.</p>
            <Button v-if="search || selectedStatus" @click="resetFilters">Reset Filter</Button>
            <div v-else>
              <Link :href="route('products.index')">
                <Button>Mulai Belanja</Button>
              </Link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import Pagination from '@/components/Pagination.vue';
import { SearchIcon, EyeIcon, ShoppingBag } from 'lucide-vue-next';

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
  const options = { year: 'numeric', month: 'long', day: 'numeric' };
  return new Date(dateString).toLocaleDateString('id-ID', options);
};

const getStatusLabel = (status) => {
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
  applyFilters();
};

onMounted(() => {
  // Set filters from URL if any
  if (window.location.search) {
    const urlParams = new URLSearchParams(window.location.search);
    search.value = urlParams.get('search') || '';
    selectedStatus.value = urlParams.get('status') || '';
  }
});
</script> 