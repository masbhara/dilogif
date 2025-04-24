<template>
  <Head title="Kategori Pengeluaran" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex flex-col gap-4 p-4 md:p-6 pb-12">
      <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-50">Kategori Pengeluaran</h1>
        <Link 
          :href="route('admin.expense-categories.create')" 
          class="inline-flex items-center justify-center rounded-md text-sm font-medium h-10 px-4 py-2 bg-primary-600 text-white shadow-sm hover:bg-primary-500 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500"
        >
          <PlusIcon class="mr-2 h-4 w-4" />
          Tambah Kategori
        </Link>
      </div>

      <!-- Filter dan pencarian -->
      <Card class="border border-slate-200 dark:border-slate-700">
        <CardContent class="p-4">
          <form @submit.prevent="submit" class="grid gap-4 sm:grid-cols-2 md:grid-cols-3">
            <div class="space-y-2">
              <Label for="search">Cari</Label>
              <Input 
                id="search" 
                v-model="form.search" 
                placeholder="Cari nama kategori" 
                class="w-full" 
              />
            </div>
            <div class="space-y-2">
              <Label for="status">Status</Label>
              <select id="status" v-model="form.status" class="w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm ring-offset-white file:border-0 file:bg-transparent file:text-sm file:font-medium focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 dark:border-slate-800 dark:bg-slate-950 dark:ring-offset-slate-950 dark:placeholder:text-slate-400 dark:focus-visible:ring-primary-400">
                <option value="">Semua Status</option>
                <option value="1">Aktif</option>
                <option value="0">Tidak Aktif</option>
              </select>
            </div>
            <div class="flex gap-2 items-end">
              <Button type="submit" class="h-10">Filter</Button>
              <Button variant="outline" @click="resetFilters" class="h-10">Reset</Button>
            </div>
          </form>
        </CardContent>
      </Card>

      <!-- Daftar Kategori Pengeluaran -->
      <Card class="border border-slate-200 dark:border-slate-700">
        <CardContent class="p-0">
          <div class="relative overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
              <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                  <th scope="col" class="px-6 py-3">Nama</th>
                  <th scope="col" class="px-6 py-3">Deskripsi</th>
                  <th scope="col" class="px-6 py-3">Warna</th>
                  <th scope="col" class="px-6 py-3">Jumlah Pengeluaran</th>
                  <th scope="col" class="px-6 py-3">Status</th>
                  <th scope="col" class="px-6 py-3">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="category in categories.data" :key="category.id" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                  <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    {{ category.name }}
                  </td>
                  <td class="px-6 py-4">{{ category.description || '-' }}</td>
                  <td class="px-6 py-4">
                    <div v-if="category.color" class="w-6 h-6 rounded" :style="{ backgroundColor: category.color }"></div>
                    <span v-else>-</span>
                  </td>
                  <td class="px-6 py-4">{{ category.expenses_count }}</td>
                  <td class="px-6 py-4">
                    <span 
                      class="px-2 py-1 rounded-full text-xs"
                      :class="{
                        'bg-green-100 text-green-800': category.is_active,
                        'bg-red-100 text-red-800': !category.is_active,
                      }"
                    >
                      {{ category.is_active ? 'Aktif' : 'Tidak Aktif' }}
                    </span>
                  </td>
                  <td class="px-6 py-4 flex gap-2">
                    <Link
                      :href="route('admin.expense-categories.show', category.id)"
                      class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300"
                    >
                      <Eye class="h-5 w-5" />
                    </Link>
                    <Link
                      :href="route('admin.expense-categories.edit', category.id)"
                      class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                    >
                      <Edit class="h-5 w-5" />
                    </Link>
                    <button
                      v-if="category.expenses_count === 0"
                      @click="confirmDelete(category)"
                      class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                    >
                      <Trash class="h-5 w-5" />
                    </button>
                  </td>
                </tr>
                <tr v-if="categories.data.length === 0">
                  <td colspan="6" class="px-6 py-4 text-center">Tidak ada data kategori pengeluaran</td>
                </tr>
              </tbody>
            </table>
          </div>
        </CardContent>
        <CardFooter class="p-4 flex justify-between items-center">
          <div>
            Menampilkan {{ categories.from || 0 }} - {{ categories.to || 0 }} dari {{ categories.total }} data
          </div>
          <Pagination :links="categories.links" />
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
import { Edit, Eye, PlusIcon, Trash } from 'lucide-vue-next';
import Pagination from '@/components/Pagination.vue';
import { ref } from 'vue';

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

// Konfirmasi hapus
const categoryToDelete = ref<ExpenseCategory | null>(null);

const confirmDelete = (category: ExpenseCategory) => {
  if (confirm(`Apakah Anda yakin ingin menghapus kategori "${category.name}"?`)) {
    useForm({}).delete(route('admin.expense-categories.destroy', category.id), {
      preserveScroll: true,
      onSuccess: () => {
        categoryToDelete.value = null;
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
    title: 'Expense Categories',
    href: route('admin.expense-categories.index'),
  },
];
</script> 