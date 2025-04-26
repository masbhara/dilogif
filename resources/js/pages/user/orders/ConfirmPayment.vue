<template>
  <Head title="Konfirmasi Pembayaran" />
  
  <AppLayout :breadcrumbs="breadcrumbItems">
    <div class="flex h-full flex-1 flex-col gap-4 p-4 md:p-6">
      <!-- Header dengan judul -->
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h1 class="text-2xl font-bold text-secondary-900 dark:text-white">Konfirmasi Pembayaran cok</h1>
      </div>

      <div v-if="!order" class="flex items-center justify-center py-12">
        <Loader2Icon class="h-6 w-6 animate-spin text-primary-500 mr-2" />
        <p class="text-slate-500 dark:text-slate-400">Memuat data pesanan...</p>
      </div>
      
      <div v-else class="space-y-6">
        <!-- Detail Pesanan -->
        <Card class="border border-slate-200 dark:border-slate-700">
          <CardHeader>
            <CardTitle>Detail Pesanan</CardTitle>
          </CardHeader>
          <CardContent>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <h4 class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Nomor Pesanan</h4>
                <p class="text-base font-semibold">{{ order.order_number }}</p>
              </div>
              <div>
                <h4 class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Status</h4>
                <Badge 
                  variant="outline" 
                  :class="{
                    'border-yellow-400 text-yellow-600 bg-yellow-50 dark:bg-yellow-900/30 dark:text-yellow-300 dark:border-yellow-700': order.status === 'pending',
                    'border-blue-400 text-blue-600 bg-blue-50 dark:bg-blue-900/30 dark:text-blue-300 dark:border-blue-700': order.status === 'processing',
                    'border-green-400 text-green-600 bg-green-50 dark:bg-green-900/30 dark:text-green-300 dark:border-green-700': order.status === 'completed',
                    'border-red-400 text-red-600 bg-red-50 dark:bg-red-900/30 dark:text-red-300 dark:border-red-700': order.status === 'cancelled'
                  }"
                >
                  {{ order.status_label }}
                </Badge>
              </div>
              <div>
                <h4 class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Tanggal</h4>
                <p class="text-base">{{ formatDate(order.created_at) }}</p>
              </div>
              <div>
                <h4 class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Total</h4>
                <p class="text-base font-semibold">{{ formatPrice(order.total_amount) }}</p>
              </div>
              <div>
                <h4 class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Status Pembayaran</h4>
                <Badge 
                  variant="outline" 
                  :class="{
                    'border-yellow-400 text-yellow-600 bg-yellow-50 dark:bg-yellow-900/30 dark:text-yellow-300 dark:border-yellow-700': order.payment?.status === 'pending',
                    'border-green-400 text-green-600 bg-green-50 dark:bg-green-900/30 dark:text-green-300 dark:border-green-700': order.payment?.status === 'completed',
                    'border-red-400 text-red-600 bg-red-50 dark:bg-red-900/30 dark:text-red-300 dark:border-red-700': order.payment?.status === 'failed'
                  }"
                >
                  {{ order.payment?.status ? getPaymentStatusLabel(order.payment.status) : 'Menunggu Pembayaran' }}
                </Badge>
              </div>
            </div>
          </CardContent>
        </Card>
        
        <!-- Informasi Rekening -->
        <Card class="border border-slate-200 dark:border-slate-700">
          <CardHeader>
            <CardTitle>Metode Pembayaran</CardTitle>
            <CardDescription>Silakan transfer ke salah satu rekening di bawah ini</CardDescription>
          </CardHeader>
          <CardContent>
            <div class="space-y-4">
              <!-- BCA -->
              <div class="border rounded-md p-4 bg-slate-50 dark:bg-slate-800/50">
                <div class="flex items-center justify-between mb-3">
                  <div class="flex items-center">
                    <span class="font-semibold text-blue-700 dark:text-blue-400">Bank BCA</span>
                  </div>
                  <Button 
                    @click="copyToClipboard('1234567890')" 
                    variant="ghost"
                    size="sm"
                  >
                    <ClipboardCopyIcon class="h-4 w-4 mr-2" />
                    Salin
                  </Button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                  <div>
                    <h4 class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Nomor Rekening</h4>
                    <p class="text-base font-semibold">1234567890</p>
                  </div>
                  <div>
                    <h4 class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Atas Nama</h4>
                    <p class="text-base font-semibold">PT Dilogif Indonesia</p>
                  </div>
                </div>
              </div>
              
              <!-- Mandiri -->
              <div class="border rounded-md p-4 bg-slate-50 dark:bg-slate-800/50">
                <div class="flex items-center justify-between mb-3">
                  <div class="flex items-center">
                    <span class="font-semibold text-blue-700 dark:text-blue-400">Bank Mandiri</span>
                  </div>
                  <Button 
                    @click="copyToClipboard('9876543210')" 
                    variant="ghost"
                    size="sm"
                  >
                    <ClipboardCopyIcon class="h-4 w-4 mr-2" />
                    Salin
                  </Button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                  <div>
                    <h4 class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Nomor Rekening</h4>
                    <p class="text-base font-semibold">9876543210</p>
                  </div>
                  <div>
                    <h4 class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Atas Nama</h4>
                    <p class="text-base font-semibold">PT Dilogif Indonesia</p>
                  </div>
                </div>
              </div>
              
              <!-- BNI -->
              <div class="border rounded-md p-4 bg-slate-50 dark:bg-slate-800/50">
                <div class="flex items-center justify-between mb-3">
                  <div class="flex items-center">
                    <span class="font-semibold text-blue-700 dark:text-blue-400">Bank BNI</span>
                  </div>
                  <Button 
                    @click="copyToClipboard('5678901234')" 
                    variant="ghost"
                    size="sm"
                  >
                    <ClipboardCopyIcon class="h-4 w-4 mr-2" />
                    Salin
                  </Button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                  <div>
                    <h4 class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Nomor Rekening</h4>
                    <p class="text-base font-semibold">5678901234</p>
                  </div>
                  <div>
                    <h4 class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Atas Nama</h4>
                    <p class="text-base font-semibold">PT Dilogif Indonesia</p>
                  </div>
                </div>
              </div>
              
              <!-- Tambahan Instruksi -->
              <Alert>
                <InfoIcon class="h-4 w-4" />
                <AlertTitle>Petunjuk Transfer</AlertTitle>
                <AlertDescription>
                  <ul class="list-disc pl-4 space-y-1">
                    <li>Silakan transfer tepat sampai 3 digit terakhir untuk memudahkan verifikasi</li>
                    <li>Konfirmasi pembayaran setelah Anda melakukan transfer</li>
                  </ul>
                </AlertDescription>
              </Alert>
            </div>
          </CardContent>
        </Card>
        
        <!-- Konfirmasi Pembayaran Sebelumnya -->
        <Card v-if="isValidLastConfirmation" class="border border-slate-200 dark:border-slate-700">
          <CardHeader>
            <CardTitle>Konfirmasi Pembayaran Terakhir</CardTitle>
          </CardHeader>
          <CardContent>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <h4 class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Bank</h4>
                <p class="text-base font-semibold">{{ lastConfirmation.bank_name }}</p>
              </div>
              <div>
                <h4 class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Atas Nama</h4>
                <p class="text-base">{{ lastConfirmation.account_name || '-' }}</p>
              </div>
              <div>
                <h4 class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Jumlah</h4>
                <p class="text-base font-semibold">{{ lastConfirmation.amount ? formatPrice(lastConfirmation.amount) : '-' }}</p>
              </div>
              <div>
                <h4 class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Status</h4>
                <Badge 
                  variant="outline" 
                  :class="{
                    'border-yellow-400 text-yellow-600 bg-yellow-50 dark:bg-yellow-900/30 dark:text-yellow-300 dark:border-yellow-700': lastConfirmation.status === 'pending',
                    'border-green-400 text-green-600 bg-green-50 dark:bg-green-900/30 dark:text-green-300 dark:border-green-700': lastConfirmation.status === 'verified',
                    'border-red-400 text-red-600 bg-red-50 dark:bg-red-900/30 dark:text-red-300 dark:border-red-700': lastConfirmation.status === 'rejected'
                  }"
                >
                  {{ lastConfirmation.status ? getStatusLabel(lastConfirmation.status) : '-' }}
                </Badge>
              </div>
            </div>
            
            <div v-if="lastConfirmation.proof_image" class="mt-6">
              <h4 class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-2">Bukti Transfer:</h4>
              <div class="rounded-lg overflow-hidden border border-slate-200 dark:border-slate-700">
                <img 
                  :src="`/storage/${lastConfirmation.proof_image}`" 
                  alt="Bukti Transfer" 
                  class="max-h-60 w-auto object-contain mx-auto"
                />
              </div>
            </div>
          </CardContent>
        </Card>
        
        <!-- Form Konfirmasi Pembayaran -->
        <Card v-if="!isValidLastConfirmation || lastConfirmation?.status === 'rejected'" class="border border-slate-200 dark:border-slate-700">
          <CardHeader>
            <CardTitle>Konfirmasi Pembayaran</CardTitle>
            <CardDescription>Silakan isi form di bawah ini setelah Anda melakukan transfer</CardDescription>
          </CardHeader>
          <CardContent>
            <form @submit.prevent="submitForm" class="space-y-4">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-2">
                  <Label for="bank_name">Bank Pengirim</Label>
                  <Input
                    id="bank_name"
                    v-model="form.bank_name"
                    type="text"
                    placeholder="Contoh: BCA, Mandiri, BNI, dll"
                    required
                  />
                </div>
                
                <div class="space-y-2">
                  <Label for="account_name">Nama Pemilik Rekening</Label>
                  <Input
                    id="account_name"
                    v-model="form.account_name"
                    type="text"
                    placeholder="Nama pemilik rekening pengirim"
                    required
                  />
                </div>
                
                <div class="space-y-2">
                  <Label for="account_number">Nomor Rekening</Label>
                  <Input
                    id="account_number"
                    v-model="form.account_number"
                    type="text"
                    placeholder="Nomor rekening pengirim"
                    required
                  />
                </div>
                
                <div class="space-y-2">
                  <Label for="amount">Jumlah Transfer</Label>
                  <Input
                    id="amount"
                    v-model="form.amount"
                    type="number"
                    step="0.01"
                    min="1"
                    :placeholder="`Min ${formatPrice(1)}`"
                    required
                  />
                  <p class="text-sm text-slate-500">
                    Total pesanan: {{ formatPrice(order.total_amount) }}
                  </p>
                </div>
                
                <div class="space-y-2">
                  <Label for="transfer_date">Tanggal Transfer</Label>
                  <Input
                    id="transfer_date"
                    v-model="form.transfer_date"
                    type="date"
                    :max="new Date().toISOString().substr(0, 10)"
                    required
                  />
                </div>
                
                <div class="space-y-2">
                  <Label for="notes">Catatan (opsional)</Label>
                  <Textarea
                    id="notes"
                    v-model="form.notes"
                    placeholder="Tulis catatan tambahan jika diperlukan"
                  />
                </div>
              </div>
              
              <div class="space-y-2 mt-4">
                <Label for="proof_image">Bukti Transfer</Label>
                <div class="mt-1 flex justify-center p-6 border-2 border-dashed border-slate-300 dark:border-slate-700 rounded-md">
                  <div class="space-y-1 text-center">
                    <div v-if="!imagePreview">
                      <UploadIcon class="mx-auto h-12 w-12 text-slate-400" />
                      <p class="text-sm text-slate-500 mt-2">
                        <span class="font-medium text-primary-600 hover:text-primary-500 focus:outline-none focus:underline transition duration-150 ease-in-out cursor-pointer" @click="$refs.fileInput.click()">Upload file</span>
                        atau drag and drop
                      </p>
                      <p class="text-xs text-slate-500 mt-1">PNG, JPG, GIF (MAX. 2MB)</p>
                    </div>
                    <img v-else :src="imagePreview" alt="Preview" class="mx-auto h-40 object-contain" />
                    <input ref="fileInput" type="file" class="hidden" accept="image/*" @change="handleFileChange" required />
                  </div>
                </div>
              </div>
              
              <div class="flex justify-end space-x-2 mt-6">
                <Button
                  type="button"
                  variant="outline"
                  @click="goBack"
                >
                  Batal
                </Button>
                <Button
                  type="submit"
                  :disabled="processing"
                >
                  <Loader2Icon v-if="processing" class="mr-2 h-4 w-4 animate-spin" />
                  {{ processing ? 'Mengirim...' : 'Kirim Konfirmasi' }}
                </Button>
              </div>
            </form>
          </CardContent>
        </Card>
      </div>
      
      <Toaster />
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { useToast } from "@/composables/useToast";
import { Toaster } from "@/components/ui/sonner";
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Badge } from '@/components/ui/badge';
import { 
  Card, 
  CardHeader, 
  CardTitle, 
  CardDescription, 
  CardContent 
} from '@/components/ui/card';
import { Alert, AlertTitle, AlertDescription } from '@/components/ui/alert';
import { 
  Loader2Icon, 
  ClipboardCopyIcon, 
  InfoIcon,
  UploadIcon
} from 'lucide-vue-next';

