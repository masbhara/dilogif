<template>
  <Head title="Template WhatsApp" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 p-4 md:p-6">
      <!-- Header dengan judul dan tombol tambah -->
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h1 class="text-2xl font-bold text-secondary-900 dark:text-white">Template WhatsApp</h1>
        <div class="flex gap-2">
          <Button 
            variant="action" 
            @click="openCreateModal"
            class="flex items-center gap-1.5 w-full sm:w-auto cursor-pointer">
            <PlusIcon class="h-4 w-4" />
            Tambah Template
          </Button>
        </div>
      </div>
      
      <!-- Template Pelanggan -->
      <div class="bg-white dark:bg-slate-800 p-4 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700">
        <h2 class="text-lg font-medium text-secondary-900 dark:text-white">Template Pesan untuk Pelanggan</h2>
        <p class="mt-1 text-sm text-gray-600 mb-4">
          Template ini digunakan untuk mengirim notifikasi WhatsApp ke pelanggan berdasarkan status pesanan.
        </p>
        
        <!-- Tabel Template Pelanggan -->
        <AdminTable 
          :columns="customerColumns" 
          :data="{ data: customerTemplates }" 
          :loading="loading"
          emptyMessage="Tidak ada template ditemukan"
        >
          <TableRow v-for="template in customerTemplates" :key="template.id" class="hover:bg-secondary-100/50 dark:hover:bg-slate-900/90">
            <TableCell class="py-3.5 px-6 align-middle font-medium text-secondary-900 dark:text-white">{{ template.name }}</TableCell>
            <TableCell class="py-3.5 px-6 align-middle">{{ getTriggerStatusLabel(template.trigger_status) }}</TableCell>
            <TableCell class="py-3.5 px-6 align-middle">
              <div class="max-h-24 overflow-y-auto">
                <pre class="whitespace-pre-wrap text-sm text-gray-500">{{ template.message_template }}</pre>
              </div>
            </TableCell>
            <TableCell class="py-3.5 px-6 align-middle">
              <StatusBadge 
                :status="template.is_active ? '1' : '0'" 
                :statusMap="statusMap"
              />
            </TableCell>
            <TableCell class="py-3.5 px-6 align-middle text-right">
              <div class="flex justify-end">
                <DropdownMenu>
                  <DropdownMenuTrigger asChild>
                    <Button variant="action" size="icon" class="h-8 w-8 rounded-md">
                      <MoreHorizontal class="h-4 w-4" />
                      <span class="sr-only">Menu</span>
                    </Button>
                  </DropdownMenuTrigger>
                  <DropdownMenuContent align="end" class="w-[160px]">
                    <DropdownMenuItem @click="openEditModal(template)" class="flex items-center gap-2 cursor-pointer py-1.5">
                      <Pencil class="h-4 w-4" />
                      <span>Edit</span>
                    </DropdownMenuItem>
                    <DropdownMenuItem 
                      @click="toggleActive(template)" 
                      class="flex items-center gap-2 cursor-pointer py-1.5">
                      <Power class="h-4 w-4" />
                      <span>{{ template.is_active ? 'Nonaktifkan' : 'Aktifkan' }}</span>
                    </DropdownMenuItem>
                    <DropdownMenuItem 
                      @click="confirmDelete(template)" 
                      variant="destructive" 
                      class="flex items-center gap-2 cursor-pointer py-1.5">
                      <Trash class="h-4 w-4" />
                      <span>Hapus</span>
                    </DropdownMenuItem>
                  </DropdownMenuContent>
                </DropdownMenu>
              </div>
            </TableCell>
          </TableRow>
        </AdminTable>
      </div>
      
      <!-- Template Admin -->
      <div class="bg-white dark:bg-slate-800 p-4 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700">
        <h2 class="text-lg font-medium text-secondary-900 dark:text-white">Template Pesan untuk Admin</h2>
        <p class="mt-1 text-sm text-gray-600 mb-4">
          Template ini digunakan untuk mengirim notifikasi WhatsApp ke admin berdasarkan status pesanan.
        </p>
        
        <!-- Tabel Template Admin -->
        <AdminTable 
          :columns="adminColumns" 
          :data="{ data: adminTemplates }" 
          :loading="loading"
          emptyMessage="Tidak ada template ditemukan"
        >
          <TableRow v-for="template in adminTemplates" :key="template.id" class="hover:bg-secondary-100/50 dark:hover:bg-slate-900/90">
            <TableCell class="py-3.5 px-6 align-middle font-medium text-secondary-900 dark:text-white">{{ template.name }}</TableCell>
            <TableCell class="py-3.5 px-6 align-middle">{{ getTriggerStatusLabel(template.trigger_status) }}</TableCell>
            <TableCell class="py-3.5 px-6 align-middle">
              <div class="max-h-24 overflow-y-auto">
                <pre class="whitespace-pre-wrap text-sm text-gray-500">{{ template.message_template }}</pre>
              </div>
            </TableCell>
            <TableCell class="py-3.5 px-6 align-middle">
              <StatusBadge 
                :status="template.is_active ? '1' : '0'" 
                :statusMap="statusMap"
              />
            </TableCell>
            <TableCell class="py-3.5 px-6 align-middle text-right">
              <div class="flex justify-end">
                <DropdownMenu>
                  <DropdownMenuTrigger asChild>
                    <Button variant="action" size="icon" class="h-8 w-8 rounded-md">
                      <MoreHorizontal class="h-4 w-4" />
                      <span class="sr-only">Menu</span>
                    </Button>
                  </DropdownMenuTrigger>
                  <DropdownMenuContent align="end" class="w-[160px]">
                    <DropdownMenuItem @click="openEditModal(template)" class="flex items-center gap-2 cursor-pointer py-1.5">
                      <Pencil class="h-4 w-4" />
                      <span>Edit</span>
                    </DropdownMenuItem>
                    <DropdownMenuItem 
                      @click="toggleActive(template)" 
                      class="flex items-center gap-2 cursor-pointer py-1.5">
                      <Power class="h-4 w-4" />
                      <span>{{ template.is_active ? 'Nonaktifkan' : 'Aktifkan' }}</span>
                    </DropdownMenuItem>
                    <DropdownMenuItem 
                      @click="confirmDelete(template)" 
                      variant="destructive" 
                      class="flex items-center gap-2 cursor-pointer py-1.5">
                      <Trash class="h-4 w-4" />
                      <span>Hapus</span>
                    </DropdownMenuItem>
                  </DropdownMenuContent>
                </DropdownMenu>
              </div>
            </TableCell>
          </TableRow>
        </AdminTable>
      </div>
    </div>

    <!-- Modal Edit Template -->
    <Dialog
      :open="showEditModal"
      @update:open="closeEditModal"
    >
      <DialogContent class="sm:max-w-[500px]">
        <DialogHeader>
          <DialogTitle>Edit Template WhatsApp</DialogTitle>
        </DialogHeader>
        <form @submit.prevent="submitEdit">
          <div class="p-6">
            <div class="mb-4">
              <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Template</label>
              <input type="text" id="name" v-model="form.name" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-800 dark:text-white">
            </div>

            <div class="mb-4">
              <label for="message_template" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Template Pesan</label>
              <textarea id="message_template" v-model="form.message_template" rows="10" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-800 dark:text-white"></textarea>
              <div class="mt-2">
                <p class="text-sm text-gray-500 dark:text-gray-400">Variabel yang tersedia:</p>
                <div class="mt-1 grid grid-cols-2 gap-2">
                  <div v-for="(description, variable) in availableVariables" :key="variable" class="text-xs bg-gray-100 dark:bg-gray-700 p-1 rounded">
                    <code>{{ variable }}</code> - {{ description }}
                  </div>
                </div>
              </div>
            </div>

            <div class="mb-4">
              <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deskripsi (Opsional)</label>
              <textarea id="description" v-model="form.description" rows="2" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-800 dark:text-white"></textarea>
            </div>

            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
              <div class="mt-2">
                <div class="flex items-center">
                  <input type="checkbox" id="is_active" v-model="form.is_active" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                  <label for="is_active" class="ml-2 block text-sm text-gray-900 dark:text-gray-100">
                    Aktif
                  </label>
                </div>
              </div>
            </div>

            <div class="mb-4">
              <label for="order" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Urutan</label>
              <input type="number" id="order" v-model="form.order" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-800 dark:text-white">
            </div>
          </div>

          <div class="flex items-center justify-end space-x-2 px-6 py-4 bg-gray-50 dark:bg-gray-800">
            <Button type="button" variant="outline" @click="closeEditModal">
              Batal
            </Button>
            <Button type="submit" variant="action">
              Simpan
            </Button>
          </div>
        </form>
      </DialogContent>
    </Dialog>

    <!-- Modal Create Template -->
    <Dialog
      :open="showCreateModal"
      @update:open="closeCreateModal"
    >
      <DialogContent class="sm:max-w-[500px]">
        <DialogHeader>
          <DialogTitle>Tambah Template WhatsApp Baru</DialogTitle>
        </DialogHeader>
        <form @submit.prevent="submitCreate">
          <div class="p-6">
            <div class="mb-4">
              <label for="create_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Template</label>
              <input type="text" id="create_name" v-model="createForm.name" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-800 dark:text-white">
            </div>

            <div class="mb-4">
              <label for="create_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipe Template</label>
              <select id="create_type" v-model="createForm.type" class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:text-white">
                <option value="customer">Template Pelanggan</option>
                <option value="admin">Template Admin</option>
              </select>
            </div>

            <div class="mb-4">
              <label for="create_trigger_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status Trigger</label>
              <select id="create_trigger_status" v-model="createForm.trigger_status" class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:text-white">
                <option v-for="(label, value) in triggerStatusOptions" :key="value" :value="value">
                  {{ label }}
                </option>
              </select>
            </div>

            <div class="mb-4">
              <label for="create_message_template" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Template Pesan</label>
              <textarea id="create_message_template" v-model="createForm.message_template" rows="10" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-800 dark:text-white"></textarea>
              <div class="mt-2">
                <p class="text-sm text-gray-500 dark:text-gray-400">Variabel yang tersedia:</p>
                <div class="mt-1 grid grid-cols-2 gap-2">
                  <div v-for="(description, variable) in availableVariables" :key="variable" class="text-xs bg-gray-100 dark:bg-gray-700 p-1 rounded">
                    <code>{{ variable }}</code> - {{ description }}
                  </div>
                </div>
              </div>
            </div>

            <div class="mb-4">
              <label for="create_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deskripsi (Opsional)</label>
              <textarea id="create_description" v-model="createForm.description" rows="2" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-800 dark:text-white"></textarea>
            </div>

            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
              <div class="mt-2">
                <div class="flex items-center">
                  <input type="checkbox" id="create_is_active" v-model="createForm.is_active" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                  <label for="create_is_active" class="ml-2 block text-sm text-gray-900 dark:text-gray-100">
                    Aktif
                  </label>
                </div>
              </div>
            </div>

            <div class="mb-4">
              <label for="create_order" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Urutan</label>
              <input type="number" id="create_order" v-model="createForm.order" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-800 dark:text-white">
            </div>
          </div>

          <div class="flex items-center justify-end space-x-2 px-6 py-4 bg-gray-50 dark:bg-gray-800">
            <Button type="button" variant="outline" @click="closeCreateModal">
              Batal
            </Button>
            <Button type="submit" variant="action">
              Simpan
            </Button>
          </div>
        </form>
      </DialogContent>
    </Dialog>

    <!-- Dialog Konfirmasi Hapus -->
    <ConfirmationDialog
      :open="showDeleteConfirmation"
      @update:open="showDeleteConfirmation = $event"
      title="Konfirmasi Penghapusan"
      dangerMode
      :icon="Trash2"
      :loading="loading"
      confirmLabel="Hapus"
      @confirm="deleteTemplate()"
    >
      <p class="mb-2 text-secondary-900 dark:text-secondary-200">Apakah Anda yakin ingin menghapus template {{ templateToDelete?.name }}?</p>
    </ConfirmationDialog>
  </AppLayout>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, reactive, computed } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { PlusIcon, MoreHorizontal, Pencil, Trash, Trash2, Power } from 'lucide-vue-next';
