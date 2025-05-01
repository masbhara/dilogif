<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import { ref, computed, watch, nextTick, onMounted, onUnmounted } from 'vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Textarea } from '@/components/ui/textarea/index';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs/index';
import { toast } from 'vue-sonner';
import { Globe, FileCode, Upload, Image as ImageIcon, Mail as MailIcon, Settings, Phone, Share2, Plus, Trash2, Instagram, Facebook, Twitter, Youtube, Linkedin, X, Check } from 'lucide-vue-next';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';

// Define props
const props = defineProps<{
  settings: {
    id: number;
    site_name: string;
    site_subtitle: string | null;
    site_description: string | null;
    homepage_route: string;
    contact_email: string | null;
    copyright_text: string | null;
    copyright_year: number | null;
    logo_path: string | null;
    favicon_path: string | null;
    default_og_image_path: string | null;
    header_scripts: string | null;
    meta_pixel_script: string | null;
    tiktok_pixel_script: string | null;
    google_tag_script: string | null;
    footer_scripts: string | null;
  };
  mediaUrls: {
    logo: string | null;
    favicon: string | null;
    og_image: string | null;
  };
  whatsappAdmins?: Array<{
    id: number;
    name: string;
    phone_number: string;
    description: string | null;
    is_active: boolean;
    order: number;
  }>;
  socialMedia?: Array<{
    id: number;
    platform: string;
    username: string;
    url: string;
    icon: string | null;
    is_active: boolean;
    order: number;
  }>;
}>();

// Breadcrumbs untuk navigasi
const breadcrumbs = [
  {
    title: 'Admin',
    href: route('admin.dashboard'),
  },
  {
    title: 'Pengaturan Website',
    href: route('admin.settings.index'),
  },
];

// State
const activeTab = ref('general');
const isProcessing = ref(false);
const showWhatsappDialog = ref(false);
const showSocialDialog = ref(false);

// Form untuk pengaturan umum
const generalForm = useForm({
  site_name: props.settings.site_name || '',
  site_subtitle: props.settings.site_subtitle || '',
  site_description: props.settings.site_description || '',
  homepage_route: props.settings.homepage_route || '/',
  contact_email: props.settings.contact_email || '',
});

// Form untuk pengaturan footer
const footerForm = useForm({
  copyright_text: props.settings.copyright_text || '',
  copyright_year: props.settings.copyright_year || new Date().getFullYear(),
});

// Form untuk skrip
const scriptsForm = useForm({
  header_scripts: props.settings.header_scripts || '',
  meta_pixel_script: props.settings.meta_pixel_script || '',
  tiktok_pixel_script: props.settings.tiktok_pixel_script || '',
  google_tag_script: props.settings.google_tag_script || '',
  footer_scripts: props.settings.footer_scripts || '',
});

// Forms untuk upload media
const logoForm = useForm({
  logo: null as File | null,
});

const faviconForm = useForm({
  favicon: null as File | null,
});

const ogImageForm = useForm({
  og_image: null as File | null,
});

// State untuk WhatsApp Admin
const whatsappAdmins = ref(props.whatsappAdmins || []);
const whatsappForm = useForm({
  name: '',
  phone_number: '',
  description: '',
  is_active: true,
});

// State untuk Social Media
const socialMedia = ref(props.socialMedia || []);
const socialMediaForm = useForm({
  platform: '',
  username: '',
  url: '',
  is_active: true,
});

// Available social media platforms
const availablePlatforms = [
  { value: 'facebook', label: 'Facebook', icon: Facebook },
  { value: 'instagram', label: 'Instagram', icon: Instagram },
  { value: 'twitter', label: 'Twitter', icon: Twitter },
  { value: 'x', label: 'X (Twitter)', icon: X },
  { value: 'youtube', label: 'YouTube', icon: Youtube },
  { value: 'linkedin', label: 'LinkedIn', icon: Linkedin },
  { value: 'tiktok', label: 'TikTok', icon: null },
  { value: 'pinterest', label: 'Pinterest', icon: null },
];

