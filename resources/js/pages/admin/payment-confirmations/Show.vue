<template>
  <Head title="Detail Konfirmasi Pembayaran" />
  
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="container mx-auto py-6 px-4 md:px-6">
      <!-- Header dengan judul dan tombol aksi -->
      <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
        <h1 class="text-2xl font-bold tracking-tight">Detail Konfirmasi Pembayaran</h1>
        <div class="flex flex-col sm:flex-row items-center gap-2">
          <Link :href="route('admin.payment-confirmations.index')" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
            <Button variant="outline" size="sm" class="h-9">
              <ArrowLeftIcon class="h-4 w-4 mr-2" />
              Kembali ke Daftar Konfirmasi
            </Button>
          </Link>

          <div v-if="confirmation?.status === 'pending'" class="flex items-center gap-2">
            <Button 
              variant="destructive" 
              size="sm" 
              class="h-9"
              @click="showRejectDialog = true"
            >
              <XIcon class="h-4 w-4 mr-2" />
              Tolak
            </Button>
            
            <Button 
              variant="primary" 
              size="sm" 
              class="h-9"
              @click="showVerifyDialog = true"
            >
              <CheckIcon class="h-4 w-4 mr-2" />
              Verifikasi
            </Button>
          </div>
        </div>
      </div>

      <!-- Tampilkan pesan jika tidak ada data konfirmasi -->
      <div v-if="!confirmation" class="flex flex-col items-center justify-center py-12">
        <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-6 text-center max-w-md mx-auto">
          <h2 class="text-xl font-semibold text-yellow-700 dark:text-yellow-400 mb-2">Data tidak tersedia</h2>
          <p class="text-yellow-600 dark:text-yellow-300 mb-4">
            Data konfirmasi pembayaran tidak ditemukan atau masih dimuat.
          </p>
        </div>
      </div>

      <!-- Konten utama jika data konfirmasi tersedia -->
      <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Kolom Kiri: Info Pesanan dan Pembeli -->
        <div class="lg:col-span-2 space-y-6">
          <!-- Order Info -->
          <Card>
            <CardHeader>
              <CardTitle>Informasi Pesanan</CardTitle>
              <CardDescription>Detail pesanan yang terkait dengan konfirmasi pembayaran ini</CardDescription>
            </CardHeader>
            <CardContent>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Nomor Pesanan</h4>
                  <p class="text-base font-semibold">{{ confirmation?.order?.order_number || '-' }}</p>
                </div>
                <div>
                  <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Status Pesanan</h4>
                  <Badge 
                    variant="outline" 
                    :class="{
                      'border-yellow-400 text-yellow-600 bg-yellow-50 dark:bg-yellow-900/30 dark:text-yellow-300 dark:border-yellow-700': confirmation?.order?.status === 'pending',
                      'border-blue-400 text-blue-600 bg-blue-50 dark:bg-blue-900/30 dark:text-blue-300 dark:border-blue-700': confirmation?.order?.status === 'processing',
                      'border-green-400 text-green-600 bg-green-50 dark:bg-green-900/30 dark:text-green-300 dark:border-green-700': confirmation?.order?.status === 'completed',
                      'border-red-400 text-red-600 bg-red-50 dark:bg-red-900/30 dark:text-red-300 dark:border-red-700': confirmation?.order?.status === 'cancelled'
                    }"
                  >
                    {{ getOrderStatusLabel(confirmation?.order?.status) }}
                  </Badge>
                </div>
                <div>
                  <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total</h4>
                  <p class="text-base font-semibold">{{ formatPrice(confirmation?.order?.total_amount || 0) }}</p>
                </div>
                <div>
                  <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Tanggal Pesanan</h4>
                  <p class="text-base">{{ formatDate(confirmation?.order?.created_at) }}</p>
                </div>
                <div>
                  <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Metode Pembayaran</h4>
                  <p class="text-base">{{ confirmation?.order?.payment?.payment_method?.name || '-' }}</p>
                </div>
                <div>
                  <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Status Pembayaran</h4>
                  <Badge 
                    variant="outline" 
                    :class="{
                      'border-yellow-400 text-yellow-600 bg-yellow-50 dark:bg-yellow-900/30 dark:text-yellow-300 dark:border-yellow-700': confirmation?.order?.payment?.status === 'pending',
                      'border-green-400 text-green-600 bg-green-50 dark:bg-green-900/30 dark:text-green-300 dark:border-green-700': confirmation?.order?.payment?.status === 'paid',
                      'border-red-400 text-red-600 bg-red-50 dark:bg-red-900/30 dark:text-red-300 dark:border-red-700': confirmation?.order?.payment?.status === 'failed'
                    }"
                  >
                    {{ getPaymentStatusLabel(confirmation?.order?.payment?.status) }}
                  </Badge>
                </div>
              </div>
            </CardContent>
          </Card>

          <!-- Customer Info -->
          <Card>
            <CardHeader>
              <CardTitle>Informasi Pelanggan</CardTitle>
            </CardHeader>
            <CardContent>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Nama</h4>
                  <p class="text-base font-semibold">{{ confirmation?.order?.user?.name || '-' }}</p>
                </div>
                <div>
                  <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Email</h4>
                  <p class="text-base">{{ confirmation?.order?.user?.email || '-' }}</p>
                </div>
                <div>
                  <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Telepon</h4>
                  <p class="text-base">{{ confirmation?.order?.user?.phone || '-' }}</p>
                </div>
                <div>
                  <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Alamat</h4>
                  <p class="text-base">{{ confirmation?.order?.shipping_address || '-' }}</p>
                </div>
              </div>
            </CardContent>
          </Card>
        </div>

        <!-- Kolom Kanan: Info Konfirmasi Pembayaran -->
        <div class="space-y-6">
          <!-- Confirmation Info -->
          <Card>
            <CardHeader>
              <CardTitle>Detail Konfirmasi</CardTitle>
            </CardHeader>
            <CardContent>
              <div class="space-y-4">
                <div>
                  <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Status</h4>
                  <Badge 
                    variant="outline" 
                    :class="{
                      'border-yellow-400 text-yellow-600 bg-yellow-50 dark:bg-yellow-900/30 dark:text-yellow-300 dark:border-yellow-700': confirmation.status === 'pending',
                      'border-green-400 text-green-600 bg-green-50 dark:bg-green-900/30 dark:text-green-300 dark:border-green-700': confirmation.status === 'verified',
                      'border-red-400 text-red-600 bg-red-50 dark:bg-red-900/30 dark:text-red-300 dark:border-red-700': confirmation.status === 'rejected'
                    }"
                  >
                    {{ getConfirmationStatusLabel(confirmation.status) }}
                  </Badge>
                </div>
                <div>
                  <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Bank</h4>
                  <p class="text-base font-semibold">{{ confirmation?.bank_name || '-' }}</p>
                </div>
                <div>
                  <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Nomor Rekening</h4>
                  <p class="text-base">{{ confirmation?.account_number || '-' }}</p>
                </div>
                <div>
                  <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Nama Pemilik Rekening</h4>
                  <p class="text-base">{{ confirmation?.account_name || '-' }}</p>
                </div>
                <div>
                  <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Jumlah Transfer</h4>
                  <p class="text-base font-semibold">{{ formatPrice(confirmation?.amount || 0) }}</p>
                </div>
                <div>
                  <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Tanggal Transfer</h4>
                  <p class="text-base">{{ formatDate(confirmation?.transfer_date || '') }}</p>
                </div>
                <div>
                  <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Catatan</h4>
                  <p class="text-base">{{ confirmation?.notes || '-' }}</p>
                </div>
                <div v-if="confirmation?.admin_notes">
                  <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Catatan Admin</h4>
                  <p class="text-base">{{ confirmation?.admin_notes }}</p>
                </div>
                <div v-if="confirmation?.rejection_reason">
                  <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Alasan Penolakan</h4>
                  <p class="text-base text-red-600 dark:text-red-400">{{ confirmation?.rejection_reason }}</p>
                </div>
              </div>
            </CardContent>
          </Card>

          <!-- Bukti Transfer -->
          <Card>
            <CardHeader>
              <CardTitle>Bukti Transfer</CardTitle>
            </CardHeader>
            <CardContent>
              <div v-if="confirmation?.proof_image" class="rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                <a :href="`/storage/${confirmation?.proof_image}`" target="_blank" class="block">
                  <img 
                    :src="`/storage/${confirmation?.proof_image}`" 
                    alt="Bukti Transfer" 
                    class="w-full h-auto object-cover"
                  />
                </a>
              </div>
              <div v-else class="py-10 text-center text-gray-500 border border-dashed rounded-lg">
                Tidak ada bukti transfer yang diunggah
              </div>
              <div v-if="confirmation?.proof_image" class="mt-4 text-center">
                <a 
                  :href="`/storage/${confirmation?.proof_image}`" 
                  target="_blank" 
                  class="inline-flex"
                >
                  <Button variant="outline" size="sm">
                    <ExternalLinkIcon class="h-4 w-4 mr-2" />
                    Lihat Gambar Asli
                  </Button>
                </a>
              </div>
              
              <!-- Debug info (hapus saat sudah berfungsi dengan baik) -->
              <div class="mt-4 p-2 bg-gray-100 dark:bg-gray-800 rounded-md text-xs">
                <p><strong>Debug Info:</strong></p>
                <p>Proof Image: {{ confirmation?.proof_image || 'Tidak ada' }}</p>
                <p>URL: {{ confirmation?.proof_image ? `/storage/${confirmation?.proof_image}` : '-' }}</p>
              </div>
            </CardContent>
          </Card>
        </div>
      </div>
    </div>

    <!-- Dialog Verifikasi Pembayaran -->
    <Dialog :open="showVerifyDialog" @update:open="showVerifyDialog = $event">
      <DialogContent>
        <DialogHeader>
          <DialogTitle>Verifikasi Konfirmasi Pembayaran</DialogTitle>
          <DialogDescription>
            Pastikan informasi pembayaran sudah sesuai sebelum memverifikasi konfirmasi pembayaran
          </DialogDescription>
        </DialogHeader>
        
        <div class="grid gap-4 py-4">
          <Alert>
            <AlertCircleIcon class="h-4 w-4" />
            <AlertTitle>Penting</AlertTitle>
            <AlertDescription>
              Tindakan ini akan mengubah status pembayaran menjadi "Dibayar" dan status pesanan menjadi "Sedang Diproses"
            </AlertDescription>
          </Alert>
          
          <div class="grid grid-cols-2 gap-4">
            <div>
              <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Nomor Pesanan</h4>
              <p class="font-medium">{{ confirmation?.order?.order_number || '-' }}</p>
            </div>
            <div>
              <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pesanan</h4>
              <p class="font-medium">{{ formatPrice(confirmation?.order?.total_amount || 0) }}</p>
            </div>
            <div>
              <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Jumlah Transfer</h4>
              <p class="font-medium">{{ formatPrice(confirmation?.amount || 0) }}</p>
            </div>
            <div>
              <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Transfer</h4>
              <p>{{ formatDate(confirmation?.transfer_date) }}</p>
            </div>
          </div>
          
          <div>
            <Label for="admin_notes">Catatan Admin (Opsional)</Label>
            <Textarea 
              id="admin_notes" 
              v-model="verifyForm.admin_notes" 
              placeholder="Masukkan catatan admin jika diperlukan"
              class="mt-1"
            />
          </div>
        </div>
        
        <DialogFooter>
          <Button 
            variant="outline" 
            @click="showVerifyDialog = false"
          >
            Batal
          </Button>
          <Button 
            variant="primary"
            :disabled="verifyProcessing"
            @click="verifyConfirmation"
          >
            <Loader2Icon v-if="verifyProcessing" class="mr-2 h-4 w-4 animate-spin" />
            Verifikasi Pembayaran
          </Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>

    <!-- Dialog Penolakan Pembayaran -->
    <Dialog :open="showRejectDialog" @update:open="showRejectDialog = $event">
      <DialogContent>
        <DialogHeader>
          <DialogTitle>Tolak Konfirmasi Pembayaran</DialogTitle>
          <DialogDescription>
            Harap berikan alasan penolakan agar pelanggan dapat mengirimkan konfirmasi pembayaran yang benar
          </DialogDescription>
        </DialogHeader>
        
        <div class="grid gap-4 py-4">
          <Alert variant="destructive">
            <AlertCircleIcon class="h-4 w-4" />
            <AlertTitle>Perhatian</AlertTitle>
            <AlertDescription>
              Tindakan ini akan menolak konfirmasi pembayaran dan pelanggan perlu mengirimkan konfirmasi pembayaran baru
            </AlertDescription>
          </Alert>
          
          <div>
            <Label for="rejection_reason" class="required">Alasan Penolakan</Label>
            <Textarea 
              id="rejection_reason" 
              v-model="rejectForm.rejection_reason" 
              placeholder="Masukkan alasan penolakan"
              class="mt-1"
              required
            />
            <div v-if="rejectErrors.rejection_reason" class="text-sm text-red-500 mt-1">
              {{ rejectErrors.rejection_reason }}
            </div>
          </div>
          
          <div>
            <Label for="admin_notes">Catatan Admin (Opsional)</Label>
            <Textarea 
              id="admin_notes" 
              v-model="rejectForm.admin_notes" 
              placeholder="Masukkan catatan admin jika diperlukan"
              class="mt-1"
            />
          </div>
        </div>
        
        <DialogFooter>
          <Button 
            variant="outline" 
            @click="showRejectDialog = false"
          >
            Batal
          </Button>
          <Button 
            variant="destructive"
            :disabled="rejectProcessing"
            @click="rejectConfirmation"
          >
            <Loader2Icon v-if="rejectProcessing" class="mr-2 h-4 w-4 animate-spin" />
            Tolak Pembayaran
          </Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>
  </AppLayout>
