<template>
  <Head title="Semua Dokumen Order" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 p-4 md:p-6">
      <!-- Header dengan judul dan tombol aksi -->
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h1 class="text-2xl font-bold text-secondary-900 dark:text-white">Semua Dokumen Order</h1>
        <div class="flex flex-wrap items-center gap-2">
          <Link :href="route('admin.orders.index')">
            <Button variant="outline" size="sm" class="h-9">
              <FileText class="h-4 w-4 mr-2" />
              Kelola Order
            </Button>
          </Link>
          <Button variant="action" size="sm" class="h-9" @click="showCreateModal = true">
            <PlusIcon class="h-4 w-4 mr-2" />
            Tambah Dokumen
          </Button>
        </div>
      </div>

      <!-- Search dan filter -->
      <div class="bg-white dark:bg-slate-800 rounded-lg shadow p-4 border border-slate-200 dark:border-slate-700 flex flex-col sm:flex-row gap-4 justify-between">
        <div class="relative w-full sm:max-w-sm">
          <Search class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-500" />
          <Input
            v-model="search"
            placeholder="Cari dokumen..."
            class="pl-9"
          />
        </div>
        
        <div class="flex items-center gap-3">
          <span class="text-sm text-slate-500 dark:text-slate-400 whitespace-nowrap">Tampilkan:</span>
          <div class="relative custom-select-container perpage-select-container" :class="{ 'active': isPerPageSelectOpen }">
            <div 
              @click="togglePerPageSelect" 
              class="custom-select-trigger flex w-[80px] items-center justify-between gap-2 rounded-md border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-3 py-2 text-sm shadow-sm hover:border-slate-300 dark:hover:border-slate-600 focus:outline-none focus:ring-0 disabled:cursor-not-allowed disabled:opacity-50 cursor-pointer h-9"
            >
              <span>{{ perPage }}</span>
              <ChevronDown class="h-4 w-4 opacity-50 transition-transform" :class="{ 'rotate-180': isPerPageSelectOpen }" />
            </div>
            
            <div 
              v-if="isPerPageSelectOpen" 
              class="custom-select-dropdown bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-md shadow-lg mt-1 overflow-hidden z-50"
            >
              <div 
                v-for="option in perPageOptions" 
                :key="option.value"
                @click="selectPerPage(option.value)"
                class="custom-select-option py-2 px-3 text-slate-900 dark:text-slate-100 hover:bg-slate-50 dark:hover:bg-slate-700 cursor-pointer text-sm"
                :class="{ 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-300 font-medium': perPage === option.value }"
              >
                {{ option.label }}
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Table -->
      <div class="bg-white dark:bg-slate-800 text-secondary-900 dark:text-white rounded-xl shadow border border-slate-200 dark:border-slate-700 overflow-hidden">
        <div class="p-6 border-b border-secondary-200 dark:border-slate-700">
          <h2 class="text-lg font-medium">Daftar Dokumen Order</h2>
          <p class="text-muted-foreground mt-1">Kelola semua dokumen order dari semua customer di satu tempat.</p>
        </div>

        <div class="overflow-x-auto">
          <Table>
            <TableCaption v-if="documents.data.length === 0">
              Tidak ada dokumen yang ditemukan.
            </TableCaption>
            <TableHeader>
              <TableRow class="hover:bg-transparent border-b border-secondary-200 dark:border-slate-700">
                <TableHead class="py-3 px-6 font-medium text-secondary-600 dark:text-secondary-400">Judul Dokumen</TableHead>
                <TableHead class="py-3 px-6 font-medium text-secondary-600 dark:text-secondary-400">No. Order</TableHead>
                <TableHead class="py-3 px-6 font-medium text-secondary-600 dark:text-secondary-400">Customer</TableHead>
                <TableHead class="py-3 px-6 font-medium text-secondary-600 dark:text-secondary-400">Tipe</TableHead>
                <TableHead class="py-3 px-6 font-medium text-secondary-600 dark:text-secondary-400">Status</TableHead>
                <TableHead class="py-3 px-6 font-medium text-secondary-600 dark:text-secondary-400 text-right">Aksi</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              <TableRow v-for="document in documents.data" :key="document.id">
                <TableCell class="py-4 px-6 font-medium">
                  <div class="flex items-center gap-2">
                    <component :is="getDocumentIcon(document.type)" class="h-4 w-4" />
                    {{ document.title }}
                  </div>
                </TableCell>
                <TableCell class="py-4 px-6">
                  <Link :href="route('admin.orders.show', document.order_id)" class="text-primary hover:underline">
                    {{ document.order.order_number }}
                  </Link>
                </TableCell>
                <TableCell class="py-4 px-6">{{ document.order.user.name }}</TableCell>
                <TableCell class="py-4 px-6">
                  <Badge :variant="getBadgeVariant(document.type)">
                    {{ getDocumentType(document.type) }}
                  </Badge>
                </TableCell>
                <TableCell class="py-4 px-6">
                  <Badge :variant="document.is_sent ? 'success' : 'outline'">
                    {{ document.is_sent ? 'Terkirim' : 'Belum Terkirim' }}
                  </Badge>
                </TableCell>
                <TableCell class="py-4 px-6 text-right">
                  <div class="flex justify-end">
                    <DropdownMenu>
                      <DropdownMenuTrigger asChild>
                        <Button variant="action" size="icon" class="h-8 w-8 rounded-md">
                          <MoreHorizontal class="h-4 w-4" />
                          <span class="sr-only">Menu</span>
                        </Button>
                      </DropdownMenuTrigger>
                      <DropdownMenuContent align="end" class="w-[160px]">
                        <DropdownMenuItem 
                          @click="viewDocumentDetail(document)" 
                          class="flex items-center gap-2 cursor-pointer py-1.5"
                        >
                          <Eye class="h-4 w-4" />
                          <span>Lihat Detail</span>
                        </DropdownMenuItem>
                        <DropdownMenuItem 
                          v-if="document.file_path"
                          @click="downloadDocument(document.id)" 
                          class="flex items-center gap-2 cursor-pointer py-1.5"
                        >
                          <Download class="h-4 w-4" />
                          <span>Unduh</span>
                        </DropdownMenuItem>
                        <DropdownMenuItem 
                          @click="editDocument(document)" 
                          class="flex items-center gap-2 cursor-pointer py-1.5"
                        >
                          <Edit class="h-4 w-4" />
                          <span>Edit</span>
                        </DropdownMenuItem>
                        <DropdownMenuItem 
                          @click="showSendModal = true; selectedDocument = document" 
                          class="flex items-center gap-2 cursor-pointer py-1.5"
                        >
                          <Mail class="h-4 w-4" />
                          <span>Kirim</span>
                        </DropdownMenuItem>
                       
                      </DropdownMenuContent>
                    </DropdownMenu>
                  </div>
                </TableCell>
              </TableRow>
            </TableBody>
          </Table>
        </div>
      </div>

      <div class="mt-4 flex justify-end">
        <Pagination :links="documents.links" />
      </div>
    </div>

    <!-- Modal Tambah Dokumen -->
    <Dialog :open="showCreateModal" @update:open="showCreateModal = false">
      <DialogContent class="sm:max-w-[600px]">
        <DialogHeader>
          <DialogTitle>Tambah Dokumen Order</DialogTitle>
          <DialogDescription>
            Tambahkan dokumen baru yang berkaitan dengan order. Masukkan informasi dokumen dan pilih order terkait.
          </DialogDescription>
        </DialogHeader>
        <form @submit.prevent="createDocument">
          <div class="grid gap-4 py-4">
            <div class="grid grid-cols-4 items-center gap-4">
              <Label for="order" class="text-right">Order Terkait</Label>
              <div class="col-span-3">
                <div class="relative custom-select-container order-select-container" :class="{ 'active': isOrderSelectOpen }">
                  <div 
                    @click="toggleOrderSelect" 
                    class="custom-select-trigger flex w-full items-center justify-between gap-2 rounded-md border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-3 py-2 text-sm shadow-sm hover:border-slate-300 dark:hover:border-slate-600 focus:outline-none focus:ring-0 disabled:cursor-not-allowed disabled:opacity-50 cursor-pointer h-9"
                  >
                    <span v-if="createForm.order_id">
                      {{ getOrderLabel(createForm.order_id) }}
                    </span>
                    <span v-else class="text-slate-400">Pilih order</span>
                    <ChevronDown class="h-4 w-4 opacity-50 transition-transform" :class="{ 'rotate-180': isOrderSelectOpen }" />
                  </div>
                  
                  <div 
                    v-if="isOrderSelectOpen" 
                    class="custom-select-dropdown bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-md shadow-lg mt-1 overflow-hidden max-h-[200px] overflow-y-auto"
                  >
                    <div 
                      v-for="order in availableOrders" 
                      :key="order.id"
                      @click="selectOrder(order.id)"
                      class="custom-select-option py-2 px-3 text-slate-900 dark:text-slate-100 hover:bg-slate-50 dark:hover:bg-slate-700 cursor-pointer text-sm"
                      :class="{ 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-300 font-medium': createForm.order_id === String(order.id) }"
                    >
                      {{ order.order_number }} - {{ order.customer_name }}
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="grid grid-cols-4 items-center gap-4">
              <Label for="type" class="text-right">Tipe Dokumen</Label>
              <div class="col-span-3">
                <div class="relative custom-select-container type-select-container" :class="{ 'active': isTypeSelectOpen }">
                  <div 
                    @click="toggleTypeSelect" 
                    class="custom-select-trigger flex w-full items-center justify-between gap-2 rounded-md border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-3 py-2 text-sm shadow-sm hover:border-slate-300 dark:hover:border-slate-600 focus:outline-none focus:ring-0 disabled:cursor-not-allowed disabled:opacity-50 cursor-pointer h-9"
                  >
                    <span>{{ getDocumentType(createForm.type) }}</span>
                    <ChevronDown class="h-4 w-4 opacity-50 transition-transform" :class="{ 'rotate-180': isTypeSelectOpen }" />
                  </div>
                  
                  <div 
                    v-if="isTypeSelectOpen" 
                    class="custom-select-dropdown bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-md shadow-lg mt-1 overflow-hidden"
                  >
                    <div 
                      v-for="(label, type) in documentTypes" 
                      :key="type"
                      @click="selectType(type)"
                      class="custom-select-option py-2 px-3 text-slate-900 dark:text-slate-100 hover:bg-slate-50 dark:hover:bg-slate-700 cursor-pointer text-sm"
                      :class="{ 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-300 font-medium': createForm.type === type }"
                    >
                      {{ label }}
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="grid grid-cols-4 items-center gap-4">
              <Label for="title" class="text-right">Judul Dokumen</Label>
              <Input id="title" v-model="createForm.title" class="col-span-3" />
            </div>
            <div class="grid grid-cols-4 items-center gap-4">
              <Label for="content" class="text-right">Isi Dokumen</Label>
              <Textarea id="content" v-model="createForm.content" class="col-span-3" rows="4" />
            </div>
            <div class="grid grid-cols-4 items-center gap-4">
              <Label for="file" class="text-right">Unggah File</Label>
              <Input id="file" type="file" class="col-span-3" @change="handleFileChange" />
            </div>
            <div class="grid grid-cols-4 items-center gap-4">
              <Label for="should_notify" class="text-right">Kirim Notifikasi</Label>
              <div class="flex items-center space-x-2 col-span-3">
                <Checkbox id="should_notify" v-model="createForm.should_notify" />
                <Label for="should_notify">Kirim notifikasi ke pelanggan</Label>
              </div>
            </div>
          </div>
          <DialogFooter>
            <Button type="button" variant="outline" @click="showCreateModal = false">Batal</Button>
            <Button type="submit" :disabled="isSubmitting">
              <Loader2 v-if="isSubmitting" class="mr-2 h-4 w-4 animate-spin" />
              Tambah Dokumen
            </Button>
          </DialogFooter>
        </form>
      </DialogContent>
    </Dialog>

    <!-- Modal Konfirmasi Hapus -->
    <Dialog :open="showDeleteModal" @update:open="showDeleteModal = false">
      <DialogContent>
        <DialogHeader>
          <DialogTitle>Konfirmasi Hapus</DialogTitle>
          <DialogDescription>
            Apakah Anda yakin ingin menghapus dokumen ini? Tindakan ini tidak dapat dibatalkan.
          </DialogDescription>
        </DialogHeader>
        <DialogFooter>
          <Button variant="outline" @click="showDeleteModal = false">Batal</Button>
          <Button variant="destructive" @click="deleteDocument" :disabled="isSubmitting">
            <Loader2 v-if="isSubmitting" class="mr-2 h-4 w-4 animate-spin" />
            Hapus
          </Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>

    <!-- Modal Konfirmasi Kirim Dokumen -->
    <Dialog :open="showSendModal" @update:open="showSendModal = false">
      <DialogContent>
        <DialogHeader>
          <DialogTitle>Kirim Dokumen ke Pelanggan</DialogTitle>
          <DialogDescription>
            Dokumen akan dikirim melalui email ke pelanggan. Konfirmasi untuk melanjutkan.
          </DialogDescription>
        </DialogHeader>
        <div class="py-4">
          <div v-if="selectedDocument" class="space-y-2">
            <div class="flex items-center">
              <span class="font-medium w-32">Judul:</span>
              <span>{{ selectedDocument.title }}</span>
            </div>
            <div class="flex items-center">
              <span class="font-medium w-32">Tipe Dokumen:</span>
              <Badge :variant="getBadgeVariant(selectedDocument.type)">
                {{ getDocumentType(selectedDocument.type) }}
              </Badge>
            </div>
            <div class="flex items-center">
              <span class="font-medium w-32">Pesanan:</span>
              <span>{{ selectedDocument.order.order_number }}</span>
            </div>
            <div class="flex items-center">
              <span class="font-medium w-32">Pelanggan:</span>
              <span>{{ selectedDocument.order.user.name }}</span>
            </div>
          </div>
        </div>
        <DialogFooter>
          <Button variant="outline" @click="showSendModal = false">Batal</Button>
          <Button @click="sendDocument" :disabled="isSubmitting">
            <Loader2 v-if="isSubmitting" class="mr-2 h-4 w-4 animate-spin" />
            <Mail class="mr-2 h-4 w-4" />
            Kirim Dokumen
          </Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref, watch, onMounted, nextTick, onUnmounted } from 'vue';
