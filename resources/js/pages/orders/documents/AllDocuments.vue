<template>
  <Head title="Dokumen Saya" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 p-4 md:p-6">
      <!-- Header dengan judul dan statistik -->
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h1 class="text-2xl font-bold text-secondary-900 dark:text-white">Dokumen Saya</h1>
        <div class="flex items-center gap-2">
          <Badge variant="secondary" class="h-8 px-3 text-sm">
            {{ totalDocuments }} Dokumen
          </Badge>
          <Badge v-if="totalUnread > 0" variant="destructive" class="h-8 px-3 text-sm">
            {{ totalUnread }} Belum Dibaca
          </Badge>
        </div>
      </div>

      <!-- Dokumen Belum Dibaca -->
      <Card v-if="unreadDocuments && unreadDocuments.length > 0" class="mb-6">
        <CardHeader class="pb-3">
          <CardTitle class="flex items-center">
            <AlertTriangle class="h-5 w-5 text-red-500 mr-2" />
            Dokumen Belum Dibaca
          </CardTitle>
          <CardDescription>
            Dokumen-dokumen yang perlu Anda baca
          </CardDescription>
        </CardHeader>
        <CardContent>
          <div class="space-y-4">
            <div v-for="doc in unreadDocuments" :key="doc.id" class="p-4 bg-white dark:bg-slate-800 border border-red-100 dark:border-red-900/30 rounded-md">
              <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                  <div class="flex items-center space-x-2 mb-2">
                    <Badge :variant="getBadgeVariant(doc.type)">
                      {{ getDocumentTypeLabel(doc.type) }}
                    </Badge>
                    <Badge variant="destructive" size="sm">
                      Belum Dibaca
                    </Badge>
                  </div>
                  <h4 class="text-lg font-medium text-slate-900 dark:text-white mb-1">{{ doc.title }}</h4>
                  <div class="text-sm text-slate-500 dark:text-slate-400">
                    <p>Pesanan: <span class="font-medium">{{ doc.order?.order_number || 'N/A' }}</span></p>
                    <p>{{ formatDate(doc.created_at) }}</p>
                    <p v-if="doc.expires_at" :class="{ 'text-red-600 dark:text-red-400 font-medium': isExpired(doc.expires_at) }">
                      {{ getExpiryText(doc.expires_at) }}
                    </p>
                  </div>
                </div>
                <div class="flex flex-shrink-0 gap-2">
                  <Button 
                    variant="outline" 
                    size="sm"
                    @click="navigateToDocumentDetail(doc.order_id, doc.id)"
                  >
                    <Eye class="h-4 w-4 mr-1" />
                    Lihat Detail
                  </Button>
                  <Button 
                    variant="secondary" 
                    size="sm"
                    @click="markAsRead(doc.order_id, doc.id)"
                  >
                    <CheckCircle class="h-4 w-4 mr-1" />
                    Tandai Dibaca
                  </Button>
                </div>
              </div>
            </div>
          </div>
        </CardContent>
      </Card>

      <!-- Semua Dokumen -->
      <Card>
        <CardHeader>
          <CardTitle>Semua Dokumen</CardTitle>
          <CardDescription>
            Dokumen, kredensial, dan informasi terkait pesanan Anda
          </CardDescription>
        </CardHeader>
        <CardContent>
          <!-- Kredensial -->
          <div v-if="filteredCredentials.length > 0" class="mb-8">
            <h3 class="text-lg font-medium mb-4 flex items-center">
              <Key class="h-5 w-5 text-blue-500 mr-2" />
              Kredensial
            </h3>
            <div class="space-y-4">
              <div v-for="doc in filteredCredentials" :key="doc.id" class="p-4 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-md">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                  <div>
                    <div class="flex items-center space-x-2 mb-2">
                      <Badge variant="blue">
                        Kredensial
                      </Badge>
                      <Badge v-if="doc.expires_at && isExpired(doc.expires_at)" variant="destructive" size="sm">
                        Kedaluwarsa
                      </Badge>
                    </div>
                    <h4 class="text-lg font-medium text-slate-900 dark:text-white mb-1">{{ doc.title }}</h4>
                    <div class="text-sm text-slate-500 dark:text-slate-400">
                      <p>Pesanan: <span class="font-medium">{{ doc.order?.order_number || 'N/A' }}</span></p>
                      <p>{{ formatDate(doc.created_at) }}</p>
                      <p v-if="doc.expires_at" :class="{ 'text-red-600 dark:text-red-400 font-medium': isExpired(doc.expires_at) }">
                        {{ getExpiryText(doc.expires_at) }}
                      </p>
                    </div>
                  </div>
                  <div class="flex-shrink-0">
                    <Button 
                      variant="outline" 
                      size="sm"
                      @click="navigateToDocumentDetail(doc.order_id, doc.id)"
                    >
                      <Eye class="h-4 w-4 mr-1" />
                      Lihat Detail
                    </Button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Informasi Domain -->
          <div v-if="filteredDomainInfo.length > 0" class="mb-8">
            <h3 class="text-lg font-medium mb-4 flex items-center">
              <Globe class="h-5 w-5 text-green-500 mr-2" />
              Informasi Domain
            </h3>
            <div class="space-y-4">
              <div v-for="doc in filteredDomainInfo" :key="doc.id" class="p-4 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-md">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                  <div>
                    <div class="flex items-center space-x-2 mb-2">
                      <Badge variant="green">
                        Domain
                      </Badge>
                      <Badge v-if="doc.expires_at && isExpired(doc.expires_at)" variant="destructive" size="sm">
                        Kedaluwarsa
                      </Badge>
                    </div>
                    <h4 class="text-lg font-medium text-slate-900 dark:text-white mb-1">{{ doc.title }}</h4>
                    <div class="text-sm text-slate-500 dark:text-slate-400">
                      <p>Pesanan: <span class="font-medium">{{ doc.order?.order_number || 'N/A' }}</span></p>
                      <p>{{ formatDate(doc.created_at) }}</p>
                      <p v-if="doc.expires_at" :class="{ 'text-red-600 dark:text-red-400 font-medium': isExpired(doc.expires_at) }">
                        {{ getExpiryText(doc.expires_at) }}
                      </p>
                    </div>
                  </div>
                  <div class="flex-shrink-0">
                    <Button 
                      variant="outline" 
                      size="sm"
                      @click="navigateToDocumentDetail(doc.order_id, doc.id)"
                    >
                      <Eye class="h-4 w-4 mr-1" />
                      Lihat Detail
                    </Button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Pembaruan -->
          <div v-if="filteredUpdates.length > 0" class="mb-8">
            <h3 class="text-lg font-medium mb-4 flex items-center">
              <RefreshCw class="h-5 w-5 text-orange-500 mr-2" />
              Pembaruan
            </h3>
            <div class="space-y-4">
              <div v-for="doc in filteredUpdates" :key="doc.id" class="p-4 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-md">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                  <div>
                    <div class="flex items-center space-x-2 mb-2">
                      <Badge variant="orange">
                        Pembaruan
                      </Badge>
                    </div>
                    <h4 class="text-lg font-medium text-slate-900 dark:text-white mb-1">{{ doc.title }}</h4>
                    <div class="text-sm text-slate-500 dark:text-slate-400">
                      <p>Pesanan: <span class="font-medium">{{ doc.order?.order_number || 'N/A' }}</span></p>
                      <p>{{ formatDate(doc.created_at) }}</p>
                    </div>
                  </div>
                  <div class="flex-shrink-0">
                    <Button 
                      variant="outline" 
                      size="sm"
                      @click="navigateToDocumentDetail(doc.order_id, doc.id)"
                    >
                      <Eye class="h-4 w-4 mr-1" />
                      Lihat Detail
                    </Button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Unduhan -->
          <div v-if="filteredDownloads.length > 0" class="mb-8">
            <h3 class="text-lg font-medium mb-4 flex items-center">
              <Download class="h-5 w-5 text-purple-500 mr-2" />
              Berkas Unduhan
            </h3>
            <div class="space-y-4">
              <div v-for="doc in filteredDownloads" :key="doc.id" class="p-4 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-md">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                  <div>
                    <div class="flex items-center space-x-2 mb-2">
                      <Badge variant="purple">
                        Unduhan
                      </Badge>
                    </div>
                    <h4 class="text-lg font-medium text-slate-900 dark:text-white mb-1">{{ doc.title }}</h4>
                    <div class="text-sm text-slate-500 dark:text-slate-400">
                      <p>Pesanan: <span class="font-medium">{{ doc.order?.order_number || 'N/A' }}</span></p>
                      <p>{{ formatDate(doc.created_at) }}</p>
                    </div>
                  </div>
                  <div class="flex-shrink-0">
                    <Button 
                      variant="outline" 
                      size="sm"
                      @click="navigateToDocumentDetail(doc.order_id, doc.id)"
                    >
                      <Eye class="h-4 w-4 mr-1" />
                      Lihat Detail
                    </Button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Empty State -->
          <div v-if="!hasDocuments" class="text-center py-12">
            <div class="mx-auto h-16 w-16 text-slate-300">
              <File class="h-16 w-16" />
            </div>
            <h3 class="mt-4 text-xl font-medium text-slate-900 dark:text-white">Tidak Ada Dokumen</h3>
            <p class="mt-2 text-slate-500 dark:text-slate-400 max-w-md mx-auto">
              Anda belum memiliki dokumen terkait pesanan. Dokumen akan muncul di sini saat tersedia.
            </p>
          </div>
        </CardContent>
      </Card>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { toRefs } from '@vueuse/core';
