<template>
  <Head title="Kategori Pengeluaran" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 p-4 md:p-6">
      <!-- Header dengan judul dan tombol tambah -->
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h1 class="text-2xl font-bold text-secondary-900 dark:text-white">Kategori Pengeluaran</h1>
        <Link :href="route('admin.expense-categories.create')">
          <Button variant="action" class="flex items-center gap-1.5 w-full sm:w-auto">
            <PlusIcon class="w-4 h-4" />
            Tambah Kategori
          </Button>
        </Link>
      </div>
      
      <div class="bg-white dark:bg-slate-800 text-secondary-900 dark:text-white rounded-xl shadow border border-slate-200 dark:border-slate-700 overflow-hidden">
        <div class="p-6 border-b border-secondary-200 dark:border-slate-700">
          <div>
            <h2 class="text-lg font-medium text-secondary-900 dark:text-white">Daftar Kategori Pengeluaran</h2>
            <p class="text-secondary-600 dark:text-secondary-400 mt-1">Kelola kategori pengeluaran untuk pencatatan keuangan</p>
          </div>
        </div>
        
        <!-- Filter dan pencarian -->
        <div class="p-6 border-b border-secondary-200 dark:border-slate-700">
          <form @submit.prevent="submit" class="grid gap-4 sm:grid-cols-2 md:grid-cols-3">
            <div class="space-y-2">
              <Label for="search">Cari</Label>
              <div class="relative">
                <Input 
                  id="search" 
                  v-model="form.search" 
                  placeholder="Cari nama kategori"
                  class="w-full pl-9"
                />
                <div class="absolute left-3 top-1/2 -translate-y-1/2 text-secondary-500">
                  <SearchIcon class="h-4 w-4" />
                </div>
              </div>
            </div>
            <div class="space-y-2">
              <Label for="status">Status</Label>
              <Select v-model="form.status">
                <SelectTrigger>
                  <SelectValue :placeholder="selectedStatusLabel" />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem v-for="option in statusOptions" :key="option.value" :value="option.value">
                    {{ option.label }}
                  </SelectItem>
                </SelectContent>
              </Select>
            </div>
            <div class="flex gap-2 items-end">
              <Button type="submit" variant="action" class="h-9">Filter</Button>
              <Button variant="action-outline" @click="resetFilters" class="h-9">Reset</Button>
            </div>
          </form>
        </div>
        
        <!-- Daftar Kategori Pengeluaran -->
        <div class="overflow-x-auto">
          <Table>
            <TableHeader>
              <TableRow class="hover:bg-transparent border-b border-secondary-200 dark:border-slate-700">
                <TableHead class="py-3 px-6 font-medium text-secondary-600 dark:text-secondary-400">Nama</TableHead>
                <TableHead class="py-3 px-6 font-medium text-secondary-600 dark:text-secondary-400">Deskripsi</TableHead>
                <TableHead class="py-3 px-6 font-medium text-secondary-600 dark:text-secondary-400">Warna</TableHead>
                <TableHead class="py-3 px-6 font-medium text-secondary-600 dark:text-secondary-400">Jumlah Pengeluaran</TableHead>
                <TableHead class="py-3 px-6 font-medium text-secondary-600 dark:text-secondary-400">Status</TableHead>
                <TableHead class="py-3 px-6 font-medium text-secondary-600 dark:text-secondary-400 text-right">Aksi</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              <TableRow 
                v-for="category in categories.data" 
                :key="category.id" 
                class="border-b border-secondary-200/60 dark:border-slate-700/60 hover:bg-secondary-100/50 dark:hover:bg-secondary-800/50"
              >
                <TableCell class="py-3.5 px-6 align-middle font-medium text-secondary-900 dark:text-white">
                  {{ category.name }}
                </TableCell>
                <TableCell class="py-3.5 px-6 align-middle">{{ category.description || '-' }}</TableCell>
                <TableCell class="py-3.5 px-6 align-middle">
                  <div v-if="category.color" class="w-6 h-6 rounded" :style="{ backgroundColor: category.color }"></div>
                  <span v-else>-</span>
                </TableCell>
                <TableCell class="py-3.5 px-6 align-middle">{{ category.expenses_count }}</TableCell>
                <TableCell class="py-3.5 px-6 align-middle">
                  <div v-if="category.is_active" 
                       class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-900 dark:bg-green-900 dark:text-green-300 border border-emerald-300 dark:border-green-800 w-fit">
                    <span class="size-2 bg-emerald-600 dark:bg-emerald-400 rounded-full"></span>
                    <span>Aktif</span>
                  </div>
                  <div v-else
                       class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-900 dark:bg-yellow-900 dark:text-yellow-300 border border-amber-300 dark:border-yellow-800 w-fit">
                    <span class="size-2 bg-amber-600 dark:bg-amber-400 rounded-full"></span>
                    <span>Nonaktif</span>
                  </div>
                </TableCell>
                <TableCell class="py-3.5 px-6 align-middle text-right">
                  <DropdownMenu>
                    <DropdownMenuTrigger asChild>
                      <Button variant="action" size="icon" class="h-8 w-8 rounded-md">
                        <MoreHorizontal class="h-4 w-4" />
                        <span class="sr-only">Menu</span>
                      </Button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent align="end" class="w-[160px]">
                      <DropdownMenuItem @click="editCategory(category)" class="flex items-center gap-2 cursor-pointer py-1.5">
                        <Pencil class="h-4 w-4" />
                        <span>Edit</span>
                      </DropdownMenuItem>
                      <DropdownMenuItem 
                        v-if="category.expenses_count === 0"
                        @click="openDeleteDialog(category)" 
                        variant="destructive" 
                        class="flex items-center gap-2 cursor-pointer py-1.5"
                      >
                        <Trash class="h-4 w-4" />
                        <span>Hapus</span>
                      </DropdownMenuItem>
                    </DropdownMenuContent>
                  </DropdownMenu>
                </TableCell>
              </TableRow>
              <TableRow v-if="categories.data.length === 0">
                <TableCell colspan="6" class="py-6 text-center text-secondary-600 dark:text-secondary-400">
                  Tidak ada data kategori pengeluaran
                </TableCell>
              </TableRow>
            </TableBody>
          </Table>
        </div>
        
        <div v-if="categories.links && categories.links.length > 0" class="py-4 px-6 flex items-center justify-between border-t border-secondary-200 dark:border-slate-700">
          <div>
            Menampilkan {{ categories.from || 0 }} - {{ categories.to || 0 }} dari {{ categories.total }} data
          </div>
          <Pagination :links="categories.links" />
        </div>
      </div>
    </div>

    <!-- Dialog Konfirmasi Hapus -->
    <ConfirmationDialog
      :open="showDeleteDialog"
      @update:open="showDeleteDialog = $event"
      title="Konfirmasi Penghapusan"
      dangerMode
      :icon="Trash2"
      :loading="loading"
      confirmLabel="Hapus"
      @confirm="deleteCategory()"
    >
      <p class="mb-2 text-secondary-900 dark:text-secondary-200">Apakah Anda yakin ingin menghapus {{ categoryToDelete?.name }}?</p>
    </ConfirmationDialog>
  </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { SearchIcon, Edit, Eye, Pencil, PlusIcon, Trash, Trash2, MoreHorizontal, ChevronDownIcon } from 'lucide-vue-next';