// Get social media icon component
const getSocialIcon = (platform: string) => {
  const platformInfo = availablePlatforms.find(p => p.value === platform);
  return platformInfo?.icon || Share2;
};

// File references for preview
const logoFile = ref<File | null>(null);
const faviconFile = ref<File | null>(null);
const ogImageFile = ref<File | null>(null);

// Preview URLs
const logoPreview = ref<string | null>(props.mediaUrls.logo);
const faviconPreview = ref<string | null>(props.mediaUrls.favicon);
const ogImagePreview = ref<string | null>(props.mediaUrls.og_image);

// Fungsi untuk menangani file upload
const handleFileChange = (e: Event, type: 'logo' | 'favicon' | 'og_image') => {
  const target = e.target as HTMLInputElement;
  if (!target.files?.length) return;
  
  const file = target.files[0];
  
  if (type === 'logo') {
    logoFile.value = file;
    logoForm.logo = file;
    logoPreview.value = URL.createObjectURL(file);
  } else if (type === 'favicon') {
    faviconFile.value = file;
    faviconForm.favicon = file;
    faviconPreview.value = URL.createObjectURL(file);
  } else if (type === 'og_image') {
    ogImageFile.value = file;
    ogImageForm.og_image = file;
    ogImagePreview.value = URL.createObjectURL(file);
  }
};

// Submit general settings
const submitGeneral = () => {
  generalForm.put(route('admin.settings.update-general'), {
    onSuccess: () => {
      toast.success('Berhasil', {
        description: 'Pengaturan umum berhasil disimpan',
      });
    },
    onError: () => {
      toast.error('Gagal', {
        description: 'Terjadi kesalahan saat menyimpan pengaturan',
      });
    },
  });
};

// Submit footer settings
const submitFooter = () => {
  footerForm.put(route('admin.settings.update-footer'), {
    onSuccess: () => {
      toast.success('Berhasil', {
        description: 'Pengaturan footer berhasil disimpan',
      });
    },
    onError: () => {
      toast.error('Gagal', {
        description: 'Terjadi kesalahan saat menyimpan pengaturan',
      });
    },
  });
};

// Submit scripts settings
const submitScripts = () => {
  scriptsForm.put(route('admin.settings.update-scripts'), {
    onSuccess: () => {
      toast.success('Berhasil', {
        description: 'Pengaturan skrip berhasil disimpan',
      });
    },
    onError: () => {
      toast.error('Gagal', {
        description: 'Terjadi kesalahan saat menyimpan pengaturan',
      });
    },
  });
};

// Upload logo
const uploadLogo = () => {
  if (!logoForm.logo) return;
  
  logoForm.post(route('admin.settings.upload-logo'), {
    forceFormData: true,
    onSuccess: () => {
      toast.success('Berhasil', {
        description: 'Logo berhasil diupload',
      });
    },
    onError: () => {
      toast.error('Gagal', {
        description: 'Terjadi kesalahan saat mengupload logo',
      });
    },
  });
};

// Upload favicon
const uploadFavicon = () => {
  if (!faviconForm.favicon) return;
  
  faviconForm.post(route('admin.settings.upload-favicon'), {
    forceFormData: true,
    onSuccess: () => {
      toast.success('Berhasil', {
        description: 'Favicon berhasil diupload',
      });
    },
    onError: () => {
      toast.error('Gagal', {
        description: 'Terjadi kesalahan saat mengupload favicon',
      });
    },
  });
};

// Upload OG image
const uploadOgImage = () => {
  if (!ogImageForm.og_image) return;
  
  ogImageForm.post(route('admin.settings.upload-og-image'), {
    forceFormData: true,
    onSuccess: () => {
      toast.success('Berhasil', {
        description: 'Gambar OG berhasil diupload',
      });
    },
    onError: () => {
      toast.error('Gagal', {
        description: 'Terjadi kesalahan saat mengupload gambar OG',
      });
    },
  });
};