import { useToast } from '@/composables/useToast';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { CheckCircle, Download, Eye, File, RefreshCw, AlertTriangle, Key, Globe } from 'lucide-vue-next';

const props = defineProps({
  documents: {
    type: Array,
    default: () => []
  },
  breadcrumbs: {
    type: Array,
    default: () => [
      { title: 'Dashboard', href: route('dashboard') },
      { title: 'Dokumen Saya', href: route('my-documents') }
    ]
  }
});

const { documents } = toRefs(props);
const { toast } = useToast();

// Computed properties untuk filter dokumen berdasarkan tipe
const filteredCredentials = computed(() => {
  return (documents.value || []).filter(doc => doc.type === 'credential' && doc.is_read);
});

const filteredDomainInfo = computed(() => {
  return (documents.value || []).filter(doc => doc.type === 'domain' && doc.is_read);
});

const filteredUpdates = computed(() => {
  return (documents.value || []).filter(doc => doc.type === 'update' && doc.is_read);
});

const filteredDownloads = computed(() => {
  return (documents.value || []).filter(doc => doc.type === 'download' && doc.is_read);
});

const unreadDocuments = computed(() => {
  return (documents.value || []).filter(doc => !doc.is_read);
});

const totalDocuments = computed(() => {
  return (documents.value || []).length;
});

