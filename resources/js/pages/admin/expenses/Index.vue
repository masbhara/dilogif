<template>
  <Head title="Daftar Pengeluaran" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex flex-col gap-4 p-4 md:p-6 pb-12">
      <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-50">Daftar Pengeluaran</h1>
        <div class="flex gap-2">
          <Link 
            :href="route('admin.expense-categories.index')" 
            class="inline-flex items-center justify-center rounded-md text-sm font-medium h-10 px-4 py-2 bg-slate-100 text-slate-900 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-50 dark:hover:bg-slate-700"
          >
            <Receipt class="mr-2 h-4 w-4" />
            Kategori
          </Link>
          <Link 
            :href="route('admin.expenses.create')" 
            class="inline-flex items-center justify-center rounded-md text-sm font-medium h-10 px-4 py-2 bg-primary-600 text-white shadow-sm hover:bg-primary-500 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500"
          >
            <PlusIcon class="mr-2 h-4 w-4" />
            Tambah Pengeluaran
          </Link>
        </div>
      </div>

      <!-- Filter dan pencarian -->
      <Card class="border border-slate-200 dark:border-slate-700">
        <CardContent class="p-4">
          <form @submit.prevent="submit" class="grid gap-4 sm:grid-cols-2 md:grid-cols-4">
            <div class="space-y-2">
              <Label for="search">Cari</Label>
              <Input 
                id="search" 
                v-model="form.search" 
                placeholder="Cari nama atau deskripsi" 
                class="w-full" 
              />
            </div>
            <div class="space-y-2">
              <Label for="category">Kategori</Label>
              <div class="relative custom-select-container category-select-container" :class="{ 'active': isCategorySelectOpen }">
                <div 
                  @click="toggleCategorySelect" 
                  class="custom-select-trigger flex w-full items-center justify-between gap-2 rounded-md border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-3 py-2 text-sm shadow-sm hover:border-slate-300 dark:hover:border-slate-600 focus:outline-none focus:ring-0 disabled:cursor-not-allowed disabled:opacity-50 cursor-pointer h-9"
                >
                  <span>{{ selectedCategoryLabel }}</span>
                  <ChevronDownIcon class="h-4 w-4 opacity-50 transition-transform" :class="{ 'rotate-180': isCategorySelectOpen }" />
                </div>
                
                <div 
                  v-if="isCategorySelectOpen" 
                  class="custom-select-dropdown bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-md shadow-lg mt-1 overflow-hidden z-50"
                >
                  <div 
                    class="custom-select-option py-2 px-3 text-slate-900 dark:text-slate-100 hover:bg-slate-50 dark:hover:bg-slate-700 cursor-pointer text-sm"
                    @click="selectCategoryString('')"
                    :class="{ 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-300 font-medium': form.category_id === '' }"
                  >
                    Semua Kategori
                  </div>
                  <div 
                    v-for="category in categories" 
                    :key="category.id"
                    @click="selectCategoryNumber(category.id)"
                    class="custom-select-option py-2 px-3 text-slate-900 dark:text-slate-100 hover:bg-slate-50 dark:hover:bg-slate-700 cursor-pointer text-sm"
                    :class="{ 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-300 font-medium': form.category_id === category.id }"
                  >
                    {{ category.name }}
                  </div>
                </div>
              </div>
            </div>
            <div class="space-y-2">
              <Label for="status">Status</Label>
              <div class="relative custom-select-container status-select-container" :class="{ 'active': isStatusSelectOpen }">
                <div 
                  @click="toggleStatusSelect" 
                  class="custom-select-trigger flex w-full items-center justify-between gap-2 rounded-md border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-3 py-2 text-sm shadow-sm hover:border-slate-300 dark:hover:border-slate-600 focus:outline-none focus:ring-0 disabled:cursor-not-allowed disabled:opacity-50 cursor-pointer h-9"
                >
                  <span>{{ selectedStatusLabel }}</span>
                  <ChevronDownIcon class="h-4 w-4 opacity-50 transition-transform" :class="{ 'rotate-180': isStatusSelectOpen }" />
                </div>
                
                <div 
                  v-if="isStatusSelectOpen" 
                  class="custom-select-dropdown bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-md shadow-lg mt-1 overflow-hidden z-50"
                >
                  <div 
                    class="custom-select-option py-2 px-3 text-slate-900 dark:text-slate-100 hover:bg-slate-50 dark:hover:bg-slate-700 cursor-pointer text-sm"
                    @click="selectStatus('')"
                    :class="{ 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-300 font-medium': form.status === '' }"
                  >
                    Semua Status
                  </div>
                  <div 
                    class="custom-select-option py-2 px-3 text-slate-900 dark:text-slate-100 hover:bg-slate-50 dark:hover:bg-slate-700 cursor-pointer text-sm"
                    @click="selectStatus('pending')"
                    :class="{ 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-300 font-medium': form.status === 'pending' }"
                  >
                    Pending
                  </div>
                  <div 
                    class="custom-select-option py-2 px-3 text-slate-900 dark:text-slate-100 hover:bg-slate-50 dark:hover:bg-slate-700 cursor-pointer text-sm"
                    @click="selectStatus('completed')"
                    :class="{ 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-300 font-medium': form.status === 'completed' }"
                  >
                    Completed
                  </div>
                  <div 
                    class="custom-select-option py-2 px-3 text-slate-900 dark:text-slate-100 hover:bg-slate-50 dark:hover:bg-slate-700 cursor-pointer text-sm"
                    @click="selectStatus('cancelled')"
                    :class="{ 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-300 font-medium': form.status === 'cancelled' }"
                  >
                    Cancelled
                  </div>
                </div>
              </div>
            </div>
            <div class="flex gap-2 items-end">
              <Button type="submit" class="h-10">Filter</Button>
              <Button variant="outline" @click="resetFilters" class="h-10">Reset</Button>
            </div>
          </form>
        </CardContent>
      </Card>

      <!-- Daftar Pengeluaran -->
      <Card class="border border-slate-200 dark:border-slate-700">
        <CardContent class="p-0">
          <div class="relative overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
              <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                  <th scope="col" class="px-6 py-3">Nama</th>
                  <th scope="col" class="px-6 py-3">Kategori</th>
                  <th scope="col" class="px-6 py-3">Jumlah</th>
                  <th scope="col" class="px-6 py-3">Tanggal</th>
                  <th scope="col" class="px-6 py-3">Status</th>
                  <th scope="col" class="px-6 py-3">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="expense in expenses.data" :key="expense.id" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                  <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    {{ expense.name }}
                  </td>
                  <td class="px-6 py-4">{{ expense.category?.name || '-' }}</td>
                  <td class="px-6 py-4">Rp {{ formatCurrency(expense.amount) }}</td>
                  <td class="px-6 py-4">{{ formatDate(expense.expense_date) }}</td>
                  <td class="px-6 py-4">
                    <span 
                      class="px-2 py-1 rounded-full text-xs"
                      :class="{
                        'bg-yellow-100 text-yellow-800': expense.status === 'pending',
                        'bg-green-100 text-green-800': expense.status === 'completed',
                        'bg-red-100 text-red-800': expense.status === 'cancelled',
                      }"
                    >
                      {{ expense.status }}
                    </span>
                  </td>
                  <td class="px-6 py-4 flex gap-2">
                    <Link
                      :href="route('admin.expenses.show', expense.id)"
                      class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300"
                    >
                      <Eye class="h-5 w-5" />
                    </Link>
                    <Link
                      :href="route('admin.expenses.edit', expense.id)"
                      class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                    >
                      <Edit class="h-5 w-5" />
                    </Link>
                    <button
                      @click="confirmDelete(expense)"
                      class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                    >
                      <Trash class="h-5 w-5" />
                    </button>
                  </td>
                </tr>
                <tr v-if="expenses.data.length === 0">
                  <td colspan="6" class="px-6 py-4 text-center">Tidak ada data pengeluaran</td>
                </tr>
              </tbody>
            </table>
          </div>
        </CardContent>
        <CardFooter class="p-4 flex justify-between items-center">
          <div>
            Menampilkan {{ expenses.from || 0 }} - {{ expenses.to || 0 }} dari {{ expenses.total }} data
          </div>
          <Pagination :links="expenses.links" />
        </CardFooter>
      </Card>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Card, CardContent, CardFooter } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { Edit, Eye, PlusIcon, Trash, Receipt, ChevronDownIcon } from 'lucide-vue-next';
