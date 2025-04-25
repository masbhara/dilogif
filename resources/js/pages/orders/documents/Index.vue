<template>
  <Head :title="`Dokumen Pesanan #${order.order_number}`" />

  <AppLayout>
    <div class="container py-6">
      <div class="mb-6">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-50">
              Dokumen & Informasi Pesanan
            </h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">
              Pesanan #{{ order.order_number }}
            </p>
          </div>
          <Button variant="outline" as="a" :href="route('orders.show', order.id)">
            Kembali ke Detail Pesanan
          </Button>
        </div>
      </div>

      <!-- Kredensi Login -->
      <section class="mb-8" v-if="credentials && credentials.length > 0">
        <h2 class="text-xl font-semibold mb-4 flex items-center text-slate-900 dark:text-slate-50">
          <Key class="h-5 w-5 mr-2 text-blue-500" />
          Kredensial Login
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <Card v-for="(document, index) in credentials" :key="index" class="overflow-hidden">
            <CardHeader class="bg-blue-50 dark:bg-blue-950 pb-3">
              <div class="flex items-center justify-between">
                <CardTitle class="text-lg">{{ document.title }}</CardTitle>
                <Badge class="bg-blue-500">Kredensial</Badge>
              </div>
              <CardDescription v-if="document.expires_at">
                {{ document.expires_at ? getExpiryText(document.expires_at) : '' }}
              </CardDescription>
            </CardHeader>
            <CardContent class="pt-4">
              <pre class="text-xs md:text-sm bg-slate-50 dark:bg-slate-800 p-4 rounded-md overflow-x-auto whitespace-pre-wrap">{{ document.content }}</pre>
            </CardContent>
            <CardFooter class="flex justify-between border-t pt-4">
              <div class="text-xs text-slate-500">
                Ditambahkan: {{ formatDate(document.created_at) }}
              </div>
              <div v-if="document.file_path">
                <a 
                  :href="route('orders.documents.download', [order.id, document.id])" 
                  class="inline-flex items-center text-blue-600 dark:text-blue-400 text-sm"
                >
                  <Download class="h-4 w-4 mr-1" /> Unduh File
                </a>
              </div>
            </CardFooter>
          </Card>
        </div>
      </section>

      <!-- Informasi Domain -->
      <section class="mb-8" v-if="domainInfo && domainInfo.length > 0">
        <h2 class="text-xl font-semibold mb-4 flex items-center text-slate-900 dark:text-slate-50">
          <Globe class="h-5 w-5 mr-2 text-green-500" />
          Informasi Domain & Hosting
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <Card v-for="(document, index) in domainInfo" :key="index" class="overflow-hidden">
            <CardHeader class="bg-green-50 dark:bg-green-950 pb-3">
              <div class="flex items-center justify-between">
                <CardTitle class="text-lg">{{ document.title }}</CardTitle>
                <Badge class="bg-green-500">Domain</Badge>
              </div>
              <CardDescription v-if="document.expires_at" :class="{'text-danger-500': isExpired(document.expires_at)}">
                {{ document.expires_at ? getExpiryText(document.expires_at) : '' }}
              </CardDescription>
            </CardHeader>
            <CardContent class="pt-4">
              <pre class="text-xs md:text-sm bg-slate-50 dark:bg-slate-800 p-4 rounded-md overflow-x-auto whitespace-pre-wrap">{{ document.content }}</pre>
            </CardContent>
            <CardFooter class="flex justify-between border-t pt-4">
              <div class="text-xs text-slate-500">
                Ditambahkan: {{ formatDate(document.created_at) }}
              </div>
              <div v-if="document.file_path">
                <a 
                  :href="route('orders.documents.download', [order.id, document.id])" 
                  class="inline-flex items-center text-blue-600 dark:text-blue-400 text-sm"
                >
                  <Download class="h-4 w-4 mr-1" /> Unduh File
                </a>
              </div>
            </CardFooter>
          </Card>
        </div>
      </section>

      <!-- Pembaruan -->
      <section class="mb-8" v-if="updates && updates.length > 0">
        <h2 class="text-xl font-semibold mb-4 flex items-center text-slate-900 dark:text-slate-50">
          <RefreshCw class="h-5 w-5 mr-2 text-orange-500" />
          Pembaruan & Informasi
        </h2>
        <div class="grid grid-cols-1 gap-4">
          <Card v-for="(document, index) in updates" :key="index" class="overflow-hidden">
            <CardHeader class="bg-orange-50 dark:bg-orange-950 pb-3">
              <div class="flex items-center justify-between">
                <CardTitle class="text-lg">{{ document.title }}</CardTitle>
                <Badge class="bg-orange-500">Pembaruan</Badge>
              </div>
            </CardHeader>
            <CardContent class="pt-4">
              <div class="prose dark:prose-invert max-w-none">
                <pre class="text-xs md:text-sm bg-slate-50 dark:bg-slate-800 p-4 rounded-md overflow-x-auto whitespace-pre-wrap">{{ document.content }}</pre>
              </div>
            </CardContent>
            <CardFooter class="flex justify-between border-t pt-4">
              <div class="text-xs text-slate-500">
                Ditambahkan: {{ formatDate(document.created_at) }}
              </div>
              <div v-if="document.file_path">
                <a 
                  :href="route('orders.documents.download', [order.id, document.id])" 
                  class="inline-flex items-center text-blue-600 dark:text-blue-400 text-sm"
                >
                  <Download class="h-4 w-4 mr-1" /> Unduh File
                </a>
              </div>
            </CardFooter>
          </Card>
        </div>
      </section>

      <!-- File Unduhan -->
      <section class="mb-8" v-if="downloads && downloads.length > 0">
        <h2 class="text-xl font-semibold mb-4 flex items-center text-slate-900 dark:text-slate-50">
          <Download class="h-5 w-5 mr-2 text-purple-500" />
          File Unduhan
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <Card v-for="(document, index) in downloads" :key="index" class="overflow-hidden">
            <CardHeader class="bg-purple-50 dark:bg-purple-950 pb-3">
              <div class="flex items-center justify-between">
                <CardTitle class="text-lg">{{ document.title }}</CardTitle>
                <Badge class="bg-purple-500">Unduhan</Badge>
              </div>
            </CardHeader>
            <CardContent class="pt-4">
              <pre class="text-xs md:text-sm bg-slate-50 dark:bg-slate-800 p-4 rounded-md overflow-x-auto whitespace-pre-wrap">{{ document.content }}</pre>
            </CardContent>
            <CardFooter class="flex justify-between border-t pt-4">
              <div class="text-xs text-slate-500">
                Ditambahkan: {{ formatDate(document.created_at) }}
              </div>
              <div v-if="document.file_path">
                <a 
                  :href="route('orders.documents.download', [order.id, document.id])" 
                  class="inline-flex items-center text-blue-600 dark:text-blue-400 text-sm font-medium"
                >
                  <Download class="h-4 w-4 mr-1" /> Unduh File
                </a>
              </div>
            </CardFooter>
          </Card>
        </div>
      </section>

      <!-- Tidak Ada Dokumen -->
      <div v-if="!hasDocuments" class="py-12 text-center">
        <div class="mx-auto h-12 w-12 text-slate-300">
          <File class="h-12 w-12" />
        </div>
        <h3 class="mt-2 text-lg font-semibold text-slate-900 dark:text-slate-50">Tidak ada dokumen</h3>
        <p class="mt-1 text-slate-500 dark:text-slate-400">
          Saat ini tidak ada dokumen atau informasi tambahan yang tersedia untuk pesanan ini.
        </p>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { 
  Key, Globe, RefreshCw, Download, File
} from 'lucide-vue-next';