</template>

<script setup>
import { ref, reactive } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { toast } from 'vue-sonner';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { 
  ArrowLeftIcon, 
  CheckIcon, 
  XIcon, 
  ExternalLinkIcon,
  AlertCircleIcon,
  Loader2Icon
} from 'lucide-vue-next';
import { 
  Card, 
  CardHeader, 
  CardTitle, 
  CardDescription, 
  CardContent 
} from '@/components/ui/card';
import { Dialog, DialogContent, DialogHeader, DialogFooter, DialogTitle, DialogDescription } from '@/components/ui/dialog';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Alert, AlertTitle, AlertDescription } from '@/components/ui/alert';

const props = defineProps({
  confirmation: Object,
});

// Data untuk dialog
const showVerifyDialog = ref(false);
const showRejectDialog = ref(false);
const verifyProcessing = ref(false);
const rejectProcessing = ref(false);
const verifyForm = reactive({
  admin_notes: ''
});
const rejectForm = reactive({
  rejection_reason: '',
  admin_notes: ''
});
const rejectErrors = reactive({});

// Breadcrumbs
const breadcrumbs = [
  {
    title: 'Admin',
    href: route('admin.dashboard'),
  },
  {
    title: 'Konfirmasi Pembayaran',
    href: route('admin.payment-confirmations.index'),
  },
  {
    title: 'Detail',
    href: route('admin.payment-confirmations.show', props.confirmation?.id),
  }
];

