<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Textarea } from '@/components/ui/textarea/index';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs/index';
import { toast } from 'vue-sonner';
import { Globe, FileCode, Upload, Image as ImageIcon, Mail as MailIcon, Settings } from 'lucide-vue-next';

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
</script>

<template>
  <Head title="Pengaturan Website" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-5 p-5 md:p-6">

        <div class="flex items-center gap-3 justify-between">

          <div>
            <h1 class="text-2xl font-bold text-secondary-900 dark:text-white">Pengaturan Website</h1>
            <p class="text-sm text-secondary-600 dark:text-secondary-400">Kelola semua pengaturan website dalam satu tempat</p>
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