import { Head, router, Link } from '@inertiajs/vue3';
import { debounce } from 'lodash';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import { Badge } from '@/components/ui/badge';
import { Checkbox } from '@/components/ui/checkbox';
import { Label } from '@/components/ui/label';
import { useToast } from '@/composables/useToast';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle
} from '@/components/ui/dialog';
import {
    Table,
    TableBody,
    TableCaption,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger
} from '@/components/ui/dropdown-menu';
import Pagination from '@/components/Pagination.vue';
import { 
    Download, 
    FileText, 
    Edit, 
    Eye,
    Globe, 
    RefreshCw, 
    Key, 
    Trash2, 
    Mail, 
    Plus as PlusIcon,
    Search,
    Loader2,
    ChevronDown,
    MoreHorizontal
} from 'lucide-vue-next';

// Breadcrumbs untuk navigasi
const breadcrumbs = [
    { title: 'Dashboard', href: route('admin.dashboard') },
    { title: 'Dokumen Order', href: route('admin.documents.all') },
];

const props = defineProps<{
    documents: {
        data: Array<{
            id: number;
            title: string;
            file_path: string;
            order_id: number;
            order: {
                id: number;
                order_number: string;
                user: {
                    name: string;
                }
            };
            type: string;
            type_label: string;
            type_icon: string;
            type_color: string;
            content: string;
            expires_at: string | null;
            is_sent: boolean;
            sent_at: string | null;
            created_at: string;
            file_size: number | null;
        }>;
        links: Array<{
            url: string | null;
            label: string;
            active: boolean;
        }>;
        meta: {
            current_page: number;
            from: number;
            last_page: number;
            links: Array<any>;
            path: string;
            per_page: number;
            to: number;
            total: number;
        };
    };
    filters: {
        search: string;
        per_page: number;
    };
    available_orders?: Array<{
        id: number;
        order_number: string;
        customer_name: string;
    }>;
}>();

