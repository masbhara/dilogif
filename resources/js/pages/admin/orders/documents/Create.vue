<template>
  <Head :title="`Tambah Dokumen - Pesanan #${order.order_number}`" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="container py-6">
      <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-50">
          Tambah Dokumen Pesanan
        </h1>
        <p class="text-sm text-slate-500 dark:text-slate-400">
          Tambahkan kredensial, informasi domain, update, atau file pesanan #{{ order.order_number }}
        </p>
      </div>

      <Card>
        <CardContent class="pt-6">
          <form @submit.prevent="submit">
            <div class="grid grid-cols-1 gap-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Pilih Jenis Dokumen -->
                <div class="space-y-2">
                  <Label for="type">Jenis Dokumen</Label>
                  <Select v-model="form.type" id="type" name="type">
                    <option value="" disabled>Pilih Jenis Dokumen</option>
                    <option v-for="(label, type) in documentTypes" :key="type" :value="type">
                      {{ label }}
                    </option>
                  </Select>
                  <div v-if="errors.type" class="text-sm text-danger-500">{{ errors.type }}</div>
                </div>

                <!-- Judul Dokumen -->
                <div class="space-y-2">
                  <Label for="title">Judul Dokumen</Label>
                  <Input v-model="form.title" id="title" name="title" placeholder="Masukkan judul dokumen" />
                  <div v-if="errors.title" class="text-sm text-danger-500">{{ errors.title }}</div>
                </div>
              </div>

              <!-- Isi Dokumen -->
              <div class="space-y-2">
                <Label for="content">Isi Dokumen</Label>
                <Textarea 
                  v-model="form.content" 
                  id="content" 
                  name="content" 
                  placeholder="Masukkan isi dokumen" 
                  :rows="8"
                />
                <p class="text-xs text-slate-500">
                  {{ getDocumentInstructions() }}
                </p>
                <div v-if="errors.content" class="text-sm text-danger-500">{{ errors.content }}</div>
              </div>

              <!-- Tanggal Kedaluwarsa -->
              <div class="space-y-2">
                <Label for="expires_at">Tanggal Kedaluwarsa (Opsional)</Label>
                <Input v-model="form.expires_at" id="expires_at" name="expires_at" type="date" />
                <p class="text-xs text-slate-500">Biarkan kosong jika dokumen tidak memiliki tanggal kedaluwarsa</p>
                <div v-if="errors.expires_at" class="text-sm text-danger-500">{{ errors.expires_at }}</div>
              </div>

              <!-- Upload File -->
              <div class="space-y-2">
                <Label for="file">Upload File (Opsional)</Label>
                <Input 
                  type="file"
                  id="file" 
                  name="file"
                  @input="form.file = $event.target.files[0]"
                />
                <p class="text-xs text-slate-500">Ukuran maksimum file: 10MB</p>
                <div v-if="errors.file" class="text-sm text-danger-500">{{ errors.file }}</div>
              </div>

              <!-- Kirim Notifikasi -->
              <div class="flex items-center space-x-2">
                <Checkbox v-model="form.should_notify" id="should_notify" />
                <Label for="should_notify">Kirim notifikasi ke pelanggan saat dokumen dibuat</Label>
              </div>

              <!-- Tombol Submit -->
              <div class="flex items-center justify-end space-x-2">
                <Button
                  variant="outline"
                  type="button"
                  @click="goBack"
                >
                  Kembali
                </Button>
                <Button
                  type="submit"
                  :disabled="form.processing"
                >
                  {{ form.processing ? 'Menyimpan...' : 'Simpan Dokumen' }}
                </Button>
              </div>
            </div>
          </form>
        </CardContent>
      </Card>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import { Select } from '@/components/ui/select';
import { Checkbox } from '@/components/ui/checkbox';

// Props yang diterima dari controller
const props = defineProps({
  order: Object,
  documentTypes: Object,
  errors: Object,
});

// Form untuk menambah dokumen baru
const form = useForm({
  type: '',
  title: '',
  content: '',
  expires_at: '',
  file: null,
  should_notify: true,
});

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
  {
    title: 'Tambah',
    href: route('admin.orders.documents.create', props.order.id),
  },
];

// Kembali ke halaman sebelumnya
const goBack = () => {
  window.history.back();
};

// Dapatkan instruksi berdasarkan tipe dokumen
const getDocumentInstructions = () => {
  if (!form.type) return 'Silakan pilih jenis dokumen terlebih dahulu';

  switch (form.type) {
    case 'credential':
      return 'Contoh format: Username: user123\nPassword: pass123\nURL: https://example.com/admin';
    case 'domain':
      return 'Contoh format: Domain: example.com\nServer: ns1.example.com, ns2.example.com\nTanggal Registrasi: 25 April 2023';
    case 'update':
      return 'Masukkan informasi pembaruan atau perubahan yang ingin disampaikan ke pelanggan';
    case 'download':
      return 'Masukkan instruksi unduhan atau link unduhan yang dapat diakses oleh pelanggan';
    default:
      return 'Masukkan isi dokumen dengan format yang sesuai';
  }
};

// Submit form untuk membuat dokumen baru
const submit = () => {
  form.post(route('admin.orders.documents.store', props.order.id), {
    onSuccess: () => {
      form.reset();
    },
    preserveScroll: true,
  });
};
</script> 