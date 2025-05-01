<template>
  <Head title="Checkout" />

  <MainLayout>
    <div class="bg-slate-50 dark:bg-background py-12">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <div class="bg-white dark:bg-slate-900 p-4 rounded-lg shadow-sm mb-8">
          <Breadcrumb :items="breadcrumbItems" />
        </div>

        <h1 class="text-3xl font-bold mb-8 text-center text-slate-900 dark:text-white">Checkout</h1>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <!-- Order Form -->
          <div class="lg:col-span-2">
            <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm p-6">
              <h2 class="text-lg font-medium text-gray-900 mb-4 dark:text-white">Informasi Pesanan</h2>
              
              <form @submit.prevent="submitOrder">
                <!-- Customer Information -->
                <div class="space-y-6">
                  <!-- Customer Name -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-white">Nama Lengkap *</label>
                    <input
                      type="text"
                      v-model="form.customer_name"
                      class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 focus:border-primary-700 focus:ring-primary-500 bg-white dark:bg-slate-800 text-gray-900 dark:text-white p-2"
                      :disabled="isSubmitting"
                      required
                    />
                  </div>

                  <!-- Customer Email -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-white">Email *</label>
                    <input
                      type="email"
                      v-model="form.customer_email"
                      class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 focus:border-primary-700 focus:ring-primary-500 bg-white dark:bg-slate-800 text-gray-900 dark:text-white p-2"
                      :disabled="isSubmitting"
                      required
                    />
                  </div>

                  <!-- Customer Phone -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-white">Nomor WhatsApp *</label>
                    <input
                      type="tel"
                      v-model="form.customer_phone"
                      class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 focus:border-primary-700 focus:ring-primary-500 bg-white dark:bg-slate-800 text-gray-900 dark:text-white p-2"
                      :disabled="isSubmitting"
                      required
                      placeholder="Contoh: 081234567890"
                    />
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Nomor WhatsApp akan digunakan untuk konfirmasi pesanan</p>
                  </div>

                  <!-- Order Notes -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-white">Catatan Pesanan (Opsional)</label>
                    <textarea
                      v-model="form.notes"
                      rows="3"
                      class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 focus:border-primary-700 focus:ring-primary-500 bg-white dark:bg-slate-800 text-gray-900 dark:text-white p-2"
                      :disabled="isSubmitting"
                      placeholder="Contoh: Warna yang diinginkan, ukuran, dll"
                    ></textarea>
                  </div>

                  <!-- Order Items -->
                  <div>
                    <h3 class="text-md font-medium text-gray-900 mb-4 dark:text-white">Produk yang Dipesan</h3>
                    <div class="space-y-4">
                      <div v-for="item in cartItems" :key="item.id" class="flex items-center justify-between p-4 bg-gray-50 dark:bg-slate-800 rounded-lg">
                        <div class="flex items-center">
                          <img 
                            :src="`/storage/${item.product.featured_image}`" 
                            :alt="item.product.name"
                            class="h-16 w-16 object-cover rounded-md"
                          />
                          <div class="ml-4">
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white">{{ item.product.name }}</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-300">{{ formatPrice(item.product.price) }} x {{ item.quantity }}</p>
                          </div>
                        </div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ formatPrice(item.product.price * item.quantity) }}</p>
                      </div>
                    </div>
                  </div>

                  <!-- Submit Button -->
                  <button 
                    type="submit"
                    class="w-full bg-primary-600 text-white px-4 py-2 rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                    :disabled="isSubmitting"
                  >
                    <span v-if="!isSubmitting">Proses Pesanan</span>
                    <span v-else class="flex items-center justify-center">
                      <div class="h-4 w-4 animate-spin rounded-full border-2 border-current border-t-transparent mr-2"></div>
                      Memproses...
                    </span>
                  </button>
                </div>
              </form>
            </div>
          </div>

          <!-- Order Summary -->
          <div class="lg:col-span-1">
            <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm p-6">
              <h2 class="text-lg font-medium text-gray-900 mb-4 dark:text-white">Ringkasan Pesanan</h2>
              
              <!-- Summary Items -->
              <div class="space-y-3 mb-6">
                <div class="flex justify-between">
                  <p class="text-gray-600 dark:text-gray-300">Subtotal ({{ summary.itemCount }} item)</p>
                  <p class="font-medium dark:text-white">{{ formatPrice(summary.subtotal) }}</p>
                </div>
                <div class="flex justify-between">
                  <p class="text-gray-600 dark:text-gray-300">Biaya Admin</p>
                  <p class="font-medium dark:text-white">{{ formatPrice(summary.adminFee) }}</p>
                </div>
                <div class="flex justify-between">
                  <p class="text-gray-600 dark:text-gray-300">Diskon</p>
                  <p class="font-medium text-green-600 dark:text-green-400">-{{ formatPrice(summary.discount) }}</p>
                </div>
              </div>
              
              <!-- Total -->
              <div class="flex justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                <p class="text-lg font-medium text-gray-900 dark:text-white">Total</p>
                <p class="text-lg font-bold text-primary-600 dark:text-primary-400">{{ formatPrice(summary.total) }}</p>
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
import { computed, ref, onMounted } from 'vue';
import { toast } from 'vue-sonner';

const props = defineProps({
  cartItems: {
    type: Array,
    required: true,
    validator: (value) => value.length > 0
  },
  summary: {
    type: Object,
    required: true,
    validator: (value) => {
      return value && 
        typeof value.subtotal === 'number' && 
        typeof value.total === 'number' &&
        typeof value.itemCount === 'number';
    }
  },
  user: {
    type: Object,
    default: null
  }
});