const props = defineProps({
  order: {
    type: Object,
    required: true,
    default: () => ({})
  },
  lastConfirmation: {
    type: [Object, null],
    default: null,
    validator: (value) => {
      if (value === null) return true;
      if (typeof value !== 'object') return false;
      if (!value.bank_name) return false;
      return true;
    }
  },
});

const { toast } = useToast();

// State
const imagePreview = ref(null);
const fileInput = ref(null);
const processing = ref(false);
const copiedText = ref('');

// Form 
const form = useForm({
  bank_name: '',
  account_name: '',
  account_number: '',
  amount: props.order?.total_amount || 0,
  transfer_date: new Date().toISOString().substr(0, 10),
  proof_image: null,
  notes: '',
});

// Computed property untuk mengecek validitas lastConfirmation
const isValidLastConfirmation = computed(() => {
  return props.lastConfirmation && 
         typeof props.lastConfirmation === 'object' && 
         props.lastConfirmation.bank_name;
});

// Computed
const breadcrumbItems = computed(() => [
  {
    title: 'Dashboard',
    href: route('dashboard'),
  },
  {
    title: 'Pesanan',
    href: route('orders.index'),
  },
  { 
    title: props.order?.order_number ? `Pesanan #${props.order.order_number}` : 'Detail Pesanan',
    href: props.order?.id ? route('orders.show', props.order.id) : '#'
  },
  {
    title: 'Konfirmasi Pembayaran',
    href: props.order?.id ? route('orders.payment.confirm', props.order.id) : '#',
  },
]);

