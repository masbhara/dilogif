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
    <div class="flex h-full flex-1 flex-col gap-5 p-5 md:p-6">
      <!-- Header area with title and actions -->
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white dark:bg-slate-800 p-6 rounded-lg border border-slate-200 dark:border-slate-700 shadow-sm">
        <div class="flex items-center gap-3">
          <div class="flex items-center justify-center w-10 h-10 rounded-full bg-primary/10 text-primary">
            <Settings class="h-5 w-5" />
          </div>
          <div>
            <h1 class="text-2xl font-bold text-secondary-900 dark:text-white">Pengaturan Website</h1>
            <p class="text-sm text-secondary-600 dark:text-secondary-400">Kelola semua pengaturan website dalam satu tempat</p>
          </div>
        </div>
        <div class="flex gap-2">
          <Button 
            variant="outline" 
            @click="navigateToEmailSettings"
            class="flex items-center gap-1.5 h-10 px-4"
          >
            <MailIcon class="h-4 w-4" />
            <span>Email Settings</span>
          </Button>
        </div>
      </div>

      <!-- Tab Navigation dengan Komponen Shadcn UI -->
      <Tabs default-value="general" class="w-full">
        <div class="bg-white dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700 shadow-sm mb-5 overflow-hidden">
          <TabsList class="w-full flex p-0 h-auto bg-slate-50 dark:bg-slate-900 border-b border-slate-200 dark:border-slate-700">
            <TabsTrigger 
              value="general" 
              class="flex-1 items-center gap-2 rounded-none px-6 py-3.5 text-sm font-medium data-[state=active]:border-b-2 data-[state=active]:border-primary data-[state=active]:text-primary data-[state=active]:bg-white dark:data-[state=active]:bg-slate-800 transition-all"
            >
              <Globe class="h-4 w-4" />
              <span>Umum</span>
            </TabsTrigger>
            <TabsTrigger 
              value="media" 
              class="flex-1 items-center gap-2 rounded-none px-6 py-3.5 text-sm font-medium data-[state=active]:border-b-2 data-[state=active]:border-primary data-[state=active]:text-primary data-[state=active]:bg-white dark:data-[state=active]:bg-slate-800 transition-all"
            >
              <ImageIcon class="h-4 w-4" />
              <span>Media</span>
            </TabsTrigger>
            <TabsTrigger 
              value="contact" 
              class="flex-1 items-center gap-2 rounded-none px-6 py-3.5 text-sm font-medium data-[state=active]:border-b-2 data-[state=active]:border-primary data-[state=active]:text-primary data-[state=active]:bg-white dark:data-[state=active]:bg-slate-800 transition-all"
            >
              <Phone class="h-4 w-4" />
              <span>Kontak & Sosial Media</span>
            </TabsTrigger>
            <TabsTrigger 
              value="scripts" 
              class="flex-1 items-center gap-2 rounded-none px-6 py-3.5 text-sm font-medium data-[state=active]:border-b-2 data-[state=active]:border-primary data-[state=active]:text-primary data-[state=active]:bg-white dark:data-[state=active]:bg-slate-800 transition-all"
            >
              <FileCode class="h-4 w-4" />
              <span>Skrip</span>
            </TabsTrigger>
          </TabsList>
        </div>

        <!-- Tab Content -->
        <!-- Tab Pengaturan Umum -->
        <TabsContent value="general" class="space-y-6 mt-0">
          <!-- Pengaturan Umum -->
          <Card class="bg-white dark:bg-slate-800 text-secondary-900 dark:text-white border border-slate-200 dark:border-slate-700 shadow-sm">
            <CardHeader class="border-b border-secondary-200 dark:border-slate-700 pb-5">
              <CardTitle class="text-xl">Informasi Website</CardTitle>
              <CardDescription class="text-secondary-600 dark:text-secondary-400 mt-1">Pengaturan dasar untuk website Anda</CardDescription>
            </CardHeader>
            <CardContent class="pt-6">
              <form @submit.prevent="submitGeneral" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div class="space-y-2.5">
                    <Label for="site_name" class="text-sm font-medium">Nama Website</Label>
                    <Input 
                      id="site_name" 
                      v-model="generalForm.site_name" 
                      type="text" 
                      placeholder="Masukkan nama website" 
                      :error="!!generalForm.errors.site_name"
                      class="h-11"
                    />
                    <p v-if="generalForm.errors.site_name" class="text-sm text-red-500 mt-1">{{ generalForm.errors.site_name }}</p>
                  </div>

                  <div class="space-y-2.5">
                    <Label for="site_subtitle" class="text-sm font-medium">Subtitle Website</Label>
                    <Input 
                      id="site_subtitle" 
                      v-model="generalForm.site_subtitle" 
                      type="text" 
                      placeholder="Masukkan subtitle website" 
                      :error="!!generalForm.errors.site_subtitle"
                      class="h-11"
                    />
                    <p v-if="generalForm.errors.site_subtitle" class="text-sm text-red-500 mt-1">{{ generalForm.errors.site_subtitle }}</p>
                  </div>
                </div>

                <div class="space-y-2.5">
                  <Label for="site_description" class="text-sm font-medium">Deskripsi Website</Label>
                  <Textarea 
                    id="site_description" 
                    v-model="generalForm.site_description" 
                    placeholder="Masukkan deskripsi singkat website" 
                    :error="!!generalForm.errors.site_description"
                    rows="3"
                    class="resize-y min-h-[100px]"
                  />
                  <p v-if="generalForm.errors.site_description" class="text-sm text-red-500 mt-1">{{ generalForm.errors.site_description }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div class="space-y-2.5">
                    <Label for="homepage_route" class="text-sm font-medium">Rute Homepage</Label>
                    <Input 
                      id="homepage_route" 
                      v-model="generalForm.homepage_route" 
                      type="text" 
                      placeholder="/" 
                      :error="!!generalForm.errors.homepage_route"
                      class="h-11"
                    />
                    <p class="text-xs text-muted-foreground mt-1.5">Tentukan rute untuk halaman utama (misalnya: /, /home, /beranda)</p>
                    <p v-if="generalForm.errors.homepage_route" class="text-sm text-red-500 mt-1">{{ generalForm.errors.homepage_route }}</p>
                  </div>

                  <div class="space-y-2.5">
                    <Label for="contact_email" class="text-sm font-medium">Email Kontak</Label>
                    <Input 
                      id="contact_email" 
                      v-model="generalForm.contact_email" 
                      type="email" 
                      placeholder="kontak@example.com" 
                      :error="!!generalForm.errors.contact_email"
                      class="h-11"
                    />
                    <p v-if="generalForm.errors.contact_email" class="text-sm text-red-500 mt-1">{{ generalForm.errors.contact_email }}</p>
                  </div>
                </div>

                <div class="flex justify-end pt-4 border-t border-slate-200 dark:border-slate-700 mt-6">
                  <Button 
                    type="submit" 
                    :disabled="generalForm.processing"
                    class="px-5 h-11"
                  >
                    {{ generalForm.processing ? 'Menyimpan...' : 'Simpan Pengaturan' }}
                  </Button>
                </div>
              </form>
            </CardContent>
          </Card>

          <!-- Pengaturan Footer -->
          <Card class="bg-white dark:bg-slate-800 text-secondary-900 dark:text-white border border-slate-200 dark:border-slate-700 shadow-sm">
            <CardHeader class="border-b border-secondary-200 dark:border-slate-700 pb-5">
              <CardTitle class="text-xl">Pengaturan Footer</CardTitle>
              <CardDescription class="text-secondary-600 dark:text-secondary-400 mt-1">Pengaturan untuk bagian footer website</CardDescription>
            </CardHeader>
            <CardContent class="pt-6">
              <form @submit.prevent="submitFooter" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div class="space-y-2.5">
                    <Label for="copyright_text" class="text-sm font-medium">Teks Copyright</Label>
                    <Input 
                      id="copyright_text" 
                      v-model="footerForm.copyright_text" 
                      type="text" 
                      placeholder="Nama perusahaan atau website" 
                      :error="!!footerForm.errors.copyright_text"
                      class="h-11"
                    />
                    <p class="text-xs text-muted-foreground mt-1.5">Akan ditampilkan sebagai "© [Tahun] [Teks Copyright]"</p>
                    <p v-if="footerForm.errors.copyright_text" class="text-sm text-red-500 mt-1">{{ footerForm.errors.copyright_text }}</p>
                  </div>

                  <div class="space-y-2.5">
                    <Label for="copyright_year" class="text-sm font-medium">Tahun Copyright</Label>
                    <Input 
                      id="copyright_year" 
                      v-model="footerForm.copyright_year" 
                      type="number" 
                      placeholder="2025" 
                      :error="!!footerForm.errors.copyright_year"
                      class="h-11"
                    />
                    <p class="text-xs text-muted-foreground mt-1.5">Kosongkan untuk menggunakan tahun berjalan</p>
                    <p v-if="footerForm.errors.copyright_year" class="text-sm text-red-500 mt-1">{{ footerForm.errors.copyright_year }}</p>
                  </div>
                </div>

                <div class="flex justify-end pt-4 border-t border-slate-200 dark:border-slate-700 mt-6">
                  <Button 
                    type="submit" 
                    :disabled="footerForm.processing"
                    class="px-5 h-11"
                  >
                    {{ footerForm.processing ? 'Menyimpan...' : 'Simpan Pengaturan Footer' }}
                  </Button>
                </div>
              </form>
            </CardContent>
          </Card>
        </TabsContent>

        <!-- Tab Media -->
        <TabsContent value="media" class="space-y-6 mt-0">
          <!-- Gabungan Logo dan Favicon -->
          <Card class="bg-white dark:bg-slate-800 text-secondary-900 dark:text-white border border-slate-200 dark:border-slate-700 shadow-sm">
            <CardHeader class="border-b border-secondary-200 dark:border-slate-700 pb-5">
              <CardTitle class="text-xl">Media Website</CardTitle>
              <CardDescription class="text-secondary-600 dark:text-secondary-400 mt-1">Upload logo dan favicon untuk website Anda</CardDescription>
            </CardHeader>
            <CardContent class="pt-6">
              <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Logo Section -->
                <div>
                  <h3 class="text-base font-medium mb-4">Logo Website</h3>
                  <form @submit.prevent="uploadLogo" class="space-y-6">
                    <div class="space-y-6">
                      <div class="space-y-2.5">
                        <Label for="logo" class="text-sm font-medium">Upload Logo</Label>
                        <Input 
                          id="logo" 
                          type="file" 
                          accept="image/*" 
                          @change="handleFileChange($event, 'logo')" 
                          :error="!!logoForm.errors.logo"
                          class="h-11"
                        />
                        <p class="text-xs text-muted-foreground mt-1.5">Format: JPG, PNG, SVG. Ukuran maksimal: 2MB.</p>
                        <p v-if="logoForm.errors.logo" class="text-sm text-red-500 mt-1">{{ logoForm.errors.logo }}</p>
                      </div>

                      <!-- Logo Preview Area -->
                      <div class="flex flex-col items-center justify-center border border-dashed rounded-lg p-4 bg-slate-50 dark:bg-slate-900 h-[120px]">
                        <div v-if="logoPreview" class="flex items-center justify-center h-full">
                          <img :src="logoPreview" alt="Logo Preview" class="max-h-[100px] max-w-full object-contain" />
                        </div>
                        <div v-else class="flex flex-col items-center justify-center h-full text-muted-foreground">
                          <ImageIcon class="h-8 w-8 mb-1 opacity-40" />
                          <span class="text-sm">Logo belum diupload</span>
                        </div>
                      </div>

                      <div class="flex justify-end">
                        <Button 
                          type="submit" 
                          :disabled="logoForm.processing || !logoForm.logo"
                          class="px-5 h-11"
                        >
                          <Upload v-if="!logoForm.processing" class="h-4 w-4 mr-2" />
                          {{ logoForm.processing ? 'Mengupload...' : 'Upload Logo' }}
                        </Button>
                      </div>
                    </div>
                  </form>
                </div>
                
                <!-- Favicon Section -->
                <div>
                  <h3 class="text-base font-medium mb-4">Favicon</h3>
                  <form @submit.prevent="uploadFavicon" class="space-y-6">
                    <div class="space-y-6">
                      <div class="space-y-2.5">
                        <Label for="favicon" class="text-sm font-medium">Upload Favicon</Label>
                        <Input 
                          id="favicon" 
                          type="file" 
                          accept="image/x-icon,image/png" 
                          @change="handleFileChange($event, 'favicon')" 
                          :error="!!faviconForm.errors.favicon"
                          class="h-11"
                        />
                        <p class="text-xs text-muted-foreground mt-1.5">Format: ICO, PNG. Ukuran maksimal: 1MB. Disarankan ukuran: 32x32px atau 16x16px.</p>
                        <p v-if="faviconForm.errors.favicon" class="text-sm text-red-500 mt-1">{{ faviconForm.errors.favicon }}</p>
                      </div>

                      <!-- Favicon Preview Area -->
                      <div class="flex flex-col items-center justify-center border border-dashed rounded-lg p-4 bg-slate-50 dark:bg-slate-900 h-[120px]">
                        <div v-if="faviconPreview" class="flex items-center justify-center h-full">
                          <img :src="faviconPreview" alt="Favicon Preview" class="max-h-[64px] max-w-[64px] object-contain" />
                        </div>
                        <div v-else class="flex flex-col items-center justify-center h-full text-muted-foreground">
                          <ImageIcon class="h-8 w-8 mb-1 opacity-40" />
                          <span class="text-sm">Favicon belum diupload</span>
                        </div>
                      </div>

                      <div class="flex justify-end">
                        <Button 
                          type="submit" 
                          :disabled="faviconForm.processing || !faviconForm.favicon"
                          class="px-5 h-11"
                        >
                          <Upload v-if="!faviconForm.processing" class="h-4 w-4 mr-2" />
                          {{ faviconForm.processing ? 'Mengupload...' : 'Upload Favicon' }}
                        </Button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </CardContent>
          </Card>
          
          <Card class="bg-white dark:bg-slate-800 text-secondary-900 dark:text-white border border-slate-200 dark:border-slate-700 shadow-sm">
            <CardHeader class="border-b border-secondary-200 dark:border-slate-700 pb-5">
              <CardTitle class="text-xl">Gambar OG Default</CardTitle>
              <CardDescription class="text-secondary-600 dark:text-secondary-400 mt-1">Upload gambar untuk Open Graph (OG) metadata yang akan ditampilkan saat website Anda dibagikan di media sosial</CardDescription>
            </CardHeader>
            <CardContent class="pt-6">
              <form @submit.prevent="uploadOgImage" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div class="space-y-2.5">
                    <Label for="og_image" class="text-sm font-medium">Upload Gambar OG</Label>
                    <Input 
                      id="og_image" 
                      type="file" 
                      accept="image/jpeg,image/png,image/jpg" 
                      @change="handleFileChange($event, 'og_image')" 
                      :error="!!ogImageForm.errors.og_image"
                      class="h-11"
                    />
                    <p class="text-xs text-muted-foreground mt-1.5">Format: JPG, PNG. Ukuran maksimal: 2MB. Rekomendasi ukuran: 1200x630px.</p>
                    <p v-if="ogImageForm.errors.og_image" class="text-sm text-red-500 mt-1">{{ ogImageForm.errors.og_image }}</p>
                  </div>

                  <div class="flex flex-col items-center justify-center border border-dashed rounded-lg p-6 bg-slate-50 dark:bg-slate-900 min-h-[160px]">
                    <div v-if="ogImagePreview" class="flex items-center justify-center h-full">
                      <img :src="ogImagePreview" alt="OG Image Preview" class="max-h-full max-w-full object-contain" />
                    </div>
                    <div v-else class="flex flex-col items-center justify-center h-full text-muted-foreground">
                      <ImageIcon class="h-12 w-12 mb-2 opacity-40" />
                      <span>Gambar OG belum diupload</span>
                    </div>
                  </div>
                </div>

                <div class="flex justify-end pt-4 border-t border-slate-200 dark:border-slate-700 mt-6">
                  <Button 
                    type="submit" 
                    :disabled="ogImageForm.processing || !ogImageForm.og_image"
                    class="px-5 h-11"
                  >
                    <Upload v-if="!ogImageForm.processing" class="h-4 w-4 mr-2" />
                    {{ ogImageForm.processing ? 'Mengupload...' : 'Upload Gambar OG' }}
                  </Button>
                </div>
              </form>
            </CardContent>
          </Card>
        </TabsContent>
        
        <!-- Tab Kontak & Sosial Media (Baru) -->
        <TabsContent value="contact" class="space-y-6 mt-0">
          <!-- WhatsApp Admin Section -->
          <Card class="bg-white dark:bg-slate-800 text-secondary-900 dark:text-white border border-slate-200 dark:border-slate-700 shadow-sm">
            <CardHeader class="border-b border-secondary-200 dark:border-slate-700 pb-5">
              <div class="flex items-center justify-between">
                <div>
                  <CardTitle class="text-xl">WhatsApp Admin</CardTitle>
                  <CardDescription class="text-secondary-600 dark:text-secondary-400 mt-1">
                    Kelola nomor WhatsApp untuk kontak admin website
                  </CardDescription>
                </div>
                <Dialog v-model:open="showWhatsappDialog">
                  <DialogTrigger asChild>
                    <Button @click="whatsappForm.reset()" class="flex items-center gap-1.5">
                      <Plus class="h-4 w-4" />
                      <span>Tambah Nomor</span>
                    </Button>
                  </DialogTrigger>
                  <DialogContent>
                    <DialogHeader>
                      <DialogTitle>Tambah Nomor WhatsApp</DialogTitle>
                      <DialogDescription>
                        Tambahkan informasi nomor WhatsApp admin baru di bawah ini.
                      </DialogDescription>
                    </DialogHeader>
                    
                    <form @submit.prevent="createWhatsappAdmin" class="space-y-4 mt-2">
                      <div class="space-y-2">
                        <Label for="whatsapp_name">Nama Admin</Label>
                        <Input 
                          id="whatsapp_name" 
                          v-model="whatsappForm.name" 
                          placeholder="Contoh: CS Website" 
                          :error="!!whatsappForm.errors.name"
                        />
                        <p v-if="whatsappForm.errors.name" class="text-sm text-red-500 mt-1">
                          {{ whatsappForm.errors.name }}
                        </p>
                      </div>
                      
                      <div class="space-y-2">
                        <Label for="whatsapp_phone">Nomor WhatsApp</Label>
                        <Input 
                          id="whatsapp_phone" 
                          v-model="whatsappForm.phone_number" 
                          placeholder="Format: 628123456789 (tanpa +)" 
                          :error="!!whatsappForm.errors.phone_number"
                        />
                        <p v-if="whatsappForm.errors.phone_number" class="text-sm text-red-500 mt-1">
                          {{ whatsappForm.errors.phone_number }}
                        </p>
                        <p v-else class="text-xs text-muted-foreground">
                          Gunakan format internasional, contoh: 628123456789 (tanpa +)
                        </p>
                      </div>
                      
                      <div class="space-y-2">
                        <Label for="whatsapp_description">Deskripsi (Opsional)</Label>
                        <Input 
                          id="whatsapp_description" 
                          v-model="whatsappForm.description" 
                          placeholder="Contoh: Customer Service 24 Jam" 
                          :error="!!whatsappForm.errors.description"
                        />
                        <p v-if="whatsappForm.errors.description" class="text-sm text-red-500 mt-1">
                          {{ whatsappForm.errors.description }}
                        </p>
                      </div>
                      
                      <DialogFooter>
                        <Button type="button" variant="outline" @click="showWhatsappDialog = false">
                          Batal
                        </Button>
                        <Button 
                          type="submit" 
                          :disabled="whatsappForm.processing"
                        >
                          {{ whatsappForm.processing ? 'Menyimpan...' : 'Simpan' }}
                        </Button>
                      </DialogFooter>
                    </form>
                  </DialogContent>
                </Dialog>
              </div>
            </CardHeader>
            
            <CardContent class="pt-6">
              <div v-if="whatsappAdmins.length === 0" class="text-center py-8 border border-dashed rounded-lg">
                <Phone class="h-10 w-10 mx-auto text-muted-foreground opacity-40 mb-2" />
                <p class="text-muted-foreground">Belum ada nomor WhatsApp yang ditambahkan</p>
                <Button @click="showWhatsappDialog = true" variant="outline" class="mt-4">
                  <Plus class="h-4 w-4 mr-2" />
                  Tambah Nomor WhatsApp
                </Button>
              </div>
              
              <div v-else class="space-y-4">
                <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm overflow-hidden border border-slate-200 dark:border-slate-700">
                  <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                    <thead class="bg-slate-50 dark:bg-slate-800/60">
                      <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Nama</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Nomor WhatsApp</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Deskripsi</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Aksi</th>
                      </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                      <tr v-for="(whatsapp, index) in whatsappAdmins" :key="whatsapp.id" 
                        class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900 dark:text-slate-100">{{ whatsapp.name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700 dark:text-slate-300">{{ whatsapp.phone_number }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700 dark:text-slate-300">{{ whatsapp.description || '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                          <button 
                            @click="toggleWhatsappStatus(whatsapp)"
                            class="px-2 py-1 rounded text-xs"
                            :class="whatsapp.is_active 
                              ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' 
                              : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400'"
                          >
                            {{ whatsapp.is_active ? 'Aktif' : 'Nonaktif' }}
                          </button>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                          <Button 
                            variant="ghost"
                            size="icon"
                            class="h-8 w-8 text-red-500"
                            @click="deleteWhatsappAdmin(whatsapp.id)"
                          >
                            <Trash2 class="h-4 w-4" />
                          </Button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </CardContent>
          </Card>
          
          <!-- Social Media Section -->
          <Card class="bg-white dark:bg-slate-800 text-secondary-900 dark:text-white border border-slate-200 dark:border-slate-700 shadow-sm">
            <CardHeader class="border-b border-secondary-200 dark:border-slate-700 pb-5">
              <div class="flex items-center justify-between">
                <div>
                  <CardTitle class="text-xl">Media Sosial</CardTitle>
                  <CardDescription class="text-secondary-600 dark:text-secondary-400 mt-1">
                    Kelola akun media sosial yang dimiliki oleh perusahaan
                  </CardDescription>
                </div>
                <Dialog v-model:open="showSocialDialog">
                  <DialogTrigger asChild>
                    <Button @click="socialMediaForm.reset()" class="flex items-center gap-1.5">
                      <Plus class="h-4 w-4" />
                      <span>Tambah Media Sosial</span>
                    </Button>
                  </DialogTrigger>
                  <DialogContent>
                    <DialogHeader>
                      <DialogTitle>Tambah Media Sosial</DialogTitle>
                      <DialogDescription>
                        Tambahkan informasi akun media sosial perusahaan baru di bawah ini.
                      </DialogDescription>
                    </DialogHeader>
                    
                    <form @submit.prevent="createSocialMedia" class="space-y-4 mt-2">
                      <div class="space-y-2">
                        <Label for="social_platform">Platform</Label>
                        <div class="relative">
                          <div 
                            ref="platformSelectRef"
                            class="custom-select-container" 
                            :class="{ 'active': isPlatformSelectOpen }"
                          >
                            <div 
                              @click="togglePlatformSelect" 
                              class="custom-select-trigger flex w-full items-center justify-between gap-2 rounded-md border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 px-3 py-2 text-sm shadow-sm hover:border-slate-300 dark:hover:border-slate-600 focus:outline-none focus:ring-0 disabled:cursor-not-allowed disabled:opacity-50 cursor-pointer h-9"
                              :class="{'border-red-500 dark:border-red-500': !!socialMediaForm.errors.platform}"
                            >
                              <span v-if="socialMediaForm.platform" class="flex items-center gap-2">
                                <component :is="getPlatformIcon(socialMediaForm.platform)" v-if="getPlatformIcon(socialMediaForm.platform)" class="h-4 w-4" />
                                <span>{{ getPlatformLabel(socialMediaForm.platform) }}</span>
                              </span>
                              <span v-else>Pilih platform media sosial</span>
                              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-50 transition-transform" :class="{ 'rotate-180': isPlatformSelectOpen }">
                                <path d="m6 9 6 6 6-6"></path>
                              </svg>
                            </div>
                            
                            <div 
                              v-if="isPlatformSelectOpen" 
                              class="custom-select-dropdown bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-md shadow-lg mt-1 max-h-[200px] overflow-y-auto absolute w-full z-50"
                            >
                              <div 
                                v-for="platform in availablePlatforms" 
                                :key="platform.value"
                                @click="selectPlatform(platform.value)"
                                class="custom-select-option py-2 px-3 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 cursor-pointer text-sm"
                                :class="{ 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-300 font-medium': socialMediaForm.platform === platform.value }"
                              >
                                <div class="flex items-center gap-2">
                                  <component :is="platform.icon" v-if="platform.icon" class="h-4 w-4" />
                                  <span>{{ platform.label }}</span>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <p v-if="socialMediaForm.errors.platform" class="text-sm text-red-500 mt-1">
                          {{ socialMediaForm.errors.platform }}
                        </p>
                      </div>
                      
                      <div class="space-y-2">
                        <Label for="social_username">Username</Label>
                        <Input 
                          id="social_username" 
                          v-model="socialMediaForm.username" 
                          placeholder="Username atau nama akun" 
                          :error="!!socialMediaForm.errors.username"
                        />
                        <p v-if="socialMediaForm.errors.username" class="text-sm text-red-500 mt-1">
                          {{ socialMediaForm.errors.username }}
                        </p>
                      </div>
                      
                      <div class="space-y-2">
                        <Label for="social_url">URL</Label>
                        <Input 
                          id="social_url" 
                          v-model="socialMediaForm.url" 
                          placeholder="https://example.com/username" 
                          :error="!!socialMediaForm.errors.url"
                        />
                        <p v-if="socialMediaForm.errors.url" class="text-sm text-red-500 mt-1">
                          {{ socialMediaForm.errors.url }}
                        </p>
                        <p v-else class="text-xs text-muted-foreground">
                          Masukkan URL lengkap ke akun media sosial
                        </p>
                      </div>
                      
                      <DialogFooter>
                        <Button type="button" variant="outline" @click="showSocialDialog = false">
                          Batal
                        </Button>
                        <Button 
                          type="submit" 
                          :disabled="socialMediaForm.processing"
                        >
                          {{ socialMediaForm.processing ? 'Menyimpan...' : 'Simpan' }}
                        </Button>
                      </DialogFooter>
                    </form>
                  </DialogContent>
                </Dialog>
              </div>
            </CardHeader>
            
            <CardContent class="pt-6">
              <div v-if="socialMedia.length === 0" class="text-center py-8 border border-dashed rounded-lg">
                <Share2 class="h-10 w-10 mx-auto text-muted-foreground opacity-40 mb-2" />
                <p class="text-muted-foreground">Belum ada media sosial yang ditambahkan</p>
                <Button @click="showSocialDialog = true" variant="outline" class="mt-4">
                  <Plus class="h-4 w-4 mr-2" />
                  Tambah Media Sosial
                </Button>
              </div>
              
              <div v-else class="space-y-4">
                <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm overflow-hidden border border-slate-200 dark:border-slate-700">
                  <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                    <thead class="bg-slate-50 dark:bg-slate-800/60">
                      <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Platform</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Username</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">URL</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Aksi</th>
                      </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                      <tr v-for="social in socialMedia" :key="social.id" class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                        <td class="px-6 py-4 whitespace-nowrap">
                          <div class="flex items-center">
                            <div class="flex-shrink-0 h-9 w-9 rounded-full bg-primary/10 text-primary flex items-center justify-center mr-3">
                              <component :is="getSocialIcon(social.platform)" class="h-4 w-4" />
                            </div>
                            <span class="text-sm font-medium text-slate-900 dark:text-slate-100 capitalize">{{ social.platform }}</span>
                          </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700 dark:text-slate-300">{{ social.username }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                          <a 
                            :href="social.url" 
                            target="_blank" 
                            rel="noopener noreferrer"
                            class="text-primary hover:underline truncate max-w-[200px] inline-block"
                          >
                            {{ social.url }}
                          </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                          <span 
                            class="px-2 py-1 text-xs rounded-full"
                            :class="social.is_active 
                              ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' 
                              : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400'"
                          >
                            {{ social.is_active ? 'Aktif' : 'Nonaktif' }}
                          </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                          <div class="flex justify-end space-x-2">
                            <Button 
                              variant="ghost"
                              size="icon"
                              class="h-8 w-8 text-slate-500"
                              @click="toggleSocialMediaStatus(social)"
                            >
                              <Check v-if="social.is_active" class="h-4 w-4 text-green-500" />
                              <X v-else class="h-4 w-4 text-red-500" />
                            </Button>
                            <Button 
                              variant="ghost"
                              size="icon"
                              class="h-8 w-8 text-red-500"
                              @click="deleteSocialMedia(social.id)"
                            >
                              <Trash2 class="h-4 w-4" />
                            </Button>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </CardContent>
          </Card>
        </TabsContent>
        
        <!-- Tab Skrip -->
        <TabsContent value="scripts" class="space-y-6 mt-0">
          <Card class="bg-white dark:bg-slate-800 text-secondary-900 dark:text-white border border-slate-200 dark:border-slate-700 shadow-sm">
            <CardHeader class="border-b border-secondary-200 dark:border-slate-700 pb-5">
              <CardTitle class="text-xl">Skrip Tracking dan Analytics</CardTitle>
              <CardDescription class="text-secondary-600 dark:text-secondary-400 mt-1">Tambahkan skrip untuk pelacakan dan analitik website</CardDescription>
            </CardHeader>
            <CardContent class="pt-6">
              <form @submit.prevent="submitScripts" class="space-y-6">
                <div class="space-y-2.5">
                  <Label for="meta_pixel_script" class="text-sm font-medium">Meta Pixel Script</Label>
                  <Textarea 
                    id="meta_pixel_script" 
                    v-model="scriptsForm.meta_pixel_script" 
                    placeholder="<!-- Tempelkan Meta Pixel Script di sini -->" 
                    :error="!!scriptsForm.errors.meta_pixel_script"
                    rows="4"
                    class="font-mono text-sm resize-y min-h-[120px] bg-slate-50 dark:bg-slate-900"
                  />
                  <p class="text-xs text-muted-foreground mt-1.5">Tempelkan kode Meta Pixel (Facebook Pixel) di sini.</p>
                  <p v-if="scriptsForm.errors.meta_pixel_script" class="text-sm text-red-500 mt-1">{{ scriptsForm.errors.meta_pixel_script }}</p>
                </div>
                
                <div class="space-y-2.5">
                  <Label for="google_tag_script" class="text-sm font-medium">Google Tag Script</Label>
                  <Textarea 
                    id="google_tag_script" 
                    v-model="scriptsForm.google_tag_script" 
                    placeholder="<!-- Tempelkan Google Tag Script di sini -->" 
                    :error="!!scriptsForm.errors.google_tag_script"
                    rows="4"
                    class="font-mono text-sm resize-y min-h-[120px] bg-slate-50 dark:bg-slate-900"
                  />
                  <p class="text-xs text-muted-foreground mt-1.5">Tempelkan kode Google Tag Manager di sini.</p>
                  <p v-if="scriptsForm.errors.google_tag_script" class="text-sm text-red-500 mt-1">{{ scriptsForm.errors.google_tag_script }}</p>
                </div>
                
                <div class="space-y-2.5">
                  <Label for="tiktok_pixel_script" class="text-sm font-medium">TikTok Pixel Script</Label>
                  <Textarea 
                    id="tiktok_pixel_script" 
                    v-model="scriptsForm.tiktok_pixel_script" 
                    placeholder="<!-- Tempelkan TikTok Pixel Script di sini -->" 
                    :error="!!scriptsForm.errors.tiktok_pixel_script"
                    rows="4"
                    class="font-mono text-sm resize-y min-h-[120px] bg-slate-50 dark:bg-slate-900"
                  />
                  <p class="text-xs text-muted-foreground mt-1.5">Tempelkan kode TikTok Pixel di sini.</p>
                  <p v-if="scriptsForm.errors.tiktok_pixel_script" class="text-sm text-red-500 mt-1">{{ scriptsForm.errors.tiktok_pixel_script }}</p>
                </div>
                
                <div class="space-y-2.5">
                  <Label for="header_scripts" class="text-sm font-medium">Skrip Tambahan Header</Label>
                  <Textarea 
                    id="header_scripts" 
                    v-model="scriptsForm.header_scripts" 
                    placeholder="<!-- Tempelkan skrip tambahan untuk header di sini -->" 
                    :error="!!scriptsForm.errors.header_scripts"
                    rows="4"
                    class="font-mono text-sm resize-y min-h-[120px] bg-slate-50 dark:bg-slate-900"
                  />
                  <p class="text-xs text-muted-foreground mt-1.5">Skrip tambahan yang akan ditempatkan di bagian header.</p>
                  <p v-if="scriptsForm.errors.header_scripts" class="text-sm text-red-500 mt-1">{{ scriptsForm.errors.header_scripts }}</p>
                </div>
                
                <div class="space-y-2.5">
                  <Label for="footer_scripts" class="text-sm font-medium">Skrip Tambahan Footer</Label>
                  <Textarea 
                    id="footer_scripts" 
                    v-model="scriptsForm.footer_scripts" 
                    placeholder="<!-- Tempelkan skrip tambahan untuk footer di sini -->" 
                    :error="!!scriptsForm.errors.footer_scripts"
                    rows="4"
                    class="font-mono text-sm resize-y min-h-[120px] bg-slate-50 dark:bg-slate-900"
                  />
                  <p class="text-xs text-muted-foreground mt-1.5">Skrip tambahan yang akan ditempatkan sebelum tag closing body.</p>
                  <p v-if="scriptsForm.errors.footer_scripts" class="text-sm text-red-500 mt-1">{{ scriptsForm.errors.footer_scripts }}</p>
                </div>

                <div class="flex justify-end pt-4 border-t border-slate-200 dark:border-slate-700 mt-6">
                  <Button 
                    type="submit" 
                    :disabled="scriptsForm.processing"
                    class="px-5 h-11"
                  >
                    {{ scriptsForm.processing ? 'Menyimpan...' : 'Simpan Skrip' }}
                  </Button>
                </div>
              </form>
            </CardContent>
          </Card>
        </TabsContent>
      </Tabs>
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