// Format harga (Rupiah)
const formatPrice = (price) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0
  }).format(price);
};

// Format tanggal
const formatDate = (date) => {
  return new Date(date).toLocaleString('id-ID', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
};

// Label status pesanan
const getOrderStatusLabel = (status) => {
  const statusMap = {
    'pending': 'Menunggu Konfirmasi',
    'processing': 'Sedang Diproses',
    'completed': 'Selesai',
    'cancelled': 'Dibatalkan'
  };
  
  return statusMap[status] || status;
};

// Label status pembayaran
const getPaymentStatusLabel = (status) => {
  const statusMap = {
    'pending': 'Menunggu Pembayaran',
    'paid': 'Dibayar',
    'failed': 'Gagal'
  };
  
  return statusMap[status] || status;
};

// Label status konfirmasi
const getConfirmationStatusLabel = (status) => {
  const statusMap = {
    'pending': 'Menunggu Verifikasi',
    'verified': 'Terverifikasi',
    'rejected': 'Ditolak'
  };
  
  return statusMap[status] || status;
};

// Verifikasi pembayaran
const verifyConfirmation = () => {
  verifyProcessing.value = true;
  
  router.post(route('admin.payment-confirmations.verify', props.confirmation?.id), verifyForm, {
    onSuccess: () => {
      showVerifyDialog.value = false;
      verifyProcessing.value = false;
      toast.success("Berhasil!", {
        description: "Konfirmasi pembayaran telah diverifikasi."
      });
    },
    onError: (errors) => {
      verifyProcessing.value = false;
      toast.error("Gagal!", {
        description: "Terjadi kesalahan saat memverifikasi pembayaran."
      });
    }
  });
};

// Tolak pembayaran
const rejectConfirmation = () => {
  rejectProcessing.value = true;
  rejectErrors.rejection_reason = '';
  
  router.post(route('admin.payment-confirmations.reject', props.confirmation?.id), rejectForm, {
    onSuccess: () => {
      showRejectDialog.value = false;
      rejectProcessing.value = false;
      toast.success("Berhasil!", {
        description: "Konfirmasi pembayaran telah ditolak."
      });
    },
    onError: (errors) => {
      rejectProcessing.value = false;
      
      if (errors.rejection_reason) {
        rejectErrors.rejection_reason = errors.rejection_reason;
      }
      
      toast.error("Gagal!", {
        description: "Terjadi kesalahan saat menolak pembayaran."
      });
    }
  });
};
</script> 