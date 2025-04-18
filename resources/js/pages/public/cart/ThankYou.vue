<template>
  <Head title="Pesanan Berhasil" />

  <MainLayout>
    <div class="bg-gray-50 py-12">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <div class="bg-white p-4 rounded-lg shadow-sm mb-8">
          <Breadcrumb :items="breadcrumbItems" />
        </div>
        
        <!-- Success Message -->
        <div class="bg-white rounded-xl shadow-sm p-8 text-center mb-8">
          <div class="mb-4">
            <div class="mx-auto h-24 w-24 flex items-center justify-center rounded-full bg-green-100">
              <CheckCircleIcon class="h-16 w-16 text-green-600" />
            </div>
          </div>
          <h1 class="text-3xl font-bold text-gray-900 mb-2">Pesanan Berhasil!</h1>
          <p class="text-lg text-gray-600 mb-6">Terima kasih telah melakukan pemesanan di toko kami.</p>
          <div class="inline-block bg-gray-100 rounded-lg px-6 py-3 mb-6">
            <p class="text-gray-700 font-medium">Nomor Pesanan:</p>
            <p class="text-2xl font-bold text-primary-600">{{ order.order_number }}</p>
          </div>
          <p class="text-gray-600 mb-8">Silakan simpan nomor pesanan ini sebagai referensi. Anda juga dapat melacak status pesanan Anda menggunakan tombol di bawah.</p>
          <div class="flex flex-col sm:flex-row justify-center gap-4">
            <Link :href="route('orders.track')" class="inline-flex items-center justify-center">
              <Button colorScheme="primary" class="px-6">
                <SearchIcon class="h-4 w-4 mr-2" />
                Lacak Pesanan
              </Button>
            </Link>
            <Link :href="route('products.index')" class="inline-flex items-center justify-center">
              <Button variant="outline" colorScheme="outline" class="px-6">
                <ShoppingBagIcon class="h-4 w-4 mr-2" />
                Lanjut Belanja
              </Button>
            </Link>
          </div>
        </div>
        
        <!-- Order Details -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
          <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold mb-0">Detail Pesanan</h2>
          </div>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6">
            <!-- Customer Info -->
            <div>
              <h3 class="text-lg font-medium mb-3">Informasi Pemesan</h3>
              <div class="bg-gray-50 rounded-lg p-4">
                <div class="mb-3">
                  <p class="text-sm text-gray-500">Nama</p>
                  <p class="font-medium">{{ order.customer_name }}</p>
                </div>
                <div class="mb-3">
                  <p class="text-sm text-gray-500">Nomor WhatsApp</p>
                  <p class="font-medium">{{ order.customer_phone }}</p>
                </div>
                <div v-if="order.customer_email">
                  <p class="text-sm text-gray-500">Email</p>
                  <p class="font-medium">{{ order.customer_email }}</p>
                </div>
              </div>
            </div>
            
            <!-- Order Summary -->
            <div>
              <h3 class="text-lg font-medium mb-3">Ringkasan Pembayaran</h3>
              <div class="bg-gray-50 rounded-lg p-4">
                <div class="space-y-2 mb-3">
                  <div class="flex justify-between">
                    <p class="text-sm text-gray-500">Subtotal</p>
                    <p class="font-medium">{{ formatPrice(order.subtotal) }}</p>
                  </div>
                  <div class="flex justify-between">
                    <p class="text-sm text-gray-500">Biaya Admin</p>
                    <p class="font-medium">{{ formatPrice(order.admin_fee) }}</p>
                  </div>
                  <div class="flex justify-between">
                    <p class="text-sm text-gray-500">Diskon</p>
                    <p class="font-medium text-green-600">-{{ formatPrice(order.discount) }}</p>
                  </div>
                </div>
                <div class="pt-3 border-t border-gray-200 flex justify-between">
                  <p class="font-medium">Total</p>
                  <p class="font-bold text-primary-600">{{ formatPrice(order.total_amount) }}</p>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Order Items -->
          <div class="p-6 border-t border-gray-200">
            <h3 class="text-lg font-medium mb-4">Produk yang Dipesan</h3>
            <div class="border border-gray-200 rounded-lg overflow-hidden">
              <div 
                v-for="(item, index) in order.items" 
                :key="item.id" 
                :class="{'border-t border-gray-200': index > 0}"
                class="p-4 flex items-center"
              >
                <div class="h-16 w-16 flex-shrink-0 overflow-hidden rounded-md border border-gray-200">
                  <img 
                    :src="`/storage/${item.product.featured_image}`" 
                    :alt="item.product.name" 
                    class="h-full w-full object-cover object-center"
                  />
                </div>
                <div class="ml-4 flex-1">
                  <h4 class="text-sm font-medium">{{ item.product.name }}</h4>
                  <div class="mt-1 flex justify-between">
                    <p class="text-sm text-gray-500">{{ formatPrice(item.price) }} x {{ item.quantity }}</p>
                    <p class="text-sm font-medium">{{ formatPrice(item.subtotal) }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Notes and Status -->
          <div class="p-6 border-t border-gray-200 grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Notes -->
            <div>
              <h3 class="text-lg font-medium mb-3">Catatan Pesanan</h3>
              <div class="bg-gray-50 rounded-lg p-4">
                <p v-if="order.notes" class="text-gray-700">{{ order.notes }}</p>
                <p v-else class="text-gray-500 italic">Tidak ada catatan</p>
              </div>
            </div>
            
            <!-- Status -->
            <div>
              <h3 class="text-lg font-medium mb-3">Status Pesanan</h3>
              <div class="bg-gray-50 rounded-lg p-4">
                <div class="inline-flex items-center">
                  <span 
                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                    :class="{
                      'bg-yellow-100 text-yellow-800': order.status === 'pending',
                      'bg-blue-100 text-blue-800': order.status === 'processing',
                      'bg-purple-100 text-purple-800': order.status === 'review',
                      'bg-green-100 text-green-800': order.status === 'completed',
                      'bg-red-100 text-red-800': order.status === 'cancelled'
                    }"
                  >
                    <Badge 
                      variant="outline" 
                      :class="{
                        'border-yellow-400 text-yellow-600': order.status === 'pending',
                        'border-blue-400 text-blue-600': order.status === 'processing',
                        'border-purple-400 text-purple-600': order.status === 'review',
                        'border-green-400 text-green-600': order.status === 'completed',
                        'border-red-400 text-red-600': order.status === 'cancelled'
                      }"
                    >
                      {{ getStatusLabel(order.status) }}
                    </Badge>
                  </span>
                </div>
                <p class="mt-2 text-sm text-gray-500">Pesanan Anda akan segera diproses oleh tim kami.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </MainLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import Breadcrumb from '@/components/ui/breadcrumb.vue';
import MainLayout from '@/components/layout/MainLayout.vue';
import { computed } from 'vue';
import { CheckCircleIcon, ShoppingBagIcon, SearchIcon } from 'lucide-vue-next';

const props = defineProps({
  order: Object
});

// Breadcrumb items
const breadcrumbItems = computed(() => [
  { label: 'Beranda', href: route('home') },
  { label: 'Pesanan Berhasil' }
]);

// Methods
const formatPrice = (price) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0
  }).format(price);
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