import Pagination from '@/components/Pagination.vue';
import { ref } from 'vue';

// Definisikan tipe
interface Category {
  id: number;
  name: string;
}

interface Order {
  id: number;
  order_number: string;
}

interface Expense {
  id: number;
  name: string;
  amount: number;
  expense_date: string;
  expense_category_id: number;
  order_id: number | null;
  user_id: number;
  description: string | null;
  receipt_image: string | null;
  payment_method: 'cash' | 'transfer' | 'credit_card' | 'other';
  status: 'pending' | 'completed' | 'cancelled';
  category: Category | null;
  order: Order | null;
  user: {
    id: number;
    name: string;
  };
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

interface ExpensesProps {
  expenses: PaginatedData<Expense>;
  categories: Category[];
  orders: Order[];
  filters: {
    search?: string;
    category_id?: string;
    status?: string;
    date_from?: string;
    date_to?: string;
    order_id?: string;
  };
}

// Definisikan props dengan tipe
const props = defineProps<ExpensesProps>();

// Form untuk filter
const form = useForm({
  search: props.filters.search || '',
  category_id: props.filters.category_id || '',
  status: props.filters.status || '',
  date_from: props.filters.date_from || '',
  date_to: props.filters.date_to || '',
  order_id: props.filters.order_id || '',
});

// Submit form filter
const submit = () => {
  form.get(route('admin.expenses.index'), {
    preserveState: true,
    preserveScroll: true,
  });
};

// Reset filter
const resetFilters = () => {
  form.search = '';
  form.category_id = '';
  form.status = '';
  form.date_from = '';
  form.date_to = '';
  form.order_id = '';
  submit();
};

// Konfirmasi hapus
const expenseToDelete = ref<Expense | null>(null);

const confirmDelete = (expense: Expense) => {
  if (confirm(`Apakah Anda yakin ingin menghapus pengeluaran "${expense.name}"?`)) {
    useForm({}).delete(route('admin.expenses.destroy', expense.id), {
      preserveScroll: true,
      onSuccess: () => {
        expenseToDelete.value = null;
      },
    });
  }
};

// Breadcrumbs untuk navigasi
const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Admin',
    href: route('admin.dashboard'),
  },
  {
    title: 'Expenses',
    href: route('admin.expenses.index'),
  },
];

