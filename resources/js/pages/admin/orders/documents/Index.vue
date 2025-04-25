<template>
  <Head :title="`Dokumen Pesanan #${order.order_number}`" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="container py-6">
      <div class="mb-6 flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-50">
            Dokumen Pesanan #{{ order.order_number }}
          </h1>
          <p class="text-sm text-slate-500 dark:text-slate-400">
            Kelola kredensial, informasi domain, dan file untuk pesanan
          </p>
        </div>
        <div>
          <Button as="a" :href="route('admin.orders.documents.create', order.id)">
            Tambah Dokumen
          </Button>
        </div>
      </div>

      <Card v-if="flashMessage" class="mb-6">
        <CardContent class="p-4 flex items-center" :class="{'bg-green-50 dark:bg-green-900/20': messageType === 'success', 'bg-red-50 dark:bg-red-900/20': messageType === 'error'}">
          <CheckCircle v-if="messageType === 'success'" class="h-5 w-5 text-green-500 dark:text-green-400 mr-2" />
          <XCircle v-else-if="messageType === 'error'" class="h-5 w-5 text-red-500 dark:text-red-400 mr-2" />
          <p :class="{'text-green-700 dark:text-green-300': messageType === 'success', 'text-red-700 dark:text-red-300': messageType === 'error'}">
            {{ flashMessage }}
          </p>
        </CardContent>
      </Card>

      <div class="grid grid-cols-1 gap-6">
        <!-- Informasi Pesanan -->
        <Card>
          <CardHeader>
            <CardTitle>Informasi Pesanan</CardTitle>
          </CardHeader>
          <CardContent>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Pelanggan</p>
                <p class="text-base font-medium">{{ order.user_id && order.user ? order.user.name : order.customer_name }}</p>
                <p class="text-sm text-slate-500 dark:text-slate-400">{{ order.customer_email }}</p>
                <p class="text-sm text-slate-500 dark:text-slate-400">{{ order.customer_phone }}</p>
              </div>
              <div>
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Tanggal Pesanan</p>
                <p class="text-base font-medium">{{ formatDate(order.created_at) }}</p>
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Status</p>
                <Badge :variant="getStatusVariant(order.status)">{{ order.status_label }}</Badge>
              </div>
            </div>
          </CardContent>
        </Card>

        <!-- Daftar Dokumen -->
        <Card>
          <CardHeader>
            <CardTitle>Dokumen & Kredensial</CardTitle>
            <CardDescription>Kelola semua dokumen terkait pesanan ini</CardDescription>
          </CardHeader>
          <CardContent>
            <div v-if="order.documents && order.documents.length > 0">
              <div class="rounded-md border">
                <Table>
                  <TableHeader>
                    <TableRow>
                      <TableHead>Judul</TableHead>
                      <TableHead>Jenis</TableHead>
                      <TableHead>Tanggal Dibuat</TableHead>
                      <TableHead>Kedaluwarsa</TableHead>
                      <TableHead>Status Pengiriman</TableHead>
                      <TableHead class="text-right">Aksi</TableHead>
                    </TableRow>
                  </TableHeader>
                  <TableBody>
                    <TableRow v-for="document in order.documents" :key="document.id">
                      <TableCell>
                        <div class="font-medium">{{ document.title }}</div>
                      </TableCell>
                      <TableCell>
                        <Badge :variant="getDocumentVariant(document.type)">{{ getDocumentTypeLabel(document.type) }}</Badge>
                      </TableCell>
                      <TableCell>{{ formatDate(document.created_at) }}</TableCell>
                      <TableCell>
                        <span v-if="document.expires_at" :class="{'text-danger-600': isExpired(document.expires_at)}">
                          {{ formatDate(document.expires_at) }}
                        </span>
                        <span v-else>-</span>
                      </TableCell>
                      <TableCell>
                        <Badge v-if="document.is_sent" variant="success">Terkirim</Badge>
                        <Badge v-else variant="secondary">Belum Dikirim</Badge>
                      </TableCell>
                      <TableCell class="text-right">
                        <div class="flex items-center justify-end gap-2">
                          <Button
                            v-if="document.file_path"
                            variant="outline"
                            size="sm"
                            :href="route('admin.orders.documents.download', [order.id, document.id])"
                            target="_blank"
                          >
                            <Download class="h-4 w-4 mr-1" />
                            Unduh
                          </Button>
                          <Button
                            v-if="!document.is_sent"
                            variant="outline"
                            size="sm"
                            @click="sendDocument(document)"
                          >
                            <Send class="h-4 w-4 mr-1" />
                            Kirim
                          </Button>
                          <Button
                            variant="outline"
                            size="sm"
                            :href="route('admin.orders.documents.edit', [order.id, document.id])"
                          >
                            <Edit class="h-4 w-4 mr-1" />
                            Edit
                          </Button>
                          <Button
                            variant="outline"
                            size="sm"
                            @click="confirmDelete(document)"
                          >
                            <Trash class="h-4 w-4 mr-1" />
                            Hapus
                          </Button>
                        </div>
                      </TableCell>
                    </TableRow>
                  </TableBody>
                </Table>
              </div>
            </div>
            <div v-else class="text-center py-8">
              <div class="mx-auto h-12 w-12 text-slate-300">
                <File class="h-12 w-12" />
              </div>
              <h3 class="mt-2 text-sm font-semibold text-slate-900 dark:text-slate-50">Tidak ada dokumen</h3>
              <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                Tambahkan dokumen, kredensial, atau file untuk pesanan ini
              </p>
              <div class="mt-6">
                <Button as="a" :href="route('admin.orders.documents.create', order.id)">
                  <Plus class="w-4 h-4 mr-2" />
                  Tambah Dokumen
                </Button>
              </div>
            </div>
          </CardContent>
        </Card>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { computed, ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Badge } from '@/components/ui/badge';
