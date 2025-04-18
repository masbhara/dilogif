<template>
  <Head title="Lacak Pesanan" />

  <MainLayout>
    <div class="bg-gray-50 py-12">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <div class="bg-white p-4 rounded-lg shadow-sm mb-8">
          <Breadcrumb :items="breadcrumbItems" />
        </div>
        
        <h1 class="text-3xl font-bold mb-8 text-center">Lacak Pesanan</h1>
        
        <!-- Tracking Form -->
        <div class="bg-white rounded-xl shadow-sm p-8 mb-8">
          <form @submit.prevent="trackOrder">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
              <div>
                <label for="order_number" class="block text-sm font-medium text-gray-700 mb-1">Nomor Pesanan <span class="text-red-500">*</span></label>
                <input 
                  id="order_number"
                  v-model="form.order_number" 
                  type="text" 
                  placeholder="Masukkan nomor pesanan Anda" 
                  class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500"
                  required
                />
              </div>
              <div>
                <label for="customer_phone" class="block text-sm font-medium text-gray-700 mb-1">Nomor WhatsApp <span class="text-red-500">*</span></label>
                <input 
                  id="customer_phone"
                  v-model="form.customer_phone" 
                  type="text" 
                  placeholder="Masukkan nomor WhatsApp Anda" 
                  class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500"
                  required
                />
              </div>
            </div>
            <div class="flex justify-center">
              <Button 
                type="submit" 
                class="w-full md:w-auto md:min-w-[200px]"
                colorScheme="primary"
                :disabled="isSubmitting"
              >
                <SearchIcon v-if="!isSubmitting" class="h-4 w-4 mr-2" />
                <ButtonLoader v-else />
                {{ isSubmitting ? 'Mencari...' : 'Lacak Pesanan' }}
              </Button>
            </div>
          </form>
        </div>
        
        <!-- Order Not Found -->
        <div 
          v-if="found === false" 
          class="bg-white rounded-xl shadow-sm p-8 text-center"
        >
          <div class="mb-4">
            <div class="mx-auto h-20 w-20 flex items-center justify-center rounded-full bg-red-100">
              <XIcon class="h-10 w-10 text-red-600" />
            </div>
          </div>
          <h2 class="text-xl font-semibold mb-2">Pesanan Tidak Ditemukan</h2>
          <p class="text-gray-600 mb-6">{{ message }}</p>
          <Button variant="outline" colorScheme="outline" @click="resetForm">Coba Lagi</Button>
        </div>
        
        <!-- Order Found -->
        <div v-if="found === true">
          <!-- Order Status Card -->
          <div class="bg-white rounded-xl shadow-sm p-8 mb-8">
            <div class="text-center mb-8">
              <div class="mb-4">
                <div 
                  class="mx-auto h-20 w-20 flex items-center justify-center rounded-full"
                  :class="{
                    'bg-yellow-100': order.status === 'pending',
                    'bg-blue-100': order.status === 'processing',
                    'bg-purple-100': order.status === 'review',
                    'bg-green-100': order.status === 'completed',
                    'bg-red-100': order.status === 'cancelled'
                  }"
                >
                  <ClockIcon v-if="order.status === 'pending'" class="h-10 w-10 text-yellow-600" />
                  <ActivityIcon v-else-if="order.status === 'processing'" class="h-10 w-10 text-blue-600" />
                  <SearchIcon v-else-if="order.status === 'review'" class="h-10 w-10 text-purple-600" />
                  <CheckCircleIcon v-else-if="order.status === 'completed'" class="h-10 w-10 text-green-600" />
                  <XCircleIcon v-else-if="order.status === 'cancelled'" class="h-10 w-10 text-red-600" />
                </div>
              </div>
              <h2 class="text-xl font-semibold mb-2">Pesanan #{{ order.order_number }}</h2>
              <div class="inline-block">
                <Badge 
                  variant="outline" 
                  class="text-sm px-3 py-1"
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
              </div>
              <p class="text-gray-500 mt-2">{{ formatDate(order.created_at) }}</p>
            </div>
            
            <!-- Order Progress -->
            <div class="max-w-2xl mx-auto">
              <div class="relative">
                <!-- Progress Line -->
                <div class="absolute top-5 left-[15px] right-[15px] h-0.5 bg-gray-200"></div>
                
                <!-- Progress Steps -->
                <div class="relative flex justify-between">
                  <!-- Pending Step -->
                  <div class="flex flex-col items-center">
                    <div 
                      class="w-10 h-10 rounded-full flex items-center justify-center z-10"
                      :class="order.status === 'pending' || order.status === 'processing' || order.status === 'review' || order.status === 'completed' ? 'bg-primary-600 text-white' : 'bg-gray-200 text-gray-400'"
                    >
                      <ShoppingCartIcon class="h-5 w-5" />
                    </div>
                    <div class="text-xs text-center mt-2">Pesanan Dibuat</div>
                  </div>
                  
                  <!-- Processing Step -->
                  <div class="flex flex-col items-center">
                    <div 
                      class="w-10 h-10 rounded-full flex items-center justify-center z-10"
                      :class="order.status === 'processing' || order.status === 'review' || order.status === 'completed' ? 'bg-primary-600 text-white' : 'bg-gray-200 text-gray-400'"
                    >
                      <ClipboardIcon class="h-5 w-5" />
                    </div>
                    <div class="text-xs text-center mt-2">Diproses</div>
                  </div>
                  
                  <!-- Review Step -->
                  <div class="flex flex-col items-center">
                    <div 
                      class="w-10 h-10 rounded-full flex items-center justify-center z-10"
                      :class="order.status === 'review' || order.status === 'completed' ? 'bg-primary-600 text-white' : 'bg-gray-200 text-gray-400'"
                    >
                      <SearchIcon class="h-5 w-5" />
                    </div>
                    <div class="text-xs text-center mt-2">Review</div>
                  </div>
                  
                  <!-- Completed Step -->
                  <div class="flex flex-col items-center">
                    <div 
                      class="w-10 h-10 rounded-full flex items-center justify-center z-10"
                      :class="order.status === 'completed' ? 'bg-primary-600 text-white' : 'bg-gray-200 text-gray-400'"
                    >
                      <CheckIcon class="h-5 w-5" />
                    </div>
                    <div class="text-xs text-center mt-2">Selesai</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Order Details -->
          <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-200">
              <h3 class="text-lg font-medium">Detail Pesanan</h3>
            </div>
            
            <div class="p-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Customer Info -->
                <div>
                  <h4 class="text-sm font-medium text-gray-500 mb-2">Informasi Pelanggan</h4>
                  <div class="bg-gray-50 rounded-lg p-4">
                    <div class="mb-2">
                      <p class="text-xs text-gray-500">Nama</p>
                      <p class="font-medium">{{ order.customer_name }}</p>
                    </div>
                    <div>
                      <p class="text-xs text-gray-500">Nomor WhatsApp</p>
                      <p class="font-medium">{{ order.customer_phone }}</p>
                    </div>
                  </div>
                </div>
                
                <!-- Payment Summary -->
                <div>
                  <h4 class="text-sm font-medium text-gray-500 mb-2">Ringkasan Pembayaran</h4>
                  <div class="bg-gray-50 rounded-lg p-4">
                    <div class="space-y-1 mb-2">
                      <div class="flex justify-between">
                        <p class="text-xs text-gray-500">Subtotal</p>
                        <p class="text-sm font-medium">{{ formatPrice(order.subtotal) }}</p>
                      </div>
                      <div class="flex justify-between">
                        <p class="text-xs text-gray-500">Biaya Admin</p>
                        <p class="text-sm font-medium">{{ formatPrice(order.admin_fee) }}</p>
                      </div>
                      <div class="flex justify-between">
                        <p class="text-xs text-gray-500">Diskon</p>
                        <p class="text-sm font-medium text-green-600">-{{ formatPrice(order.discount) }}</p>
                      </div>
                    </div>
                    <div class="pt-2 border-t border-gray-200 flex justify-between">
                      <p class="text-sm font-medium">Total</p>
                      <p class="text-sm font-bold text-primary-600">{{ formatPrice(order.total_amount) }}</p>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Order Items -->
              <h4 class="text-sm font-medium text-gray-500 mb-2">Produk yang Dipesan</h4>
              <div class="border border-gray-200 rounded-lg overflow-hidden mb-6">
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
              
              <!-- Notes -->
              <div v-if="order.notes">
                <h4 class="text-sm font-medium text-gray-500 mb-2">Catatan</h4>
                <div class="bg-gray-50 rounded-lg p-4">
                  <p class="text-sm text-gray-700">{{ order.notes }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </MainLayout>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import { Button, ButtonLoader } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import Breadcrumb from '@/components/ui/breadcrumb.vue';
import MainLayout from '@/components/layout/MainLayout.vue';
import { 
  SearchIcon, 
  XIcon, 
  ClockIcon, 
  CheckCircleIcon, 
  XCircleIcon, 
  ActivityIcon,
  ShoppingCartIcon,
  ClipboardIcon,
  CheckIcon
} from 'lucide-vue-next';

const props = defineProps({
  order: Object,
  found: {
    type: Boolean,
    default: null
  },
  message: {
    type: String,
    default: 'Pesanan tidak ditemukan dengan nomor pesanan dan nomor telepon yang Anda masukkan.'
  }
});

// State
const isSubmitting = ref(false);
const form = ref({
  order_number: '',
  customer_phone: ''
});

// Breadcrumb items
const breadcrumbItems = computed(() => [
  { label: 'Beranda', href: route('home') },
  { label: 'Lacak Pesanan' }
]);

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
    month: 'long',
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

const trackOrder = () => {
  if (isSubmitting.value) return;
  
  isSubmitting.value = true;
  
  router.get(route('orders.track'), form.value, {
    preserveState: true,
    onFinish: () => {
      isSubmitting.value = false;
    }
  });
};

const resetForm = () => {
  form.value = {
    order_number: '',
    customer_phone: ''
  };
};

// Init form values from URL params
watch(() => window.location.search, (search) => {
  const params = new URLSearchParams(search);
  
  if (params.has('order_number')) {
    form.value.order_number = params.get('order_number');
  }
  
  if (params.has('customer_phone')) {
    form.value.customer_phone = params.get('customer_phone');
  }
}, { immediate: true });
</script> 