import { TableRow, TableCell } from '@/components/ui/table';
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import ConfirmationDialog from '@/components/ui/ConfirmationDialog.vue';
import { toast } from 'vue-sonner';
import AdminTable from '@/components/AdminTable.vue';
import StatusBadge from '@/components/StatusBadge.vue';

const props = defineProps({
  customerTemplates: Array,
  adminTemplates: Array,
  availableVariables: Object,
  triggerStatusOptions: Object,
});

// Breadcrumbs
const breadcrumbs = [
  {
    title: 'Admin',
    href: route('admin.dashboard'),
  },
  {
    title: 'Template WhatsApp',
    href: route('admin.notifications.index'),
  },
];

// Status map untuk StatusBadge
const statusMap = {
  '1': 'Aktif',
  '0': 'Nonaktif'
};

// Kolom tabel
const customerColumns = [
  { label: 'Nama' },
  { label: 'Status Trigger' },
  { label: 'Template' },
  { label: 'Status' },
  { label: 'Aksi', headerClass: 'text-right' }
];

const adminColumns = [
  { label: 'Nama' },
  { label: 'Status Trigger' },
  { label: 'Template' },
  { label: 'Status' },
  { label: 'Aksi', headerClass: 'text-right' }
];

// State untuk form dan modal
const form = reactive({
  id: null,
  name: '',
  message_template: '',
  description: '',
  is_active: true,
  order: 0,
});

