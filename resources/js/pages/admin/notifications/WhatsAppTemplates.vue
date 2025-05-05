<template>
  <AppLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Pengaturan Template WhatsApp
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <div class="mb-6">
              <h3 class="text-lg font-medium text-gray-900">Template Pesan untuk Pelanggan</h3>
              <p class="mt-1 text-sm text-gray-600">
                Template ini digunakan untuk mengirim notifikasi WhatsApp ke pelanggan berdasarkan status pesanan.
              </p>
            </div>

            <!-- Tabel Template Pelanggan -->
            <div class="overflow-x-auto mb-8">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Nama
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Status Trigger
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Template
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Status
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Aksi
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="template in customerTemplates" :key="template.id">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                      {{ template.name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ getTriggerStatusLabel(template.trigger_status) }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                      <div class="max-h-24 overflow-y-auto">
                        <pre class="whitespace-pre-wrap">{{ template.message_template }}</pre>
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span :class="template.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                        {{ template.is_active ? 'Aktif' : 'Nonaktif' }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                      <button @click="openEditModal(template)" class="text-indigo-600 hover:text-indigo-900 mr-3">
                        Edit
                      </button>
                      <button @click="toggleActive(template)" class="text-gray-600 hover:text-gray-900 mr-3">
                        {{ template.is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                      </button>
                      <button @click="confirmDelete(template)" class="text-red-600 hover:text-red-900">
                        Hapus
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <div class="mb-6">
              <h3 class="text-lg font-medium text-gray-900">Template Pesan untuk Admin</h3>
              <p class="mt-1 text-sm text-gray-600">
                Template ini digunakan untuk mengirim notifikasi WhatsApp ke admin berdasarkan status pesanan.
              </p>
            </div>

            <!-- Tabel Template Admin -->
            <div class="overflow-x-auto mb-8">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Nama
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Status Trigger
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Template
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Status
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Aksi
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="template in adminTemplates" :key="template.id">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                      {{ template.name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ getTriggerStatusLabel(template.trigger_status) }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                      <div class="max-h-24 overflow-y-auto">
                        <pre class="whitespace-pre-wrap">{{ template.message_template }}</pre>
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span :class="template.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                        {{ template.is_active ? 'Aktif' : 'Nonaktif' }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                      <button @click="openEditModal(template)" class="text-indigo-600 hover:text-indigo-900 mr-3">
                        Edit
                      </button>
                      <button @click="toggleActive(template)" class="text-gray-600 hover:text-gray-900 mr-3">
                        {{ template.is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                      </button>
                      <button @click="confirmDelete(template)" class="text-red-600 hover:text-red-900">
                        Hapus
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <div class="mt-6">
              <button @click="openCreateModal" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-white hover:bg-indigo-700">
                Tambah Template Baru
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Edit Template -->
    <Dialog
      :open="showEditModal"
      @update:open="closeEditModal"
    >
      <DialogContent class="sm:max-w-[425px]">
        <DialogHeader>
          <DialogTitle>Edit Template WhatsApp</DialogTitle>
        </DialogHeader>
        <form @submit.prevent="submitEdit">
          <div class="p-6">
            <div class="mb-4">
              <label for="name" class="block text-sm font-medium text-gray-700">Nama Template</label>
              <input type="text" id="name" v-model="form.name" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>

            <div class="mb-4">
              <label for="message_template" class="block text-sm font-medium text-gray-700">Template Pesan</label>
              <textarea id="message_template" v-model="form.message_template" rows="10" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
              <div class="mt-2">
                <p class="text-sm text-gray-500">Variabel yang tersedia:</p>
                <div class="mt-1 grid grid-cols-2 gap-2">
                  <div v-for="(description, variable) in availableVariables" :key="variable" class="text-xs bg-gray-100 p-1 rounded">
                    <code>{{ variable }}</code> - {{ description }}
                  </div>
                </div>
              </div>
            </div>

            <div class="mb-4">
              <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi (Opsional)</label>
              <textarea id="description" v-model="form.description" rows="2" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
            </div>

            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700">Status</label>
              <div class="mt-2">
                <div class="flex items-center">
                  <input type="checkbox" id="is_active" v-model="form.is_active" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                  <label for="is_active" class="ml-2 block text-sm text-gray-900">
                    Aktif
                  </label>
                </div>
              </div>
            </div>

            <div class="mb-4">
              <label for="order" class="block text-sm font-medium text-gray-700">Urutan</label>
              <input type="number" id="order" v-model="form.order" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>
          </div>

          <div class="px-6 py-4 bg-gray-50 text-right">
            <button type="button" @click="closeEditModal" class="px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-gray-700 hover:bg-gray-50 mr-2">
              Batal
            </button>
            <button type="submit" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-white hover:bg-indigo-700">
              Simpan
            </button>
          </div>
        </form>
      </DialogContent>
    </Dialog>

    <!-- Modal Create Template -->
    <Dialog
      :open="showCreateModal"
      @update:open="closeCreateModal"
    >
      <DialogContent class="sm:max-w-[425px]">
        <DialogHeader>
          <DialogTitle>Tambah Template WhatsApp Baru</DialogTitle>
        </DialogHeader>
        <form @submit.prevent="submitCreate">
          <div class="p-6">
            <div class="mb-4">
              <label for="create_name" class="block text-sm font-medium text-gray-700">Nama Template</label>
              <input type="text" id="create_name" v-model="createForm.name" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>

            <div class="mb-4">
              <label for="create_type" class="block text-sm font-medium text-gray-700">Tipe Template</label>
              <select id="create_type" v-model="createForm.type" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option value="customer">Template Pelanggan</option>
                <option value="admin">Template Admin</option>
              </select>
            </div>

            <div class="mb-4">
              <label for="create_trigger_status" class="block text-sm font-medium text-gray-700">Status Trigger</label>
              <select id="create_trigger_status" v-model="createForm.trigger_status" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option v-for="(label, value) in triggerStatusOptions" :key="value" :value="value">
                  {{ label }}
                </option>
              </select>
            </div>

            <div class="mb-4">
              <label for="create_message_template" class="block text-sm font-medium text-gray-700">Template Pesan</label>
              <textarea id="create_message_template" v-model="createForm.message_template" rows="10" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
              <div class="mt-2">
                <p class="text-sm text-gray-500">Variabel yang tersedia:</p>
                <div class="mt-1 grid grid-cols-2 gap-2">
                  <div v-for="(description, variable) in availableVariables" :key="variable" class="text-xs bg-gray-100 p-1 rounded">
                    <code>{{ variable }}</code> - {{ description }}
                  </div>
                </div>
              </div>
            </div>

            <div class="mb-4">
              <label for="create_description" class="block text-sm font-medium text-gray-700">Deskripsi (Opsional)</label>
              <textarea id="create_description" v-model="createForm.description" rows="2" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
            </div>

            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700">Status</label>
              <div class="mt-2">
                <div class="flex items-center">
                  <input type="checkbox" id="create_is_active" v-model="createForm.is_active" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                  <label for="create_is_active" class="ml-2 block text-sm text-gray-900">
                    Aktif
                  </label>
                </div>
              </div>
            </div>

            <div class="mb-4">
              <label for="create_order" class="block text-sm font-medium text-gray-700">Urutan</label>
              <input type="number" id="create_order" v-model="createForm.order" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>
          </div>

          <div class="px-6 py-4 bg-gray-50 text-right">
            <button type="button" @click="closeCreateModal" class="px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-gray-700 hover:bg-gray-50 mr-2">
              Batal
            </button>
            <button type="submit" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-white hover:bg-indigo-700">
              Simpan
            </button>
          </div>
        </form>
      </DialogContent>
    </Dialog>

    <!-- Konfirmasi Hapus -->
    <ConfirmationDialog
      :open="showDeleteConfirmation"
      @close="closeDeleteConfirmation"
      :title="'Konfirmasi Hapus'"
      :description="'Apakah Anda yakin ingin menghapus template ini? Tindakan ini tidak dapat dibatalkan.'"
      :confirmLabel="'Hapus'"
      :dangerMode="true"
      @confirm="deleteTemplate"
    >
    </ConfirmationDialog>
  </AppLayout>
</template>

<script>
import { ref, reactive } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { router } from '@inertiajs/vue3';
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogDescription, DialogFooter } from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import ConfirmationDialog from '@/Components/ui/ConfirmationDialog.vue';

export default {
  components: {
    AppLayout,
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
    DialogDescription,
    DialogFooter,
    Button,
    ConfirmationDialog,
  },
  props: {
    customerTemplates: Array,
    adminTemplates: Array,
    availableVariables: Object,
    triggerStatusOptions: Object,
  },
  setup(props) {
    // Form untuk edit template
    const form = reactive({
      id: null,
      name: '',
      message_template: '',
      description: '',
      is_active: true,
      order: 0,
    });

    // Form untuk create template
    const createForm = reactive({
      name: '',
      type: 'customer',
      trigger_status: '',
      message_template: '',
      description: '',
      is_active: true,
      order: 0,
    });

    // State untuk modal
    const showEditModal = ref(false);
    const showCreateModal = ref(false);
    const showDeleteConfirmation = ref(false);
    const templateToDelete = ref(null);

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
      router.put(route('admin.notifications.whatsapp-templates.update', form.id), form, {
        onSuccess: () => {
          closeEditModal();
        }
      });
    };

    const submitCreate = () => {
      router.post(route('admin.notifications.whatsapp-templates.store'), createForm, {
        onSuccess: () => {
          closeCreateModal();
        }
      });
    };

    // Fungsi untuk toggle status aktif
    const toggleActive = (template) => {
      router.patch(route('admin.notifications.whatsapp-templates.toggle-active', template.id));
    };

    // Fungsi untuk hapus template
    const confirmDelete = (template) => {
      templateToDelete.value = template;
      showDeleteConfirmation.value = true;
    };

    const closeDeleteConfirmation = () => {
      showDeleteConfirmation.value = false;
      templateToDelete.value = null;
    };

    const deleteTemplate = () => {
      if (templateToDelete.value) {
        router.delete(route('admin.notifications.whatsapp-templates.destroy', templateToDelete.value.id), {
          onSuccess: () => {
            closeDeleteConfirmation();
          }
        });
      }
    };

    return {
      form,
      createForm,
      showEditModal,
      showCreateModal,
      showDeleteConfirmation,
      templateToDelete,
      getTriggerStatusLabel,
      openEditModal,
      closeEditModal,
      openCreateModal,
      closeCreateModal,
      submitEdit,
      submitCreate,
      toggleActive,
      confirmDelete,
      closeDeleteConfirmation,
      deleteTemplate,
    };
  },
};
</script> 