const search = ref(props.filters.search || '');
const perPage = ref(props.filters.per_page?.toString() || '10');
const currentPage = ref(1);

// Status modals
const showCreateModal = ref(false);
const showEditModal = ref(false);
const showDeleteModal = ref(false);
const showSendModal = ref(false);
const isSubmitting = ref(false);
const selectedDocument = ref<any>(null);

// Availabe orders untuk dropdown
const availableOrders = ref(props.available_orders || []);

// Form untuk membuat dokumen baru
const createForm = ref({
    order_id: '',
    type: 'credential',
    title: '',
    content: '',
    file: null,
    should_notify: false
});

// Handler untuk file upload
const handleFileChange = (event: any) => {
    createForm.value.file = event.target.files[0];
};

// Fungsi untuk membuat dokumen baru
const createDocument = () => {
    isSubmitting.value = true;

    const formData = new FormData();
    formData.append('order_id', createForm.value.order_id);
    formData.append('type', createForm.value.type);
    formData.append('title', createForm.value.title);
    formData.append('content', createForm.value.content);
    formData.append('should_notify', createForm.value.should_notify ? '1' : '0');
    
    if (createForm.value.file) {
        formData.append('file', createForm.value.file);
    }

    router.post(route('admin.orders.documents.store', createForm.value.order_id), formData, {
        onSuccess: () => {
            showCreateModal.value = false;
            createForm.value = {
                order_id: '',
                type: 'credential',
                title: '',
                content: '',
                file: null,
                should_notify: false
            };
        },
        onFinish: () => {
            isSubmitting.value = false;
        }
    });
};

