<template>
  <Head title="Edit Dokumen" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 p-4 md:p-6">
      <!-- Header dengan judul dan tombol kembali -->
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h1 class="text-2xl font-bold text-secondary-900 dark:text-white">Edit Dokumen</h1>
        <div class="flex gap-2">
          <Link :href="route('admin.orders.documents.index', order.id)">
            <Button variant="outline" size="sm" class="h-9">
              <ArrowLeft class="h-4 w-4 mr-2" />
              Kembali
            </Button>
          </Link>
        </div>
      </div>

      <!-- Form Edit -->
      <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-secondary-200 dark:border-slate-700">
        <div class="p-6 border-b border-secondary-200 dark:border-slate-700">
          <h2 class="text-lg font-semibold text-secondary-900 dark:text-white">Informasi Dokumen</h2>
          <p class="text-secondary-500 dark:text-secondary-400 mt-1">Edit informasi dokumen dan konten di bawah ini.</p>
        </div>
        <form @submit.prevent="updateDocument" class="p-6">
          <div class="space-y-6">
            <!-- Tipe Dokumen -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-start">
              <Label for="type" class="text-right hidden md:block">Tipe Dokumen</Label>
              <div class="md:col-span-3 space-y-1">
                <Label for="type" class="md:hidden">Tipe Dokumen</Label>
                <Select v-model="form.type">
                  <SelectTrigger>
                    <SelectValue placeholder="Pilih tipe dokumen" />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem v-for="(label, value) in documentTypes" :key="value" :value="value">
                      {{ label }}
                    </SelectItem>
                  </SelectContent>
                </Select>
                <p v-if="errors.type" class="text-destructive text-sm">{{ errors.type }}</p>
              </div>
            </div>

            <!-- Judul -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-start">
              <Label for="title" class="text-right hidden md:block">Judul Dokumen</Label>
              <div class="md:col-span-3 space-y-1">
                <Label for="title" class="md:hidden">Judul Dokumen</Label>
                <Input id="title" v-model="form.title" />
                <p v-if="errors.title" class="text-destructive text-sm">{{ errors.title }}</p>
              </div>
            </div>

            <!-- Konten -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-start">
              <Label for="content" class="text-right hidden md:block">Konten</Label>
              <div class="md:col-span-3 space-y-1">
                <Label for="content" class="md:hidden">Konten</Label>
                <Textarea id="content" v-model="form.content" rows="8" />
                <p v-if="errors.content" class="text-destructive text-sm">{{ errors.content }}</p>
              </div>
            </div>

            <!-- File Upload -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-start">
              <Label for="file" class="text-right hidden md:block">File Lampiran</Label>
              <div class="md:col-span-3 space-y-1">
                <Label for="file" class="md:hidden">File Lampiran</Label>
                <Input id="file" type="file" @change="handleFileChange" />
                <p class="text-sm text-secondary-500 dark:text-secondary-400 mt-1">
                  File saat ini: 
                  <span v-if="document.file_path" class="font-medium">
                    {{ getFileName(document.file_path) }} ({{ formatFileSize(document.file_size) }})
                  </span>
                  <span v-else class="italic">Tidak ada file</span>
                </p>
                <p v-if="errors.file" class="text-destructive text-sm">{{ errors.file }}</p>
              </div>
            </div>

            <!-- Kirim Notifikasi -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-start">
              <div class="md:col-span-1 text-right hidden md:block"></div>
              <div class="md:col-span-3 flex items-center gap-2">
                <Checkbox id="should_notify" v-model="form.should_notify" />
                <Label for="should_notify">Kirim notifikasi ke pelanggan setelah diperbarui</Label>
              </div>
            </div>

            <!-- Tombol Submit -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-start pt-4 border-t border-secondary-200 dark:border-slate-700">
              <div class="md:col-span-1 text-right hidden md:block"></div>
              <div class="md:col-span-3 flex justify-start gap-2">
                <Button type="submit" disabled={isSubmitting}>
                  <Loader2 v-if="isSubmitting" class="mr-2 h-4 w-4 animate-spin" />
                  Simpan Perubahan
                </Button>
                <Link :href="route('admin.orders.documents.index', order.id)">
                  <Button type="button" variant="outline">
                    Batal
                  </Button>
                </Link>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Checkbox } from '@/components/ui/checkbox';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { ArrowLeft, Loader2 } from 'lucide-vue-next';
import { useToast } from '@/composables/useToast';

const props = defineProps({
  order: Object,
  document: Object,
  documentTypes: Object,
  errors: Object
});

// Breadcrumbs
const breadcrumbs = [
  { title: 'Dashboard', href: route('admin.dashboard') },
  { title: 'Orders', href: route('admin.orders.index') },
  { title: 'Order ' + props.order.order_number, href: route('admin.orders.show', props.order.id) },
  { title: 'Dokumen', href: route('admin.orders.documents.index', props.order.id) },
  { title: 'Edit ' + props.document.title },
];

// Form state
const form = useForm({
  type: props.document.type,
  title: props.document.title,
  content: props.document.content,
  expires_at: props.document.expires_at,
  file: null,
  should_notify: false
});

const isSubmitting = ref(false);
const { toast } = useToast();

// Format file size
const formatFileSize = (bytes) => {
  if (!bytes) return '-';
  const k = 1024;
  const sizes = ['Bytes', 'KB', 'MB', 'GB'];
  const i = Math.floor(Math.log(bytes) / Math.log(k));
  return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
};

// Get file name from path
const getFileName = (path) => {
  if (!path) return '';
  return path.split('/').pop();
};

// Handle file change
const handleFileChange = (e) => {
  if (e.target.files.length > 0) {
    form.file = e.target.files[0];
  }
};

// Update document
const updateDocument = () => {
  isSubmitting.value = true;
  
  form.post(route('admin.orders.documents.update', [props.order.id, props.document.id]), {
    _method: 'PUT',
    forceFormData: true,
    onSuccess: () => {
      router.visit(route('admin.orders.documents.show', [props.order.id, props.document.id]));
      toast({
        title: 'Dokumen diperbarui',
        description: 'Dokumen berhasil diperbarui',
      });
    },
    onError: (errors) => {
      toast({
        title: 'Gagal memperbarui dokumen',
        description: Object.values(errors).flat().join(' '),
        variant: 'destructive',
      });
    },
    onFinish: () => {
      isSubmitting.value = false;
    }
  });
};
</script> 