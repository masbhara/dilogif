<template>
  <Head title="Konfirmasi Pembayaran" />
  
  <AppLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
          Konfirmasi Pembayaran
        </h2>
      </div>
    </template>

    <div class="py-6">
      <div class="mx-auto space-y-6 max-w-7xl sm:px-6 lg:px-8">
        <!-- List Konfirmasi Pembayaran -->
        <Card>
          <CardHeader>
            <CardTitle>Daftar Konfirmasi Pembayaran</CardTitle>
            <CardDescription>
              Daftar konfirmasi pembayaran dari pelanggan yang memerlukan verifikasi.
            </CardDescription>
            <div class="flex items-center space-x-2 pt-2">
              <Button variant="outline" size="sm" @click="refreshData">
                <RefreshCcw class="h-4 w-4 mr-2" />
                Refresh
              </Button>
            </div>
          </CardHeader>
          
          <CardContent>
            <!-- Table -->
            <AdminTable 
              :columns="confirmationColumns" 
              :data="confirmations" 
              :loading="processing"
              emptyMessage="Belum ada data konfirmasi pembayaran"
            >
              <TableRow 
                v-for="confirmation in confirmations.data" 
                :key="confirmation.id"
                class="hover:bg-secondary-100/50 dark:hover:bg-slate-900/90"
              >
                <TableCell class="text-center py-3.5 px-6 align-middle">
                  {{ confirmation.id }}
                </TableCell>
                <TableCell class="py-3.5 px-6 align-middle">
                  <Link 
                    :href="route('admin.orders.show', confirmation.payment.order.id)"
                    class="text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300 font-medium"
                  >
                    {{ confirmation.payment.order.order_number }}
                  </Link>
                </TableCell>
                <TableCell class="py-3.5 px-6 align-middle">
                  <div class="flex flex-col">
                    <span class="font-medium">{{ confirmation.user.name }}</span>
                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ confirmation.user.email }}</span>
                  </div>
                </TableCell>
                <TableCell class="py-3.5 px-6 align-middle">
                  <div class="flex flex-col">
                    <span class="font-medium">{{ confirmation.bank_name }}</span>
                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ confirmation.account_name }}</span>
                  </div>
                </TableCell>
                <TableCell class="py-3.5 px-6 align-middle">
                  {{ formatPrice(confirmation.amount) }}
                </TableCell>
                <TableCell class="py-3.5 px-6 align-middle">
                  {{ formatDate(confirmation.transfer_date) }}
                </TableCell>
                <TableCell class="py-3.5 px-6 align-middle">
                  <Badge 
                    variant="outline" 
                    :class="{
                      'border-yellow-400 text-yellow-600 bg-yellow-50 dark:bg-yellow-900/20 dark:text-yellow-400': confirmation.status === 'pending',
                      'border-green-400 text-green-600 bg-green-50 dark:bg-green-900/20 dark:text-green-400': confirmation.status === 'verified',
                      'border-red-400 text-red-600 bg-red-50 dark:bg-red-900/20 dark:text-red-400': confirmation.status === 'rejected',
                    }"
                  >
                    {{ getStatusLabel(confirmation.status) }}
                  </Badge>
                </TableCell>
                <TableCell class="text-right py-3.5 px-6 align-middle">
                  <div class="flex justify-end">
                    <DropdownMenu>
                      <DropdownMenuTrigger asChild>
                        <Button variant="ghost" size="icon">
                          <MoreHorizontal class="h-4 w-4" />
                        </Button>
                      </DropdownMenuTrigger>
                      <DropdownMenuContent align="end">
                        <DropdownMenuItem @click="viewConfirmation(confirmation)">
                          <Eye class="h-4 w-4 mr-2" />
                          Detail
                        </DropdownMenuItem>
                        <DropdownMenuSeparator />
                        <DropdownMenuItem 
                          v-if="confirmation.status === 'pending'"
                          @click="verifyConfirmation(confirmation)"
                          class="text-green-600 dark:text-green-400"
                        >
                          <CheckCircle class="h-4 w-4 mr-2" />
                          Verifikasi
                        </DropdownMenuItem>
                        <DropdownMenuItem 
                          v-if="confirmation.status === 'pending'"
                          @click="rejectConfirmation(confirmation)"
                          class="text-red-600 dark:text-red-400"
                        >
                          <XCircle class="h-4 w-4 mr-2" />
                          Tolak
                        </DropdownMenuItem>
                      </DropdownMenuContent>
                    </DropdownMenu>
                  </div>
                </TableCell>
              </TableRow>
            </AdminTable>
          </CardContent>
        </Card>
        
        <!-- Pesanan yang Memerlukan Pembayaran -->
        <Card>
          <CardHeader>
            <CardTitle>Pesanan yang Memerlukan Pembayaran</CardTitle>
            <CardDescription>
              Daftar pesanan yang belum melakukan konfirmasi pembayaran.
            </CardDescription>
          </CardHeader>
          
          <CardContent>
            <AdminTable
              :columns="orderColumns"
              :data="{ data: pendingOrders, links: [] }"
              :loading="processing"
              emptyMessage="Tidak ada pesanan yang memerlukan pembayaran"
            >
              <TableRow 
                v-for="order in pendingOrders" 
                :key="order.id"
                class="hover:bg-secondary-100/50 dark:hover:bg-slate-900/90"
              >
                <TableCell class="py-3.5 px-6 align-middle">
                  <Link 
                    :href="route('admin.orders.show', order.id)"
                    class="text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300 font-medium"
                  >
                    {{ order.order_number }}
                  </Link>
                </TableCell>
                <TableCell class="py-3.5 px-6 align-middle">
                  <div class="flex flex-col">
                    <span class="font-medium">{{ order.user?.name }}</span>
                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ order.user?.email }}</span>
                  </div>
                </TableCell>
                <TableCell class="py-3.5 px-6 align-middle">{{ formatPrice(order.total_amount) }}</TableCell>
                <TableCell class="py-3.5 px-6 align-middle">{{ order.payment?.payment_method?.name || '-' }}</TableCell>
                <TableCell class="py-3.5 px-6 align-middle">
                  <Badge 
                    variant="outline" 
                    :class="{
                      'border-yellow-400 text-yellow-600 bg-yellow-50 dark:bg-yellow-900/20 dark:text-yellow-400': order.payment?.status === 'pending',
                      'border-green-400 text-green-600 bg-green-50 dark:bg-green-900/20 dark:text-green-400': order.payment?.status === 'completed',
                      'border-red-400 text-red-600 bg-red-50 dark:bg-red-900/20 dark:text-red-400': order.payment?.status === 'failed',
                    }"
                  >
                    {{ getPaymentStatusLabel(order.payment?.status) }}
                  </Badge>
                </TableCell>
                <TableCell class="py-3.5 px-6 align-middle">{{ formatDate(order.created_at) }}</TableCell>
              </TableRow>
            </AdminTable>
          </CardContent>
        </Card>
      </div>
    </div>
    
    <!-- Dialog Verifikasi -->
    <Dialog :open="showVerifyDialog" @update:open="showVerifyDialog = $event">
      <DialogContent>
        <DialogHeader>
          <DialogTitle>Verifikasi Konfirmasi Pembayaran</DialogTitle>
          <DialogDescription>
            Pastikan Anda telah memeriksa bukti pembayaran dan detail konfirmasi dengan benar.
          </DialogDescription>
        </DialogHeader>
        <div class="space-y-4">
          <div v-if="selectedConfirmation">
            <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-4 mb-4">
              <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                  <p class="text-gray-500 dark:text-gray-400">Nomor Pesanan:</p>
                  <p class="font-medium">{{ selectedConfirmation.payment?.order?.order_number }}</p>
                </div>
                <div>
                  <p class="text-gray-500 dark:text-gray-400">Total Pesanan:</p>
                  <p class="font-medium">{{ formatPrice(selectedConfirmation.payment?.order?.total_amount) }}</p>
                </div>
                <div>
                  <p class="text-gray-500 dark:text-gray-400">Bank Pengirim:</p>
                  <p class="font-medium">{{ selectedConfirmation.bank_name }}</p>
                </div>
                <div>
                  <p class="text-gray-500 dark:text-gray-400">Jumlah Transfer:</p>
                  <p class="font-medium">{{ formatPrice(selectedConfirmation.amount) }}</p>
                </div>
              </div>
            </div>
            
            <div>
              <Label for="admin_notes">Catatan Admin (opsional)</Label>
              <Textarea 
                id="admin_notes" 
                v-model="adminNotes" 
                placeholder="Masukkan catatan untuk customer (jika diperlukan)" 
                class="mt-1"
              />
            </div>
          </div>
        </div>
        <DialogFooter>
          <Button variant="outline" @click="showVerifyDialog = false">Batal</Button>
          <Button @click="updateStatus('verified')" :loading="processing" :disabled="processing">
            <CheckCircle class="h-4 w-4 mr-2" />
            Verifikasi
          </Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>
    
    <!-- Dialog Penolakan -->
    <Dialog :open="showRejectDialog" @update:open="showRejectDialog = $event">
      <DialogContent>
        <DialogHeader>
          <DialogTitle>Tolak Konfirmasi Pembayaran</DialogTitle>
          <DialogDescription>
            Berikan alasan penolakan untuk konfirmasi pembayaran ini.
          </DialogDescription>
        </DialogHeader>
        <div class="space-y-4">
          <div v-if="selectedConfirmation">
            <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-4 mb-4">
              <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                  <p class="text-gray-500 dark:text-gray-400">Nomor Pesanan:</p>
                  <p class="font-medium">{{ selectedConfirmation.payment?.order?.order_number }}</p>
                </div>
                <div>
                  <p class="text-gray-500 dark:text-gray-400">Total Pesanan:</p>
                  <p class="font-medium">{{ formatPrice(selectedConfirmation.payment?.order?.total_amount) }}</p>
                </div>
                <div>
                  <p class="text-gray-500 dark:text-gray-400">Bank Pengirim:</p>
                  <p class="font-medium">{{ selectedConfirmation.bank_name }}</p>
                </div>
                <div>
                  <p class="text-gray-500 dark:text-gray-400">Jumlah Transfer:</p>
                  <p class="font-medium">{{ formatPrice(selectedConfirmation.amount) }}</p>
                </div>
              </div>
            </div>
            
            <div>
              <Label for="reject_reason">Alasan Penolakan <span class="text-red-500">*</span></Label>
              <Textarea 
                id="reject_reason" 
                v-model="adminNotes" 
                placeholder="Masukkan alasan penolakan konfirmasi pembayaran" 
                class="mt-1"
                required
              />
              <p v-if="!adminNotes && showNoteError" class="text-sm text-red-500 mt-1">
                Alasan penolakan harus diisi
              </p>
            </div>
          </div>
        </div>
        <DialogFooter>
          <Button variant="outline" @click="showRejectDialog = false">Batal</Button>
          <Button @click="updateStatus('rejected')" variant="destructive" :loading="processing" :disabled="processing">
            <XCircle class="h-4 w-4 mr-2" />
            Tolak
          </Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>
    
    <!-- Dialog Detail -->
    <Dialog :open="showDetailDialog" @update:open="showDetailDialog = $event">
      <DialogContent class="sm:max-w-3xl">
        <DialogHeader>
          <DialogTitle>Detail Konfirmasi Pembayaran</DialogTitle>
        </DialogHeader>
        <div v-if="selectedConfirmation" class="space-y-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3">Detail Pesanan</h3>
              <div class="space-y-3 bg-gray-50 dark:bg-gray-900/50 rounded-lg p-4">
                <div class="flex justify-between text-sm">
                  <span class="text-gray-500 dark:text-gray-400">Nomor Pesanan:</span>
                  <span class="font-medium">{{ selectedConfirmation.payment?.order?.order_number }}</span>
                </div>
                <div class="flex justify-between text-sm">
                  <span class="text-gray-500 dark:text-gray-400">Status Pesanan:</span>
                  <Badge 
                    variant="outline" 
                    :class="{
                      'border-yellow-400 text-yellow-600 bg-yellow-50 dark:bg-yellow-900/20 dark:text-yellow-400': selectedConfirmation.payment?.order?.status === 'pending',
                      'border-blue-400 text-blue-600 bg-blue-50 dark:bg-blue-900/20 dark:text-blue-400': selectedConfirmation.payment?.order?.status === 'processing',
                      'border-purple-400 text-purple-600 bg-purple-50 dark:bg-purple-900/20 dark:text-purple-400': selectedConfirmation.payment?.order?.status === 'review',
                      'border-green-400 text-green-600 bg-green-50 dark:bg-green-900/20 dark:text-green-400': selectedConfirmation.payment?.order?.status === 'completed',
                      'border-red-400 text-red-600 bg-red-50 dark:bg-red-900/20 dark:text-red-400': selectedConfirmation.payment?.order?.status === 'cancelled',
                    }"
                  >
                    {{ getOrderStatusLabel(selectedConfirmation.payment?.order?.status) }}
                  </Badge>
                </div>
                <div class="flex justify-between text-sm">
                  <span class="text-gray-500 dark:text-gray-400">Status Pembayaran:</span>
                  <Badge 
                    variant="outline" 
                    :class="{
                      'border-yellow-400 text-yellow-600 bg-yellow-50 dark:bg-yellow-900/20 dark:text-yellow-400': selectedConfirmation.payment?.status === 'pending',
                      'border-green-400 text-green-600 bg-green-50 dark:bg-green-900/20 dark:text-green-400': selectedConfirmation.payment?.status === 'completed',
                      'border-red-400 text-red-600 bg-red-50 dark:bg-red-900/20 dark:text-red-400': selectedConfirmation.payment?.status === 'failed',
                    }"
                  >
                    {{ getPaymentStatusLabel(selectedConfirmation.payment?.status) }}
                  </Badge>
                </div>
                <div class="flex justify-between text-sm">
                  <span class="text-gray-500 dark:text-gray-400">Total Pesanan:</span>
                  <span class="font-medium">{{ formatPrice(selectedConfirmation.payment?.order?.total_amount) }}</span>
                </div>
                <div class="flex justify-between text-sm">
                  <span class="text-gray-500 dark:text-gray-400">Tanggal Pesanan:</span>
                  <span class="font-medium">{{ formatDate(selectedConfirmation.payment?.order?.created_at) }}</span>
                </div>
              </div>
            </div>
            
            <div>
              <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3">Detail Konfirmasi</h3>
              <div class="space-y-3 bg-gray-50 dark:bg-gray-900/50 rounded-lg p-4">
                <div class="flex justify-between text-sm">
                  <span class="text-gray-500 dark:text-gray-400">Bank Pengirim:</span>
                  <span class="font-medium">{{ selectedConfirmation.bank_name }}</span>
                </div>
                <div class="flex justify-between text-sm">
                  <span class="text-gray-500 dark:text-gray-400">Nama Rekening:</span>
                  <span class="font-medium">{{ selectedConfirmation.account_name }}</span>
                </div>
                <div class="flex justify-between text-sm">
                  <span class="text-gray-500 dark:text-gray-400">Nomor Rekening:</span>
                  <span class="font-medium">{{ selectedConfirmation.account_number }}</span>
                </div>
                <div class="flex justify-between text-sm">
                  <span class="text-gray-500 dark:text-gray-400">Jumlah Transfer:</span>
                  <span class="font-medium">{{ formatPrice(selectedConfirmation.amount) }}</span>
                </div>
                <div class="flex justify-between text-sm">
                  <span class="text-gray-500 dark:text-gray-400">Tanggal Transfer:</span>
                  <span class="font-medium">{{ formatDate(selectedConfirmation.transfer_date) }}</span>
                </div>
                <div class="flex justify-between text-sm">
                  <span class="text-gray-500 dark:text-gray-400">Status Konfirmasi:</span>
                  <Badge 
                    variant="outline" 
                    :class="{
                      'border-yellow-400 text-yellow-600 bg-yellow-50 dark:bg-yellow-900/20 dark:text-yellow-400': selectedConfirmation.status === 'pending',
                      'border-green-400 text-green-600 bg-green-50 dark:bg-green-900/20 dark:text-green-400': selectedConfirmation.status === 'verified',
                      'border-red-400 text-red-600 bg-red-50 dark:bg-red-900/20 dark:text-red-400': selectedConfirmation.status === 'rejected',
                    }"
                  >
                    {{ getStatusLabel(selectedConfirmation.status) }}
                  </Badge>
                </div>
              </div>
            </div>
          </div>
          
          <div>
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3">Bukti Transfer</h3>
            <div class="flex justify-center bg-gray-50 dark:bg-gray-900/50 rounded-lg p-4">
              <img 
                v-if="selectedConfirmation.proof_image" 
                :src="`/storage/${selectedConfirmation.proof_image}`" 
                class="max-h-96 rounded-lg shadow-sm"
                alt="Bukti Transfer"
              />
              <p v-else class="py-8 text-center text-gray-500 dark:text-gray-400">
                Tidak ada bukti transfer yang diunggah
              </p>
            </div>
          </div>
          
          <div v-if="selectedConfirmation.notes || selectedConfirmation.admin_notes">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3">Catatan</h3>
            <div class="space-y-4">
              <div v-if="selectedConfirmation.notes" class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-4">
                <h4 class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Catatan Customer:</h4>
                <p class="text-sm">{{ selectedConfirmation.notes }}</p>
              </div>
              <div v-if="selectedConfirmation.admin_notes" class="bg-orange-50 dark:bg-orange-900/20 rounded-lg p-4">
                <h4 class="text-xs font-medium text-orange-700 dark:text-orange-300 mb-1">Catatan Admin:</h4>
                <p class="text-sm text-orange-600 dark:text-orange-400">{{ selectedConfirmation.admin_notes }}</p>
              </div>
            </div>
          </div>
          
          <div class="pt-4 flex justify-end space-x-3">
            <Button variant="outline" @click="showDetailDialog = false">Tutup</Button>
            <Button 
              v-if="selectedConfirmation.status === 'pending'" 
              @click="verifyConfirmation(selectedConfirmation); showDetailDialog = false"
            >
              <CheckCircle class="h-4 w-4 mr-2" />
              Verifikasi
            </Button>
            <Button 
              v-if="selectedConfirmation.status === 'pending'" 
              variant="destructive" 
              @click="rejectConfirmation(selectedConfirmation); showDetailDialog = false"
            >
              <XCircle class="h-4 w-4 mr-2" />
              Tolak
            </Button>
          </div>
        </div>
      </DialogContent>
    </Dialog>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Textarea } from '@/components/ui/textarea';