// Konfirmasi hapus dokumen
const confirmDelete = (document: any) => {
    selectedDocument.value = document;
    showDeleteModal.value = true;
};

// Hapus dokumen
const deleteDocument = () => {
    isSubmitting.value = true;
    
    router.delete(route('admin.orders.documents.destroy', [selectedDocument.value.order_id, selectedDocument.value.id]), {
        onSuccess: () => {
            showDeleteModal.value = false;
            selectedDocument.value = null;
        },
        onFinish: () => {
            isSubmitting.value = false;
        }
    });
};

// Dokumentasi tipe dan badge color
const documentTypes = {
    credential: 'Kredensial Login',
    domain: 'Informasi Domain',
    update: 'Pembaruan',
    download: 'File Unduhan',
    other: 'Lainnya'
};

const badgeVariants = {
    credential: 'default',
    domain: 'secondary',
    update: 'outline',
    download: 'destructive',
};

// Format file size (jika diperlukan)
const formatFileSize = (bytes: number | null) => {
    if (!bytes) return '-';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
};

// Format tanggal
const formatDate = (dateString: string | null) => {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return new Intl.DateTimeFormat('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    }).format(date);
};

// Update search dengan debounce
const updateSearch = debounce((value: string) => {
    router.get(route('admin.documents.all'), {
        search: value,
        per_page: perPage.value
    }, {
        preserveState: true,
        replace: true
    });
}, 500);

// Watch search input
watch(search, (value) => {
    updateSearch(value);
});

// State untuk dropdown
const isPerPageSelectOpen = ref(false);
const perPageSelectRef = ref<HTMLElement | null>(null);

// Opsi per halaman
const perPageOptions = [
    { value: '10', label: '10' },
    { value: '25', label: '25' },
    { value: '50', label: '50' },
    { value: '100', label: '100' },
];

// Toggle dropdown per page
const togglePerPageSelect = () => {
    isPerPageSelectOpen.value = !isPerPageSelectOpen.value;
};

// Handle per page selection
const selectPerPage = (value: string) => {
    perPage.value = value;
    isPerPageSelectOpen.value = false;
    updatePerPage(value);
};

// Handle click outside for perpage dropdown
const handlePerPageClickOutside = (evt: MouseEvent) => {
    const target = evt.target as Node;
    if (perPageSelectRef.value && !perPageSelectRef.value.contains(target)) {
        isPerPageSelectOpen.value = false;
    }
};

// Update per page
const updatePerPage = (value: string) => {
    router.get(route('admin.documents.all'), {
        search: search.value,
        per_page: value
    }, {
        preserveState: true,
        replace: true
    });
};

// Download dokumen
const downloadDocument = (id: number) => {
    window.open(route('documents.download', id));
};

// Get badge variant
const getBadgeVariant = (type: string) => {
    return badgeVariants[type as keyof typeof badgeVariants] || 'default';
};

// Get document type display name
const getDocumentType = (type: string) => {
    return documentTypes[type as keyof typeof documentTypes] || 'Lainnya';
};

// Get document icon
const getDocumentIcon = (type: string) => {
    switch (type) {
        case 'credential': return Key;
        case 'domain': return Globe;
        case 'update': return RefreshCw;
        case 'download': return Download;
        default: return FileText;
    }
};

// State untuk dropdown tipe dokumen dan order
const isOrderSelectOpen = ref(false);
const isTypeSelectOpen = ref(false);
const orderSelectRef = ref<HTMLElement | null>(null);
const typeSelectRef = ref<HTMLElement | null>(null);

// Toggle dropdown order
const toggleOrderSelect = () => {
  isOrderSelectOpen.value = !isOrderSelectOpen.value;
  
  // Tutup dropdown lain jika terbuka
  if (isOrderSelectOpen.value) {
    isTypeSelectOpen.value = false;
    isPerPageSelectOpen.value = false;
  }
};

// Handle order selection
const selectOrder = (id: number) => {
  createForm.value.order_id = String(id);
  isOrderSelectOpen.value = false;
};

// Toggle dropdown tipe dokumen
const toggleTypeSelect = () => {
  isTypeSelectOpen.value = !isTypeSelectOpen.value;
  
  // Tutup dropdown lain jika terbuka
  if (isTypeSelectOpen.value) {
    isOrderSelectOpen.value = false;
    isPerPageSelectOpen.value = false;
  }
};

// Handle tipe dokumen selection
const selectType = (type: string) => {
  createForm.value.type = type;
  isTypeSelectOpen.value = false;
};

// Get order label dari id
const getOrderLabel = (id: number | string) => {
  const orderId = typeof id === 'string' ? parseInt(id) : id;
  const order = availableOrders.value.find(o => o.id === orderId);
  return order ? `${order.order_number} - ${order.customer_name}` : 'Pilih order';
};

// Handle click outside for type dan order dropdown
const handleTypeClickOutside = (evt: MouseEvent) => {
  const target = evt.target as Node;
  if (typeSelectRef.value && !typeSelectRef.value.contains(target)) {
    isTypeSelectOpen.value = false;
  }
};

const handleOrderClickOutside = (evt: MouseEvent) => {
  const target = evt.target as Node;
  if (orderSelectRef.value && !orderSelectRef.value.contains(target)) {
    isOrderSelectOpen.value = false;
  }
};

// Ambil daftar order yang tersedia untuk dropdown
onMounted(() => {
  // Set up click outside listeners
  document.addEventListener('click', handlePerPageClickOutside);
  document.addEventListener('click', handleTypeClickOutside);
  document.addEventListener('click', handleOrderClickOutside);
  
  nextTick(() => {
    perPageSelectRef.value = document.querySelector('.perpage-select-container');
    typeSelectRef.value = document.querySelector('.type-select-container');
    orderSelectRef.value = document.querySelector('.order-select-container');
  });
  
  if (!props.available_orders || props.available_orders.length === 0) {
    router.get(route('admin.orders.index'), {
        only: ['available_orders']
    }, {
        preserveState: true,
        onSuccess: (page: any) => {
            if (page.props.available_orders) {
                availableOrders.value = page.props.available_orders;
            }
        }
    });
  }
});

onUnmounted(() => {
  document.removeEventListener('click', handlePerPageClickOutside);
  document.removeEventListener('click', handleTypeClickOutside);
  document.removeEventListener('click', handleOrderClickOutside);
});

// Kirim dokumen
const sendDocument = () => {
    if (!selectedDocument.value) return;
    
    isSubmitting.value = true;
    const { toast } = useToast();
    
    router.post(route('admin.orders.documents.send', [selectedDocument.value.order_id, selectedDocument.value.id]), {}, {
        onSuccess: () => {
            showSendModal.value = false;
            // Update status dokumen di UI
            if (selectedDocument.value) {
                selectedDocument.value.is_sent = true;
                selectedDocument.value.sent_at = new Date().toISOString();
            }
            // Tampilkan toast notification sukses
            toast({
                title: 'Dokumen terkirim',
                description: 'Dokumen berhasil dikirim ke pelanggan',
            });
        },
        onError: (errors: any) => {
            // Tampilkan toast notification error
            toast({
                title: 'Gagal mengirim dokumen',
                description: errors.message || 'Terjadi kesalahan saat mengirim dokumen',
                variant: 'destructive',
            });
        },
        onFinish: () => {
            isSubmitting.value = false;
        }
    });
};

// Handle pagination
const onSuccess = (page: number) => {
    currentPage.value = page;
};

// Fungsi untuk melihat detail dokumen
const viewDocumentDetail = (document: any) => {
  router.visit(route('admin.orders.documents.show', [document.order_id, document.id]));
};

// Fungsi untuk mengedit dokumen
const editDocument = (document: any) => {
  router.visit(route('admin.orders.documents.edit', [document.order_id, document.id]));
};
</script>

<style>
/* Custom select styling */
.custom-select-container {
  position: relative;
  width: 100%;
  -webkit-tap-highlight-color: transparent;
  border-radius: 0.375rem;
}

.custom-select-dropdown {
  position: absolute;
  top: 100%;
  left: 0;
  width: 100%;
  max-height: 200px;
  overflow-y: auto;
  animation: slideDown 0.15s ease-out;
  z-index: 50;
}

.custom-select-option:first-child {
  border-top-left-radius: 0.375rem;
  border-top-right-radius: 0.375rem;
}

.custom-select-option:last-child {
  border-bottom-left-radius: 0.375rem;
  border-bottom-right-radius: 0.375rem;
}

@keyframes slideDown {
  from { opacity: 0; transform: translateY(-5px); }
  to { opacity: 1; transform: translateY(0); }
}

/* Perbaikan outline saat fokus */
.custom-select-trigger {
  outline: none !important;
  -webkit-appearance: none;
  -webkit-tap-highlight-color: transparent !important;
}

.custom-select-trigger:focus,
.custom-select-trigger:focus-visible,
.custom-select-trigger:active,
.custom-select-trigger:hover,
.custom-select-trigger:-moz-focusring {
  outline: none !important;
  box-shadow: none !important;
  border-color: #0ea5e9 !important;
}

/* Fix untuk Firefox */
.custom-select-trigger:-moz-focusring {
  outline: none !important;
}

/* Fix untuk Safari dan Chrome */
.custom-select-trigger::-webkit-focus-inner {
  border: 0;
}

/* Fix tambahan untuk Chrome */
*:focus {
  outline-color: transparent !important;
}
</style> 