const createForm = reactive({
  name: '',
  type: 'customer',
  trigger_status: '',
  message_template: '',
  description: '',
  is_active: true,
  order: 0,
});

// State untuk modal dan konfirmasi
const showEditModal = ref(false);
const showCreateModal = ref(false);
const showDeleteConfirmation = ref(false);
const templateToDelete = ref(null);
const loading = ref(false);

// Fungsi untuk mendapatkan label status trigger
const getTriggerStatusLabel = (status) => {
  return props.triggerStatusOptions[status] || status;
};

// Fungsi untuk modal edit
const openEditModal = (template) => {
  form.id = template.id;
  form.name = template.name;
  form.message_template = template.message_template;
  form.description = template.description || '';
  form.is_active = template.is_active;
  form.order = template.order;
  showEditModal.value = true;
};

const closeEditModal = () => {
  showEditModal.value = false;
};

// Fungsi untuk modal create
const openCreateModal = () => {
  createForm.name = '';
  createForm.type = 'customer';
  createForm.trigger_status = '';
  createForm.message_template = '';
  createForm.description = '';
  createForm.is_active = true;
  createForm.order = 0;
  showCreateModal.value = true;
};

const closeCreateModal = () => {
  showCreateModal.value = false;
};

// Fungsi untuk submit form
const submitEdit = () => {
  loading.value = true;
  router.put(route('admin.notifications.whatsapp-templates.update', form.id), form, {
    onSuccess: () => {
      closeEditModal();
      toast.success('Berhasil', {
        description: 'Template berhasil diperbarui',
      });
    },
    onError: (errors) => {
      toast.error('Gagal', {
        description: 'Terjadi kesalahan saat memperbarui template',
      });
    },
    onFinish: () => {
      loading.value = false;
    }
  });
};