// Fungsi navigasi ke halaman email settings
const navigateToEmailSettings = () => {
  router.visit(route('admin.email.index'));
};

// WhatsApp Admin - Create New
const createWhatsappAdmin = () => {
  whatsappForm.post(route('admin.settings.whatsapp.store'), {
    headers: {
      'X-Inertia': 'true'
    },
    onSuccess: () => {
      toast.success('Berhasil', {
        description: 'Nomor WhatsApp Admin berhasil ditambahkan',
      });
      
      // Refresh data
      router.reload({ only: ['whatsappAdmins'] });
      
      // Reset form & close dialog
      whatsappForm.reset();
      showWhatsappDialog.value = false;
    },
    onError: () => {
      toast.error('Gagal', {
        description: 'Terjadi kesalahan saat menambahkan nomor WhatsApp',
      });
    },
  });
};

// WhatsApp Admin - Delete
const deleteWhatsappAdmin = (id: number) => {
  if (confirm('Apakah Anda yakin ingin menghapus nomor WhatsApp ini?')) {
    router.delete(route('admin.settings.whatsapp.destroy', id), {
      headers: {
        'X-Inertia': 'true'
      },
      onSuccess: () => {
        toast.success('Berhasil', {
          description: 'Nomor WhatsApp berhasil dihapus',
        });
      },
      onError: () => {
        toast.error('Gagal', {
          description: 'Terjadi kesalahan saat menghapus nomor WhatsApp',
        });
      },
    });
  }
};

// WhatsApp Admin - Toggle Status
const toggleWhatsappStatus = (whatsapp: any) => {
  router.put(route('admin.settings.whatsapp.update', whatsapp.id), {
    name: whatsapp.name,
    phone_number: whatsapp.phone_number,
    description: whatsapp.description,
    is_active: !whatsapp.is_active,
    order: whatsapp.order
  }, {
    headers: {
      'X-Inertia': 'true'
    },
    onSuccess: () => {
      toast.success('Berhasil', {
        description: 'Status WhatsApp berhasil diubah',
      });
    },
    onError: () => {
      toast.error('Gagal', {
        description: 'Terjadi kesalahan saat mengubah status WhatsApp',
      });
    },
  });
};

// Social Media - Create New
const createSocialMedia = () => {
  socialMediaForm.post(route('admin.settings.social-media.store'), {
    headers: {
      'X-Inertia': 'true'
    },
    onSuccess: () => {
      toast.success('Berhasil', {
        description: 'Media sosial berhasil ditambahkan',
      });
      
      // Refresh data
      router.reload({ only: ['socialMedia'] });
      
      // Reset form & close dialog
      socialMediaForm.reset();
      showSocialDialog.value = false;
    },
    onError: () => {
      toast.error('Gagal', {
        description: 'Terjadi kesalahan saat menambahkan media sosial',
      });
    },
  });
};

// Social Media - Delete
const deleteSocialMedia = (id: number) => {
  if (confirm('Apakah Anda yakin ingin menghapus media sosial ini?')) {
    router.delete(route('admin.settings.social-media.destroy', id), {
      headers: {
        'X-Inertia': 'true'
      },
      onSuccess: () => {
        toast.success('Berhasil', {
          description: 'Media sosial berhasil dihapus',
        });
      },
      onError: () => {
        toast.error('Gagal', {
          description: 'Terjadi kesalahan saat menghapus media sosial',
        });
      },
    });
  }
};

// Social Media - Toggle Status
const toggleSocialMediaStatus = (social: any) => {
  router.put(route('admin.settings.social-media.update', social.id), {
    platform: social.platform,
    username: social.username,
    url: social.url,
    is_active: !social.is_active,
    order: social.order
  }, {
    headers: {
      'X-Inertia': 'true'
    },
    onSuccess: () => {
      toast.success('Berhasil', {
        description: 'Status media sosial berhasil diubah',
      });
    },
    onError: () => {
      toast.error('Gagal', {
        description: 'Terjadi kesalahan saat mengubah status media sosial',
      });
    },
  });
};

