<template>
  <Head title="Statistik Pesanan" />

  <AppLayout>
    <template #header>
      <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <h2 class="text-xl font-semibold leading-tight">
          Statistik Pesanan
        </h2>
        <div class="flex items-center gap-2">
          <Link :href="route('admin.orders.index')" class="text-gray-500 hover:text-gray-700">
            <Button variant="outline" size="sm" class="h-9">
              <ArrowLeftIcon class="h-4 w-4 mr-2" />
              Kembali ke Daftar Pesanan
            </Button>
          </Link>
        </div>
      </div>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Order Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
          <!-- Total Orders -->
          <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center mb-1">
              <ReceiptIcon class="h-5 w-5 text-gray-400 mr-2" />
              <h3 class="text-sm font-medium text-gray-500">Total Pesanan</h3>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ statistics.totalOrders }}</p>
          </div>
          
          <!-- Pending Orders -->
          <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center mb-1">
              <ClockIcon class="h-5 w-5 text-yellow-400 mr-2" />
              <h3 class="text-sm font-medium text-gray-500">Menunggu Konfirmasi</h3>
            </div>
            <p class="text-3xl font-bold text-yellow-600">{{ statistics.pendingOrders }}</p>
          </div>
          
          <!-- Processing Orders -->
          <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center mb-1">
              <ActivityIcon class="h-5 w-5 text-blue-400 mr-2" />
              <h3 class="text-sm font-medium text-gray-500">Sedang Diproses</h3>
            </div>
            <p class="text-3xl font-bold text-blue-600">{{ statistics.processingOrders }}</p>
          </div>
          
          <!-- Completed Orders -->
          <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center mb-1">
              <CheckCircleIcon class="h-5 w-5 text-green-400 mr-2" />
              <h3 class="text-sm font-medium text-gray-500">Selesai</h3>
            </div>
            <p class="text-3xl font-bold text-green-600">{{ statistics.completedOrders }}</p>
          </div>
          
          <!-- Cancelled Orders -->
          <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center mb-1">
              <XCircleIcon class="h-5 w-5 text-red-400 mr-2" />
              <h3 class="text-sm font-medium text-gray-500">Dibatalkan</h3>
            </div>
            <p class="text-3xl font-bold text-red-600">{{ statistics.cancelledOrders }}</p>
          </div>
        </div>
        
        <!-- Revenue Card -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
          <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
              <h3 class="text-lg font-medium text-gray-900">Total Pendapatan</h3>
              <p class="text-sm text-gray-500">Total pendapatan dari semua pesanan</p>
            </div>
            <div class="mt-2 md:mt-0">
              <span class="text-3xl font-bold text-primary-600">{{ formatPrice(statistics.totalRevenue) }}</span>
            </div>
          </div>
          
          <!-- Revenue Chart Placeholder - Future Enhancement -->
          <div class="h-64 bg-gray-50 rounded-lg flex items-center justify-center">
            <div class="text-center">
              <BarChart3Icon class="h-12 w-12 text-gray-300 mb-2 mx-auto" />
              <p class="text-gray-500">Grafik pendapatan akan tersedia segera</p>
            </div>
          </div>
        </div>
        
        <!-- Recent Orders -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
          <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Pesanan Terbaru</h3>
          </div>
          
          <div class="overflow-x-auto">
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
                <tr v-for="order in recentOrders" :key="order.id" class="hover:bg-gray-50">
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
          </div>
          
          <div class="p-6 border-t border-gray-200 text-center">
            <Link :href="route('admin.orders.index')" class="text-primary-600 hover:text-primary-800">
              <Button variant="outline">Lihat Semua Pesanan</Button>
            </Link>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { 
  ArrowLeftIcon, 
  EyeIcon, 
  ReceiptIcon, 
  ClockIcon, 
  ActivityIcon, 
  CheckCircleIcon, 
  XCircleIcon, 
  BarChart3Icon
} from 'lucide-vue-next';

const props = defineProps({
  statistics: Object,
  recentOrders: Array
});

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
  const statusMap = {
    'pending': 'Menunggu Konfirmasi',
    'processing': 'Sedang Diproses',
    'review': 'Review',
    'completed': 'Selesai',
    'cancelled': 'Dibatalkan'
  };
  
  return statusMap[status] || status;
};
</script> 