// Methods
const formatPrice = (price) => {
  if (!price && price !== 0) return 'Rp 0';
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0,
  }).format(price);
};

const formatDate = (date) => {
  if (!date) return '-';
  return new Date(date).toLocaleDateString('id-ID', {
    day: 'numeric',
    month: 'long',
    year: 'numeric',
  });
};

const getStatusLabel = (status) => {
  const statusMap = {
    'pending': 'Menunggu Verifikasi',
    'verified': 'Terverifikasi',
    'rejected': 'Ditolak'
  };
  
  return status ? (statusMap[status] || status) : 'Tidak Diketahui';
};

const getPaymentStatusLabel = (status) => {
  const statusMap = {
    'pending': 'Menunggu Pembayaran',
    'completed': 'Pembayaran Diterima',
    'failed': 'Pembayaran Gagal'
  };
  
  return status ? (statusMap[status] || status) : 'Tidak Diketahui';
};

const copyToClipboard = (text) => {
  navigator.clipboard.writeText(text).then(() => {
    copiedText.value = text;
    toast({
      title: "Berhasil!",
      description: "Nomor rekening berhasil disalin",
      variant: "success"
    });
    
    setTimeout(() => {
      copiedText.value = '';
    }, 2000);
  }).catch(err => {
    console.error('Gagal menyalin teks: ', err);
    toast({
      title: "Gagal!",
      description: "Tidak dapat menyalin nomor rekening",
      variant: "destructive"
    });
  });
};