import { Label } from '@/components/ui/label';
import { Card, CardHeader, CardTitle, CardDescription, CardContent } from '@/components/ui/card';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { TableRow, TableCell } from '@/components/ui/table';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuSeparator, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import Pagination from '@/components/Pagination.vue';
import AdminTable from '@/components/AdminTable.vue';
import { toast } from 'vue-sonner';
import { 
  MoreHorizontal, Eye, CheckCircle, XCircle, RefreshCcw,
} from 'lucide-vue-next';

const props = defineProps({
  confirmations: Object,
  pendingOrders: Array,
});

// State
const showVerifyDialog = ref(false);
const showRejectDialog = ref(false);
const showDetailDialog = ref(false);
const selectedConfirmation = ref(null);
const adminNotes = ref('');
const processing = ref(false);
const showNoteError = ref(false);

// Columns for AdminTable
const confirmationColumns = [
  { label: '#', headerClass: 'w-16 text-center' },
  { label: 'Nomor Pesanan' },
  { label: 'Pelanggan' },
  { label: 'Bank' },
  { label: 'Jumlah' },
  { label: 'Tanggal Transfer' },
  { label: 'Status' },
  { label: 'Aksi', headerClass: 'w-24 text-right' }
];

const orderColumns = [
  { label: 'Nomor Pesanan' },
  { label: 'Pelanggan' },
  { label: 'Total' },
  { label: 'Metode Pembayaran' },
  { label: 'Status' },
  { label: 'Tanggal' }
];