// Format currency
const formatCurrency = (value: number): string => {
  return new Intl.NumberFormat('id-ID').format(value);
};

// Format date
const formatDate = (dateString: string): string => {
  const date = new Date(dateString);
  return new Intl.DateTimeFormat('id-ID', {
    day: 'numeric',
    month: 'long',
    year: 'numeric',
  }).format(date);
};

// New refs for custom select
const isCategorySelectOpen = ref(false);
const isStatusSelectOpen = ref(false);
const selectedCategoryLabel = ref('');
const selectedStatusLabel = ref('');

// Functions for custom select
const toggleCategorySelect = () => {
  isCategorySelectOpen.value = !isCategorySelectOpen.value;
};

const toggleStatusSelect = () => {
  isStatusSelectOpen.value = !isStatusSelectOpen.value;
};

// Inisialisasi label
selectedCategoryLabel.value = 'Semua Kategori';
selectedStatusLabel.value = 'Semua Status';

// Fungsi select untuk kategori dengan string (untuk opsi "")
const selectCategoryString = (id: string) => {
  form.category_id = id;
  isCategorySelectOpen.value = false;
  selectedCategoryLabel.value = 'Semua Kategori';
};

// Fungsi select untuk kategori dengan number (untuk opsi kategori)
const selectCategoryNumber = (id: number) => {
  form.category_id = id.toString();
  isCategorySelectOpen.value = false;
  selectedCategoryLabel.value = props.categories.find(c => c.id === id)?.name || '';
};

// Fungsi untuk status
const selectStatus = (status: string) => {
  form.status = status;
  isStatusSelectOpen.value = false;
  selectedStatusLabel.value = status === '' ? 'Semua Status' : status === 'pending' ? 'Pending' : status === 'completed' ? 'Completed' : 'Cancelled';
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