const handleFileChange = (e) => {
  const file = e.target.files[0];
  if (!file) return;
  
  // Validate file size (max 2MB)
  if (file.size > 2 * 1024 * 1024) {
    toast({
      title: "Ukuran file terlalu besar",
      description: "Maksimal ukuran file adalah 2MB",
      variant: "destructive"
    });
    if (fileInput.value) fileInput.value.value = '';
    return;
  }
  
  // Create image preview
  const reader = new FileReader();
  reader.onload = (e) => {
    imagePreview.value = e.target.result;
  };
  reader.readAsDataURL(file);
  
  form.proof_image = file;
};

const submitForm = () => {
  if (!props.order?.id) {
    toast({
      title: "Kesalahan",
      description: "ID pesanan tidak ditemukan",
      variant: "destructive"
    });
    return;
  }
  
  processing.value = true;
  form.post(route('orders.payment.confirm.store', props.order.id), {
    preserveScroll: true,
    onSuccess: () => {
      toast({
        title: "Berhasil!",
        description: "Konfirmasi pembayaran berhasil dikirim",
        variant: "success"
      });
      processing.value = false;
    },
    onError: (errors) => {
      toast({
        title: "Gagal!",
        description: "Terjadi kesalahan saat mengirim konfirmasi pembayaran",
        variant: "destructive"
      });
      console.error('Form errors:', errors);
      processing.value = false;
    },
  });
};

const goBack = () => {
  if (props.order?.id) {
    router.visit(route('orders.show', props.order.id));
  } else {
    router.visit(route('orders.index'));
  }
};
</script> 