import { 
  File, Edit, Trash, Download, Send, 
  Plus, CheckCircle, XCircle 
} from 'lucide-vue-next';
import { useToast } from '@/composables/useToast';

// Props yang diterima dari controller
const props = defineProps({
  order: Object,
  documentTypes: Object,
  errors: Object,
  flash: Object,
});

// Toast untuk notifikasi
const { toast } = useToast();

// Form untuk menghapus dokumen
const deleteForm = useForm({});

// Breadcrumbs untuk navigasi
const breadcrumbs = [
  {
    title: 'Admin',
    href: route('admin.dashboard'),
  },
  {
    title: 'Pesanan',
    href: route('admin.orders.index'),
  },
  {
    title: `#${props.order.order_number}`,
    href: route('admin.orders.show', props.order.id),
  },
  {
    title: 'Dokumen',
    href: route('admin.orders.documents.index', props.order.id),
  },
];

// Flash message dari controller
const flashMessage = computed(() => props.flash?.message || props.flash?.error);
const messageType = computed(() => props.flash?.error ? 'error' : 'success');

// Format tanggal menjadi format lokal
const formatDate = (dateString) => {
  if (!dateString) return '-';
  
  const date = new Date(dateString);
  return new Intl.DateTimeFormat('id-ID', {
    day: 'numeric',
    month: 'long',
    year: 'numeric',
  }).format(date);
};

// Cek apakah tanggal sudah expired
const isExpired = (dateString) => {
  if (!dateString) return false;
  
  const expiryDate = new Date(dateString);
  return expiryDate < new Date();
};

// Dapatkan variant badge berdasarkan status pesanan
const getStatusVariant = (status) => {
  switch (status) {
    case 'pending': return 'warning';
    case 'processing': return 'info';
    case 'review': return 'purple';
    case 'completed': return 'success';
    case 'cancelled': return 'destructive';
    default: return 'secondary';
  }
};

// Dapatkan variant badge berdasarkan tipe dokumen
const getDocumentVariant = (type) => {
  switch (type) {
    case 'credential': return 'blue';
    case 'domain': return 'green';
    case 'update': return 'orange';
    case 'download': return 'purple';
    default: return 'secondary';
  }
};

// Dapatkan label tipe dokumen
const getDocumentTypeLabel = (type) => {
  return props.documentTypes[type] || type;
};

// Konfirmasi penghapusan dokumen
const confirmDelete = (document) => {
  if (confirm(`Apakah Anda yakin ingin menghapus dokumen "${document.title}"?`)) {
    deleteForm.delete(route('admin.orders.documents.destroy', [props.order.id, document.id]), {
      onSuccess: () => {
        toast({
          title: 'Dokumen dihapus',
          description: 'Dokumen berhasil dihapus',
        });
      },
    });
  }
};

// Kirim dokumen melalui email
const sendDocument = (document) => {
  if (confirm(`Kirim dokumen "${document.title}" ke pelanggan?`)) {
    const form = useForm({});
    form.post(route('admin.orders.documents.send', [props.order.id, document.id]), {
      onSuccess: () => {
        toast({
          title: 'Dokumen dikirim',
          description: 'Dokumen berhasil dikirim ke pelanggan',
        });
      },
      onError: () => {
        toast({
          title: 'Gagal mengirim',
          description: 'Terjadi kesalahan saat mengirim dokumen',
          variant: 'destructive',
        });
      },
    });
  }
};
</script> 