const totalUnread = computed(() => {
  return unreadDocuments.value.length;
});

const hasDocuments = computed(() => {
  return totalDocuments.value > 0;
});

// Fungsi untuk mendapatkan label tipe dokumen sesuai bahasa Indonesia
function getDocumentTypeLabel(type) {
  const types = {
    'credential': 'Kredensial',
    'domain': 'Domain',
    'update': 'Pembaruan',
    'download': 'Unduhan',
    'invoice': 'Faktur',
    'receipt': 'Kuitansi'
  };
  
  return types[type] || 'Dokumen';
}

// Fungsi untuk mendapatkan variant badge berdasarkan tipe dokumen
function getBadgeVariant(type) {
  const variants = {
    'credential': 'blue',
    'domain': 'green',
    'update': 'orange',
    'download': 'purple',
    'invoice': 'yellow',
    'receipt': 'default'
  };
  
  return variants[type] || 'default';
}

// Fungsi untuk memformat tanggal
function formatDate(dateString) {
  if (!dateString) return '';
  
  const date = new Date(dateString);
  return new Intl.DateTimeFormat('id-ID', {
    day: 'numeric',
    month: 'long',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  }).format(date);
}

// Fungsi untuk mengecek apakah dokumen sudah kedaluwarsa
function isExpired(expiryDate) {
  if (!expiryDate) return false;
  
  const now = new Date();
  const expiry = new Date(expiryDate);
  return now > expiry;
}

// Fungsi untuk mendapatkan teks kedaluwarsa
function getExpiryText(expiryDate) {
  if (!expiryDate) return '';
  
  const now = new Date();
  const expiry = new Date(expiryDate);
  
  if (now > expiry) {
    return 'Kedaluwarsa: ' + formatDate(expiryDate);
  }
  
  return 'Kedaluwarsa pada: ' + formatDate(expiryDate);
}

// Fungsi untuk menandai dokumen sebagai dibaca
async function markAsRead(orderId, documentId) {
  try {
    await router.post(route('orders.documents.mark-as-read', [orderId, documentId]));
    toast({
      title: 'Dokumen ditandai sebagai dibaca',
      description: 'Dokumen berhasil ditandai sebagai telah dibaca',
      variant: 'success'
    });
  } catch (error) {
    toast({
      title: 'Error',
      description: 'Gagal menandai dokumen sebagai dibaca',
      variant: 'destructive'
    });
  }
}

// Fungsi untuk navigasi ke halaman detail dokumen
function navigateToDocumentDetail(orderId, documentId) {
  router.visit(route('orders.documents.show', [orderId, documentId]));
}
</script> 