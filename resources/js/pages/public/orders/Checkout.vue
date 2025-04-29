<template>
  <Head title="Checkout" />

  <MainLayout>
    <div class="bg-slate-50 dark:bg-slate-900 py-12">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <div class="bg-white dark:bg-slate-800 p-4 rounded-lg shadow-sm mb-8">
          <Breadcrumb :items="breadcrumbItems" />
        </div>

        <h1 class="text-3xl font-bold mb-8 text-center text-slate-900 dark:text-white">Checkout</h1>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <!-- Order Form -->
          <div class="lg:col-span-2">
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-6">
              <h2 class="text-lg font-medium text-gray-900 mb-4">Informasi Pesanan</h2>
              
              <form @submit.prevent="submitOrder">
                <!-- Customer Information -->
                <div v-if="!$page.props.auth.user" class="space-y-4 mb-6">
                  <h3 class="text-md font-medium text-gray-900">Informasi Pelanggan</h3>
                  
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                      <input
                        type="text"
                        v-model="form.name"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                        required
                      />
                    </div>
                    
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Email</label>
                      <input
                        type="email"
                        v-model="form.email"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                        required
                      />
                    </div>
                    
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Nomor WhatsApp</label>
                      <input
                        type="tel"
                        v-model="form.phone"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                        required
                      />
                    </div>
                    
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Password</label>
                      <input
                        type="password"
                        v-model="form.password"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                        required
                      />
                    </div>
                  </div>
                </div>

                <!-- Order Notes -->
                <div class="mb-6">
                  <label class="block text-sm font-medium text-gray-700">Catatan Pesanan (Opsional)</label>
                  <textarea
                    v-model="form.notes"
                    rows="3"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                  ></textarea>
                </div>

                <!-- Order Items -->
                <div class="mb-6">
                  <h3 class="text-md font-medium text-gray-900 mb-4">Produk yang Dipesan</h3>
                  <div class="space-y-4">
                    <div v-for="item in cartItems" :key="item.id" class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                      <div class="flex items-center">
                        <img 
                          :src="`/storage/${item.product.featured_image}`" 
                          :alt="item.product.name"
                          class="h-16 w-16 object-cover rounded-md"
                        />
                        <div class="ml-4">
                          <h4 class="text-sm font-medium text-gray-900">{{ item.product.name }}</h4>
                          <p class="text-sm text-gray-500">{{ formatPrice(item.product.price) }} x {{ item.quantity }}</p>
                        </div>
                      </div>
                      <p class="text-sm font-medium text-gray-900">{{ formatPrice(item.product.price * item.quantity) }}</p>
                    </div>
                  </div>
                </div>

                <!-- Submit Button -->
                <Button 
                  type="submit" 
                  colorScheme="primary"
                  class="w-full"
                  :disabled="isSubmitting"
                >
                  <span v-if="!isSubmitting">Proses Pesanan</span>
                  <span v-else class="flex items-center">
                    <div class="h-4 w-4 animate-spin rounded-full border-2 border-current border-t-transparent mr-2"></div>
                    Memproses...
                  </span>
                </Button>
              </form>
            </div>
          </div>

          <!-- Order Summary -->
          <div class="lg:col-span-1">
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-6">
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
            </div>
          </div>
        </div>
      </div>
    </div>
  </MainLayout>
</template>

<script setup>
import { Head, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import Breadcrumb from '@/components/ui/breadcrumb.vue';
import MainLayout from '@/components/layout/MainLayout.vue';
import { computed, ref } from 'vue';
import { toast } from 'vue-sonner';

const props = defineProps({
  cartItems: Array,
  summary: Object
});

// State
const isSubmitting = ref(false);
const form = ref({
  name: '',
  email: '',
  phone: '',
  password: '',
  notes: ''
});

// Breadcrumb items
const breadcrumbItems = computed(() => [
  { label: 'Beranda', href: route('home') },
  { label: 'Keranjang', href: route('cart.index') },
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
  isSubmitting.value = true;
  
  router.post(route('orders.store'), form.value, {
    onSuccess: () => {
      toast.success('Pesanan berhasil dibuat');
    },
    onError: (errors) => {
      toast.error('Gagal membuat pesanan');
    },
    onFinish: () => {
      isSubmitting.value = false;
    }
  });
};
</script> 