import Pagination from '@/components/ui/pagination/Pagination.vue';
import { ref, computed } from 'vue';
import ConfirmationDialog from '@/components/ui/ConfirmationDialog.vue';

// Definisikan tipe
interface ExpenseCategory {
  id: number;
  name: string;
  description: string | null;
  color: string | null;
  is_active: boolean;
  expenses_count: number;
}

interface PaginationLink {
  url: string | null;
  label: string;
  active: boolean;
}

interface PaginatedData<T> {
  data: T[];
  links: PaginationLink[];
  current_page: number;
  from: number;
  to: number;
  total: number;
  last_page: number;
  per_page: number;
}

interface ExpenseCategoriesProps {
  categories: PaginatedData<ExpenseCategory>;
  filters: {
    search?: string;
    status?: string;
  };
}

// Definisikan props dengan tipe
const props = defineProps<ExpenseCategoriesProps>();

// Form untuk filter
const form = useForm({
  search: props.filters.search || '',
  status: props.filters.status || '',
});

// State untuk select status
const statusOptions = [
  { value: '', label: 'Semua Status' },
  { value: '1', label: 'Aktif' },
  { value: '0', label: 'Tidak Aktif' },
];

const selectedStatusLabel = computed(() => {
  const option = statusOptions.find(option => option.value === form.status);
  return option ? option.label : 'Semua Status';
});

// Submit form filter
const submit = () => {
  form.get(route('admin.expense-categories.index'), {
    preserveState: true,
    preserveScroll: true,
  });
};

// Reset filter
const resetFilters = () => {
  form.search = '';
  form.status = '';
  submit();
};

// State untuk dialog hapus
const categoryToDelete = ref<ExpenseCategory | null>(null);
const showDeleteDialog = ref(false);
const loading = ref(false);

// Fungsi untuk actions
const editCategory = (category: ExpenseCategory) => {
  router.visit(route('admin.expense-categories.edit', category.id));
};

const openDeleteDialog = (category: ExpenseCategory) => {
  categoryToDelete.value = category;
  showDeleteDialog.value = true;
};

const deleteCategory = () => {
  if (!categoryToDelete.value) return;
  
  loading.value = true;
  
  router.delete(route('admin.expense-categories.destroy', categoryToDelete.value.id), {
    preserveScroll: true,
    onSuccess: () => {
      // Notifikasi sukses akan ditampilkan oleh sistem
      showDeleteDialog.value = false;
      categoryToDelete.value = null;
    },
    onError: () => {
      // Notifikasi error akan ditampilkan oleh sistem
    },
    onFinish: () => {
      loading.value = false;
    }
  });
};

// Breadcrumbs untuk navigasi
const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Admin',
    href: route('admin.dashboard'),
  },
  {
    title: 'Expense Categories',
    href: route('admin.expense-categories.index'),
  },
];
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
</style> 