// State
const isSubmitting = ref(false);
const form = ref({
  notes: '',
  customer_name: props.user?.name || '',
  customer_phone: props.user?.whatsapp || '',
  customer_email: props.user?.email || ''
});

// Validasi form
const validateForm = () => {
  // Debug: Log validasi
  console.log('Validating form:', {
    cartItems: props.cartItems,
    summary: props.summary,
    user: props.user,
    form: form.value
  });

  if (!props.cartItems || props.cartItems.length === 0) {
    console.log('Cart is empty');
    router.visit(route('cart.index'));
    return false;
  }

  if (!props.summary || !props.summary.total) {
    console.log('Invalid summary');
    toast.error('Terjadi kesalahan saat menghitung total belanja');
    return false;
  }

  // Validasi data customer
  if (!form.value.customer_name) {
    console.log('Name missing');
    toast.error('Silakan masukkan nama lengkap Anda');
    return false;
  }

  if (!form.value.customer_email) {
    console.log('Email missing');
    toast.error('Silakan masukkan email Anda');
    return false;
  }

  // Validasi format email
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailRegex.test(form.value.customer_email)) {
    console.log('Invalid email format');
    toast.error('Format email tidak valid');
    return false;
  }

  // Validasi nomor WhatsApp
  if (!form.value.customer_phone) {
    console.log('Phone number missing');
    toast.error('Silakan masukkan nomor WhatsApp Anda');
    return false;
  }

  // Bersihkan format nomor telepon sebelum validasi
  const cleanedPhone = cleanPhoneNumber(form.value.customer_phone);
  
  // Validasi format nomor WhatsApp
  const phoneRegex = /^(0|62|\+62)8[1-9][0-9]{8,11}$/;
  if (!phoneRegex.test(cleanedPhone)) {
    console.log('Invalid phone number format:', cleanedPhone);
    toast.error('Format nomor WhatsApp tidak valid. Gunakan format: 08xx, 62xx, atau +62xx');
    return false;
  }

  return true;
};

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

// Fungsi untuk membersihkan format nomor WhatsApp
const cleanPhoneNumber = (phone) => {
  if (!phone) return '';
  // Hapus semua karakter non-digit
  return phone.replace(/[^\d]/g, '');
};

const submitOrder = async () => {
  if (isSubmitting.value) return;
  
  // Debug: Log data yang akan dikirim
  console.log('=== DEBUG CHECKOUT ===');
  console.log('1. Form Data:', form.value);
  console.log('2. Cart Items:', props.cartItems);
  console.log('3. Summary:', props.summary);
  console.log('4. User:', props.user);
  console.log('5. Route URL:', route('orders.store'));
  
  // Bersihkan format nomor telepon
  form.value.customer_phone = cleanPhoneNumber(form.value.customer_phone);
  
  // Validasi form
  if (!validateForm()) return;
  
  try {
    isSubmitting.value = true;
    
    // Debug: Log request URL dan headers
    console.log('6. Sending request to:', route('orders.store'));
    
    // Gunakan Inertia form untuk menangani CSRF token secara otomatis
    const response = await router.post(route('orders.store'), form.value, {
      preserveScroll: true,
      preserveState: true,
      onBefore: () => {
        // Debug: Log sebelum request
        console.log('7. Before request - CSRF Token:', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'));
      },
      onSuccess: (page) => {
        // Debug: Log response sukses
        console.log('8. Success Response:', page);
        console.log('9. Flash Message:', page.props?.flash);
        
        // Cek flash message untuk data order
        if (page.props?.flash?.order) {
          console.log('10. Redirecting to thank you page with order:', page.props.flash.order);
          router.visit(route('orders.thankyou', { order: page.props.flash.order.id }));
        } else {
          console.error('11. Order data not found in flash message:', page.props?.flash);
          toast.error('Terjadi kesalahan saat memproses pesanan');
        }
      },
      onError: (errors) => {
        // Debug: Log error detail
        console.error('12. Error Response:', errors);
        console.error('13. Error Details:', {
          message: errors.message,
          errors: errors.errors,
          status: errors.status
        });
        
        if (errors.notes) {
          toast.error(errors.notes);
        } else if (errors.cart) {
          toast.error('Terjadi kesalahan pada keranjang belanja');
        } else if (errors.message === 'Sesi Anda telah berakhir. Silakan coba lagi.') {
          // Refresh halaman jika sesi berakhir
          window.location.reload();
        } else {
          toast.error('Gagal membuat pesanan. Silakan coba lagi.');
        }
      }
    });
    
  } catch (error) {
    // Debug: Log error sistem
    console.error('14. System Error:', error);
    console.error('15. Error Stack:', error.stack);
    console.error('16. Error Response:', error.response?.data);
    console.error('17. Error Status:', error.response?.status);
    
    toast.error('Terjadi kesalahan sistem. Silakan coba lagi.');
  } finally {
    isSubmitting.value = false;
  }
};

// Lifecycle hooks
onMounted(() => {
  // Debug: Log mounted
  console.log('Component mounted:', {
    cartItems: props.cartItems,
    summary: props.summary,
    user: props.user
  });

  // Set form data dari user jika sudah login
  form.value = {
    notes: '',
    customer_name: props.user?.name || '',
    customer_phone: props.user?.whatsapp || '',
    customer_email: props.user?.email || ''
  };
});
</script> 