// Props yang diterima dari controller
const props = defineProps({
  order: Object,
  credentials: Array,
  domainInfo: Array,
  updates: Array,
  downloads: Array,
});

// Periksa apakah ada dokumen
const hasDocuments = computed(() => {
  return (
    (props.credentials && props.credentials.length > 0) ||
    (props.domainInfo && props.domainInfo.length > 0) ||
    (props.updates && props.updates.length > 0) ||
    (props.downloads && props.downloads.length > 0)
  );
});

// Format tanggal
const formatDate = (dateString) => {
  if (!dateString) return '-';
  
  const date = new Date(dateString);
  return new Intl.DateTimeFormat('id-ID', { 
    day: 'numeric', 
    month: 'long', 
    year: 'numeric'
  }).format(date);
};

// Cek apakah tanggal sudah expired
const isExpired = (dateString) => {
  if (!dateString) return false;
  
  const expiryDate = new Date(dateString);
  return expiryDate < new Date();
};

// Teks untuk tanggal kedaluwarsa
const getExpiryText = (dateString) => {
  if (!dateString) return '';
  
  const expiryDate = new Date(dateString);
  const now = new Date();
  
  if (isExpired(dateString)) {
    return `Kedaluwarsa pada ${formatDate(dateString)}`;
  }
  
  const diffTime = Math.abs(expiryDate - now);
  const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
  
  if (diffDays <= 30) {
    return `Kedaluwarsa dalam ${diffDays} hari (${formatDate(dateString)})`;
  }
  
  return `Masa aktif hingga ${formatDate(dateString)}`;
};
</script> 