// New state for platform selection
const isPlatformSelectOpen = ref(false);
const platformSelectRef = ref<HTMLElement | null>(null);

// Set up click outside handler
const handleOutsideClick = (event: MouseEvent) => {
  if (
    isPlatformSelectOpen.value && 
    platformSelectRef.value && 
    !platformSelectRef.value.contains(event.target as Node)
  ) {
    isPlatformSelectOpen.value = false;
  }
};

// Lifecycle hooks
onMounted(() => {
  document.addEventListener('mousedown', handleOutsideClick);
});

onUnmounted(() => {
  document.removeEventListener('mousedown', handleOutsideClick);
});

// New methods for platform selection
const togglePlatformSelect = () => {
  isPlatformSelectOpen.value = !isPlatformSelectOpen.value;
};

const selectPlatform = (platform: string) => {
  socialMediaForm.platform = platform;
  isPlatformSelectOpen.value = false;
};

const getPlatformIcon = (platform: string) => {
  const platformInfo = availablePlatforms.find(p => p.value === platform);
  return platformInfo?.icon || Share2;
};

const getPlatformLabel = (platform: string) => {
  const platformInfo = availablePlatforms.find(p => p.value === platform);
  return platformInfo?.label || '';
};
</script>

<template>
  <Head title="Pengaturan Website" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="container mx-auto py-6 px-4">
      <header class="mb-6">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-2xl font-semibold text-gray-900">Pengaturan</h1>
            <p class="mt-1 text-sm text-gray-600">
              Kelola pengaturan aplikasi Anda
            </p>
          </div>
        </div>
      </header>

      <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- General Settings -->
        <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
          <div class="p-6">
            <h3 class="text-lg font-medium">Pengaturan Umum</h3>
            <p class="text-sm text-muted-foreground">
              Konfigurasi dasar aplikasi Anda
            </p>

            <div class="mt-6 space-y-4">
              <div class="space-y-2">
                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="app-name">
                  Nama Aplikasi
                </label>
                <input
                  id="app-name"
                  v-model="generalForm.site_name"
                  type="text"
                  class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                  placeholder="Masukkan nama aplikasi"
                />
              </div>

              <div class="space-y-2">
                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="app-description">
                  Deskripsi Aplikasi
                </label>
                <textarea
                  id="app-description"
                  v-model="generalForm.site_description"
                  class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                  placeholder="Masukkan deskripsi aplikasi"
                />
              </div>
            </div>
          </div>
          <div class="flex items-center justify-end space-x-4 border-t p-4">
            <button
              :disabled="generalForm.processing"
              class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2"
              @click="submitGeneral"
            >
              <span v-if="generalForm.processing" class="mr-2">
                <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none" />
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                </svg>
              </span>
              Simpan Pengaturan
            </button>
          </div>
        </div>

        <!-- Contact Settings -->
        <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
          <div class="p-6">
            <h3 class="text-lg font-medium">Pengaturan Kontak</h3>
            <p class="text-sm text-muted-foreground">
              Informasi kontak untuk aplikasi Anda
            </p>

            <div class="mt-6 space-y-4">
              <div class="space-y-2">
                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="email">
                  Email
                </label>
                <input
                  id="email"
                  v-model="generalForm.contact_email"
                  type="email"
                  class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                  placeholder="Masukkan email kontak"
                />
              </div>

              <div class="space-y-2">
                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="phone">
                  Nomor Telepon
                </label>
                <input
                  id="phone"
                  v-model="generalForm.contact_phone"
                  type="tel"
                  class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                  placeholder="Masukkan nomor telepon"
                />
              </div>

              <div class="space-y-2">
                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="address">
                  Alamat
                </label>
                <textarea
                  id="address"
                  v-model="generalForm.contact_address"
                  class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                  placeholder="Masukkan alamat"
                />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<style>
/* Custom select styling */
.custom-select-container {
  position: relative;
  width: 100%;
  border-radius: 0.375rem;
}

.custom-select-dropdown {
  position: absolute;
  top: calc(100%);
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
</style> 