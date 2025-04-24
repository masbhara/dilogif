<template>
  <Head title="Checkout" />

  <MainLayout>
    <div class="bg-gray-50 py-12">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <div class="bg-white p-4 rounded-lg shadow-sm mb-8">
          <Breadcrumb :items="breadcrumbItems" />
        </div>
        
        <h1 class="text-3xl font-bold mb-8">Checkout</h1>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <!-- Checkout Form -->
          <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm p-6">
              <h2 class="text-xl font-semibold mb-6">Informasi Pemesan</h2>
              
              <form @submit.prevent="submitOrder">
                <!-- Customer Name -->
                <div class="mb-4">
                  <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                  <input 
                    id="customer_name"
                    v-model="form.customer_name" 
                    type="text" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500"
                    required
                  />
                  <div v-if="errors.customer_name" class="mt-1 text-sm text-red-600">
                    {{ errors.customer_name }}
                  </div>
                </div>
                
                <!-- Customer Phone -->
                <div class="mb-4">
                  <label for="customer_phone" class="block text-sm font-medium text-gray-700 mb-1">Nomor WhatsApp <span class="text-red-500">*</span></label>
                  <input 
                    id="customer_phone"
                    v-model="form.customer_phone" 
                    type="text" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500"
                    placeholder="Contoh: 08123456789"
                    required
                  />
                  <div v-if="errors.customer_phone" class="mt-1 text-sm text-red-600">
                    {{ errors.customer_phone }}
                  </div>
                </div>
                
                <!-- Customer Email -->
                <div class="mb-4">
                  <label for="customer_email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                  <input 
                    id="customer_email"
                    v-model="form.customer_email" 
                    type="email" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500"
                    placeholder="Contoh: nama@email.com (opsional)"
                  />
                  <div v-if="errors.customer_email" class="mt-1 text-sm text-red-600">
                    {{ errors.customer_email }}
                  </div>
                </div>
                
                <!-- Notes -->
                <div class="mb-6">
                  <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Catatan Pesanan</label>
                  <textarea 
                    id="notes"
                    v-model="form.notes" 
                    rows="3" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500"
                    placeholder="Catatan tambahan untuk pesanan Anda (opsional)"
                  ></textarea>
                  <div v-if="errors.notes" class="mt-1 text-sm text-red-600">
                    {{ errors.notes }}
                  </div>
                </div>
                
                <!-- Products List -->
                <div class="mb-6">
                  <h3 class="text-lg font-medium mb-4">Produk yang Dibeli</h3>
                  <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <div 
                      v-for="(item, index) in cartItems" 
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
                          <p class="text-sm text-gray-500">{{ formatPrice(item.product.price) }} x {{ item.quantity }}</p>
                          <p class="text-sm font-medium">{{ formatPrice(item.product.price * item.quantity) }}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                
                <!-- Submit Button -->
                <div class="mt-8">
                  <Button 
                    type="submit" 
                    class="w-full h-12"
                    colorScheme="primary"
                    :disabled="isSubmitting"
                  >
                    <span v-if="isSubmitting" class="mr-2 h-4 w-4 animate-spin rounded-full border-2 border-current border-t-transparent"></span>
                    {{ isSubmitting ? 'Memproses...' : 'Kirim Pesanan' }}
                  </Button>
                </div>
                
                <div class="mt-4 text-center text-sm text-gray-500">
                  <p>Dengan menekan tombol "Kirim Pesanan", Anda menyetujui syarat dan ketentuan yang berlaku</p>
                </div>
              </form>
            </div>
          </div>
          
          <!-- Order Summary -->
          <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm p-6 sticky top-8">
              <h2 class="text-lg font-medium text-gray-900 mb-4">Ringkasan Pesanan</h2>
              
              <!-- Summary Items -->
              <div class="space-y-3 mb-6">
                <div class="flex justify-between">
                  <p class="text-gray-600">Subtotal ({{ summary.itemCount }} item)</p>
                  <p class="font-medium">{{ formatPrice(summary.subtotal) }}</p>
                </div>
                <div class="flex justify-between">
                  <p class="text-gray-600">Biaya Admin</p>
                  <p class="font-medium">{{ formatPrice(summary.adminFee) }}</p>
                </div>
                <div class="flex justify-between">
                  <p class="text-gray-600">Diskon</p>
                  <p class="font-medium text-green-600">-{{ formatPrice(summary.discount) }}</p>
                </div>
              </div>
              
              <!-- Total -->
              <div class="flex justify-between pt-4 border-t border-gray-200">
                <p class="text-lg font-medium text-gray-900">Total</p>
                <p class="text-lg font-bold text-primary-600">{{ formatPrice(summary.total) }}</p>
              </div>
              
              <!-- Back to Cart -->
              <div class="mt-6">
                <Link :href="route('cart.index')" class="text-primary-600 hover:text-primary-800 text-sm font-medium flex items-center justify-center">
                  <ArrowLeftIcon class="h-4 w-4 mr-1" />
                  Kembali ke Keranjang
                </Link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </MainLayout>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import Breadcrumb from '@/components/ui/breadcrumb.vue';
import MainLayout from '@/components/layout/MainLayout.vue';
import { computed, ref } from 'vue';
import { ArrowLeftIcon } from 'lucide-vue-next';
import { toast } from 'vue-sonner';

const props = defineProps({
  cartItems: Array,
  summary: Object,
  errors: Object
});

// State
const isSubmitting = ref(false);
const form = ref({
  customer_name: '',
  customer_phone: '',
  customer_email: '',
  notes: ''
});

// Breadcrumb items
const breadcrumbItems = computed(() => [
  { label: 'Beranda', href: route('home') },
  { label: 'Keranjang Belanja', href: route('cart.index') },
  { label: 'Checkout' }
]);

// Methods
const formatPrice = (price) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0
  }).format(price);
};

const submitOrder = () => {
  if (isSubmitting.value) return;
  
  isSubmitting.value = true;
  
  router.post(route('orders.store'), form.value, {
    onSuccess: () => {
      // Success will redirect to thank you page
    },
    onError: (errors) => {
      toast.error('Harap periksa formulir Anda untuk kesalahan');
    },
    onFinish: () => {
      isSubmitting.value = false;
    }
  });
};
</script> 