// Methods
const formatPrice = (price) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0,
  }).format(price || 0);
};

const formatDate = (date) => {
  if (!date) return '';
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
    'rejected': 'Ditolak',
  };
  
  return statusMap[status] || status;
};

const getOrderStatusLabel = (status) => {
  const statusMap = {
    'pending': 'Menunggu Konfirmasi',
    'processing': 'Sedang Diproses',
    'review': 'Review',
    'completed': 'Selesai',
    'cancelled': 'Dibatalkan',
  };
  
  return statusMap[status] || status;
};

const getPaymentStatusLabel = (status) => {
  const statusMap = {
    'pending': 'Menunggu Pembayaran',
    'completed': 'Pembayaran Diterima',
    'failed': 'Pembayaran Gagal',
  };
  
  return statusMap[status] || status;
};

const viewConfirmation = (confirmation) => {
  selectedConfirmation.value = confirmation;
  showDetailDialog.value = true;
};

const verifyConfirmation = (confirmation) => {
  selectedConfirmation.value = confirmation;
  adminNotes.value = '';
  showVerifyDialog.value = true;
};

const rejectConfirmation = (confirmation) => {
  selectedConfirmation.value = confirmation;
  adminNotes.value = '';
  showNoteError.value = false;
  showRejectDialog.value = true;
};