const submitCreate = () => {
  loading.value = true;
  router.post(route('admin.notifications.whatsapp-templates.store'), createForm, {
    onSuccess: () => {
      closeCreateModal();
      toast.success('Berhasil', {
        description: 'Template berhasil dibuat',
      });
    },
    onError: (errors) => {
      toast.error('Gagal', {
        description: 'Terjadi kesalahan saat membuat template',
      });
    },
    onFinish: () => {
      loading.value = false;
    }
  });
};

// Fungsi untuk toggle status aktif
const toggleActive = (template) => {
  loading.value = true;
  router.patch(route('admin.notifications.whatsapp-templates.toggle-active', template.id), {}, {
    onSuccess: () => {
      toast.success('Berhasil', {
        description: `Template berhasil ${template.is_active ? 'dinonaktifkan' : 'diaktifkan'}`,
      });
    },
    onError: (errors) => {
      toast.error('Gagal', {
        description: `Terjadi kesalahan saat ${template.is_active ? 'menonaktifkan' : 'mengaktifkan'} template`,
      });
    },
    onFinish: () => {
      loading.value = false;
    }
  });
};

// Fungsi untuk hapus template
const confirmDelete = (template) => {
  templateToDelete.value = template;
  showDeleteConfirmation.value = true;
};

const deleteTemplate = () => {
  if (!templateToDelete.value) return;
  
  loading.value = true;
  
  router.delete(route('admin.notifications.whatsapp-templates.destroy', templateToDelete.value.id), {
    onSuccess: () => {
      toast.success('Berhasil', {
        description: `Template ${templateToDelete.value.name} berhasil dihapus`,
      });
      showDeleteConfirmation.value = false;
      templateToDelete.value = null;
    },
    onError: (errors) => {
      toast.error('Gagal', {
        description: `Terjadi kesalahan saat menghapus template: ${errors.message || 'Unknown error'}`,
      });
    },
    onFinish: () => {
      loading.value = false;
    }
  });
};
</script> 