<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-6 space-y-6">
      <PageHeader
        title="Tambah Kupon Baru"
        description="Buat kupon diskon baru untuk diterapkan pada checkout"
      />

      <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <div class="lg:col-span-2 space-y-6">
          <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
            <div class="p-6 space-y-6">
              <form @submit.prevent="submitForm">
                <!-- Info Dasar -->
                <div class="space-y-4">
                  <h3 class="text-lg font-medium">Informasi Kupon</h3>
                  
                  <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                      <Label for="code">Kode Kupon <span class="text-red-500">*</span></Label>
                      <Input 
                        id="code" 
                        v-model="form.code" 
                        placeholder="DISKON20"
                        class="uppercase"
                        @blur="form.code = form.code.toUpperCase()"
                      />
                      <InputError :message="errors.code" />
                    </div>
                    
                    <div class="md:col-span-2">
                      <Label for="description">Deskripsi</Label>
                      <Textarea 
                        id="description" 
                        v-model="form.description" 
                        placeholder="Deskripsi singkat tentang kupon ini"
                        rows="3"
                      />
                      <InputError :message="errors.description" />
                    </div>
                  </div>
                </div>
                
                <Separator class="my-6" />
                
                <!-- Nilai Diskon -->
                <div class="space-y-4">
                  <h3 class="text-lg font-medium">Nilai Diskon</h3>
                  
                  <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                      <Label for="type">Tipe Diskon <span class="text-red-500">*</span></Label>
                      <Select v-model="form.type" id="type">
                        <option value="fixed">Nominal Tetap (Rp)</option>
                        <option value="percentage">Persentase (%)</option>
                      </Select>
                      <InputError :message="errors.type" />
                    </div>
                    
                    <div>
                      <Label for="value">Nilai Diskon <span class="text-red-500">*</span></Label>
                      <div class="relative">
                        <div v-if="form.type === 'fixed'" class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                          <span class="text-gray-500">Rp</span>
                        </div>
                        <div v-else class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                          <span class="text-gray-500">%</span>
                        </div>
                        <Input 
                          id="value" 
                          v-model="form.value" 
                          type="number" 
                          :class="{ 'pl-10': form.type === 'fixed', 'pr-8': form.type === 'percentage' }"
                          :min="0"
                          :max="form.type === 'percentage' ? 100 : null"
                          step="0.01"
                        />
                      </div>
                      <InputError :message="errors.value" />
                      <p v-if="form.type === 'percentage' && Number(form.value) > 100" class="text-xs text-red-500 mt-1">
                        Nilai persentase tidak boleh lebih dari 100%
                      </p>
                    </div>
                    
                    <div>
                      <Label for="min_purchase">Minimal Pembelian <span class="text-red-500">*</span></Label>
                      <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                          <span class="text-gray-500">Rp</span>
                        </div>
                        <Input 
                          id="min_purchase" 
                          v-model="form.min_purchase" 
                          type="number" 
                          class="pl-10"
                          min="0"
                          step="1000"
                        />
                      </div>
                      <InputError :message="errors.min_purchase" />
                    </div>
                    
                    <div>
                      <Label for="max_discount">Maksimum Diskon</Label>
                      <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                          <span class="text-gray-500">Rp</span>
                        </div>
                        <Input 
                          id="max_discount" 
                          v-model="form.max_discount" 
                          type="number" 
                          class="pl-10"
                          min="0"
                          step="1000"
                        />
                      </div>
                      <InputError :message="errors.max_discount" />
                      <p class="text-xs text-muted-foreground mt-1">
                        Kosongkan jika tidak ada batas maksimum diskon
                      </p>
                    </div>
                  </div>
                </div>
                
                <Separator class="my-6" />
                
                <!-- Batasan Penggunaan -->
                <div class="space-y-4">
                  <h3 class="text-lg font-medium">Batasan Penggunaan</h3>
                  
                  <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                      <Label for="max_uses">Maksimum Penggunaan</Label>
                      <div class="flex items-center gap-2">
                        <Input 
                          id="max_uses" 
                          v-model="form.max_uses" 
                          type="number" 
                          min="0"
                          step="1"
                          class="flex-1"
                        />
                        <p class="text-sm text-muted-foreground whitespace-nowrap">(0 = tidak terbatas)</p>
                      </div>
                      <InputError :message="errors.max_uses" />
                    </div>
                    
                    <div class="flex items-center h-full pt-6">
                      <div class="flex items-center space-x-2">
                        <Checkbox id="is_active" v-model:checked="form.is_active" />
                        <Label for="is_active">Aktifkan Kupon</Label>
                      </div>
                      <InputError :message="errors.is_active" />
                    </div>
                  </div>
                </div>
                
                <Separator class="my-6" />
                
                <!-- Periode Kupon -->
                <div class="space-y-4">
                  <h3 class="text-lg font-medium">Periode Kupon</h3>
                  
                  <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                      <Label for="starts_at">Berlaku Dari</Label>
                      <Input 
                        id="starts_at" 
                        v-model="form.starts_at" 
                        type="datetime-local"
                      />
                      <InputError :message="errors.starts_at" />
                    </div>
                    
                    <div>
                      <Label for="expires_at">Berlaku Sampai</Label>
                      <Input 
                        id="expires_at" 
                        v-model="form.expires_at" 
                        type="datetime-local"
                        :min="form.starts_at"
                      />
                      <InputError :message="errors.expires_at" />
                    </div>
                  </div>
                </div>
                
                <div class="mt-6 flex justify-end gap-3">
                  <Button variant="outline" type="button" @click="cancel">
                    Batal
                  </Button>
                  <Button type="submit" :disabled="processing">
                    <Loader2Icon v-if="processing" class="mr-2 h-4 w-4 animate-spin" />
                    Simpan
                  </Button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import PageHeader from '@/components/ui/page-header/PageHeader.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Checkbox } from '@/components/ui/checkbox';
import { Button } from '@/components/ui/button';
import { Select } from '@/components/ui/select';
import { Textarea } from '@/components/ui/textarea';
import { Separator } from '@/components/ui/separator';
import { Loader2Icon } from 'lucide-vue-next';
import InputError from '@/components/InputError.vue';
import { toast } from '@/lib/toast';

const breadcrumbs = [
  { label: 'Dashboard', href: route('admin.dashboard') },
  { label: 'Manajemen Kupon', href: route('admin.coupons.index') },
  { label: 'Tambah Kupon', href: route('admin.coupons.create') },
];

const form = useForm({
  code: '',
  description: '',
  type: 'fixed',
  value: 0,
  min_purchase: 0,
  max_discount: null,
  max_uses: 0,
  is_active: true,
  starts_at: '',
  expires_at: '',
});

const processing = ref(false);
const errors = ref<Record<string, string>>({});

const submitForm = () => {
  // Validasi tambahan untuk persentase
  if (form.type === 'percentage' && Number(form.value) > 100) {
    errors.value.value = 'Persentase diskon tidak boleh lebih dari 100%';
    return;
  }
  
  processing.value = true;
  errors.value = {};
  
  form.post(route('admin.coupons.store'), {
    preserveScroll: true,
    onSuccess: () => {
      toast.success('Kupon berhasil dibuat');
      router.visit(route('admin.coupons.index'));
    },
    onError: (err) => {
      errors.value = err;
      processing.value = false;
    },
    onFinish: () => {
      processing.value = false;
    }
  });
};

const cancel = () => {
  router.visit(route('admin.coupons.index'));
};
</script> 