<template>
  <Head title="Edit Pengeluaran" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex flex-col gap-4 p-4 md:p-6 pb-12">
      <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-50">Edit Pengeluaran</h1>
      </div>

      <Card class="border border-slate-200 dark:border-slate-700">
        <CardContent class="p-6">
          <form @submit.prevent="submit" class="space-y-6">
            <!-- Grid 2 columns untuk desktop dan tablet, 1 column untuk mobile -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Kolom Kiri -->
              <div class="space-y-6">
                <!-- Nama Pengeluaran -->
                <div class="space-y-2">
                  <Label for="name">Nama Pengeluaran <span class="text-red-500">*</span></Label>
                  <Input 
                    id="name" 
                    v-model="form.name" 
                    :class="{ 'border-red-500': form.errors.name }"
                    placeholder="Masukkan nama pengeluaran" 
                  />
                  <div v-if="form.errors.name" class="text-red-500 text-sm mt-1">{{ form.errors.name }}</div>
                </div>

                <!-- Kategori -->
                <div class="space-y-2">
                  <Label for="expense_category_id">Kategori <span class="text-red-500">*</span></Label>
                  <select 
                    id="expense_category_id" 
                    v-model="form.expense_category_id"
                    :class="{ 'border-red-500': form.errors.expense_category_id, 'w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm ring-offset-white file:border-0 file:bg-transparent file:text-sm file:font-medium focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 dark:border-slate-800 dark:bg-slate-950 dark:ring-offset-slate-950 dark:placeholder:text-slate-400 dark:focus-visible:ring-primary-400': true }"
                  >
                    <option value="">Pilih Kategori</option>
                    <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}</option>
                  </select>
                  <div v-if="form.errors.expense_category_id" class="text-red-500 text-sm mt-1">{{ form.errors.expense_category_id }}</div>
                </div>

                <!-- Jumlah -->
                <div class="space-y-2">
                  <Label for="amount">Jumlah (Rp) <span class="text-red-500">*</span></Label>
                  <Input 
                    id="amount" 
                    v-model="form.amount" 
                    type="number"
                    step="0.01"
                    :class="{ 'border-red-500': form.errors.amount }"
                    placeholder="Masukkan jumlah pengeluaran" 
                  />
                  <div v-if="form.errors.amount" class="text-red-500 text-sm mt-1">{{ form.errors.amount }}</div>
                </div>

                <!-- Order -->
                <div class="space-y-2">
                  <Label for="order_id">Terkait Pesanan</Label>
                  <select 
                    id="order_id" 
                    v-model="form.order_id"
                    :class="{ 'border-red-500': form.errors.order_id, 'w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm ring-offset-white file:border-0 file:bg-transparent file:text-sm file:font-medium focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 dark:border-slate-800 dark:bg-slate-950 dark:ring-offset-slate-950 dark:placeholder:text-slate-400 dark:focus-visible:ring-primary-400': true }"
                  >
                    <option value="">Tidak Terkait Pesanan</option>
                    <option v-for="order in orders" :key="order.id" :value="order.id">{{ order.order_number }}</option>
                  </select>
                  <div v-if="form.errors.order_id" class="text-red-500 text-sm mt-1">{{ form.errors.order_id }}</div>
                </div>
              </div>

              <!-- Kolom Kanan -->
              <div class="space-y-6">
                <!-- Tanggal -->
                <div class="space-y-2">
                  <Label for="expense_date">Tanggal <span class="text-red-500">*</span></Label>
                  <Input 
                    id="expense_date" 
                    v-model="form.expense_date" 
                    type="date"
                    :class="{ 'border-red-500': form.errors.expense_date }"
                  />
                  <div v-if="form.errors.expense_date" class="text-red-500 text-sm mt-1">{{ form.errors.expense_date }}</div>
                </div>

                <!-- Metode Pembayaran -->
                <div class="space-y-2">
                  <Label for="payment_method">Metode Pembayaran <span class="text-red-500">*</span></Label>
                  <select 
                    id="payment_method" 
                    v-model="form.payment_method"
                    :class="{ 'border-red-500': form.errors.payment_method, 'w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm ring-offset-white file:border-0 file:bg-transparent file:text-sm file:font-medium focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 dark:border-slate-800 dark:bg-slate-950 dark:ring-offset-slate-950 dark:placeholder:text-slate-400 dark:focus-visible:ring-primary-400': true }"
                  >
                    <option value="cash">Tunai</option>
                    <option value="transfer">Transfer</option>
                    <option value="credit_card">Kartu Kredit</option>
                    <option value="other">Lainnya</option>
                  </select>
                  <div v-if="form.errors.payment_method" class="text-red-500 text-sm mt-1">{{ form.errors.payment_method }}</div>
                </div>

                <!-- Status -->
                <div class="space-y-2">
                  <Label for="status">Status <span class="text-red-500">*</span></Label>
                  <select 
                    id="status" 
                    v-model="form.status"
                    :class="{ 'border-red-500': form.errors.status, 'w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm ring-offset-white file:border-0 file:bg-transparent file:text-sm file:font-medium focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 dark:border-slate-800 dark:bg-slate-950 dark:ring-offset-slate-950 dark:placeholder:text-slate-400 dark:focus-visible:ring-primary-400': true }"
                  >
                    <option value="pending">Pending</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                  </select>
                  <div v-if="form.errors.status" class="text-red-500 text-sm mt-1">{{ form.errors.status }}</div>
                </div>

                <!-- Bukti Pembayaran -->
                <div class="space-y-2">
                  <Label for="receipt_image">Bukti Pembayaran</Label>
                  <div v-if="expense.receipt_image" class="mb-2">
                    <img :src="'/storage/' + expense.receipt_image" class="w-24 h-24 object-cover rounded" />
                    <p class="text-xs text-slate-500 mt-1">Gambar saat ini</p>
                  </div>
                  <Input 
                    id="receipt_image" 
                    type="file"
                    @input="form.receipt_image = $event.target.files[0]"
                    :class="{ 'border-red-500': form.errors.receipt_image }"
                  />
                  <div v-if="form.errors.receipt_image" class="text-red-500 text-sm mt-1">{{ form.errors.receipt_image }}</div>
                </div>
              </div>
            </div>

            <!-- Deskripsi (full width) -->
            <div class="space-y-2">
              <Label for="description">Deskripsi</Label>
              <Textarea 
                id="description" 
                v-model="form.description" 
                rows="4"
                :class="{ 'border-red-500': form.errors.description }"
                placeholder="Masukkan deskripsi pengeluaran (opsional)" 
              />
              <div v-if="form.errors.description" class="text-red-500 text-sm mt-1">{{ form.errors.description }}</div>
            </div>

            <div class="flex gap-2">
              <Button type="submit" :disabled="form.processing">{{ form.processing ? 'Menyimpan...' : 'Simpan' }}</Button>
              <Link :href="route('admin.expenses.index')">
                <Button type="button" variant="outline">Batal</Button>
              </Link>
            </div>
          </form>
        </CardContent>
      </Card>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Card, CardContent } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';

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
}

interface ExpenseEditProps {
  expense: Expense;
  categories: Category[];
  orders: Order[];
}

// Definisikan props dengan tipe
const props = defineProps<ExpenseEditProps>();

// Form untuk expense
const form = useForm({
  name: props.expense.name,
  amount: props.expense.amount,
  expense_date: props.expense.expense_date,
  expense_category_id: props.expense.expense_category_id,
  order_id: props.expense.order_id || '',
  description: props.expense.description || '',
  receipt_image: null as File | null,
  payment_method: props.expense.payment_method,
  status: props.expense.status,
  _method: 'PUT'
});

// Submit form
const submit = () => {
  form.post(route('admin.expenses.update', props.expense.id), {
    preserveScroll: true,
  });
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
  {
    title: 'Edit',
    href: route('admin.expenses.edit', props.expense.id),
  },
];
</script> 