const updateStatus = (status) => {
  if (status === 'rejected' && !adminNotes.value) {
    showNoteError.value = true;
    return;
  }
  
  processing.value = true;
  
  router.post(route('admin.payment-confirmations.update-status', selectedConfirmation.value.id), {
    status: status,
    admin_notes: adminNotes.value,
  }, {
    preserveScroll: true,
    onSuccess: () => {
      toast.success('Berhasil', {
        description: status === 'verified' 
          ? 'Konfirmasi pembayaran berhasil diverifikasi' 
          : 'Konfirmasi pembayaran berhasil ditolak',
      });
      showVerifyDialog.value = false;
      showRejectDialog.value = false;
    },
    onError: (errors) => {
      console.error('Error:', errors);
      toast.error('Gagal', {
        description: 'Terjadi kesalahan saat memperbarui status konfirmasi',
      });
    },
    onFinish: () => {
      processing.value = false;
    }
  });
};

const refreshData = () => {
  router.reload({
    preserveScroll: true,
    onStart: () => {
      toast.info('Memuat', {
        description: 'Memuat ulang data konfirmasi pembayaran',
      });
    },
    onSuccess: () => {
      toast.success('Berhasil', {
        description: 'Data konfirmasi pembayaran berhasil dimuat ulang',
      });
    },
    onError: () => {
      toast.error('Gagal', {
        description: 'Terjadi kesalahan saat memuat ulang data',
      });
    },
  });
};
</script> 