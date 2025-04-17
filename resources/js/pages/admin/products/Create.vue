<template>
    <Head title="Tambah Produk" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4 md:p-6">
            <!-- Header dengan judul dan tombol kembali -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h1 class="text-2xl font-bold">Tambah Produk</h1>
                <Link :href="route('admin.products.index')" class="cursor-pointer">
                    <Button variant="outline" class="flex items-center gap-1.5 w-full sm:w-auto cursor-pointer">
                        <ArrowLeft class="h-4 w-4" />
                        Kembali
                    </Button>
                </Link>
            </div>
            
            <div class="bg-card text-card-foreground rounded-xl shadow border border-sidebar-border/70 dark:border-sidebar-border overflow-hidden">
                <div class="p-6 border-b">
                    <div>
                        <h2 class="text-lg font-medium">Form Produk Baru</h2>
                        <p class="text-muted-foreground mt-1">Tambahkan produk baru ke dalam katalog Anda</p>
                    </div>
                </div>
                <div class="p-6">
                    <form @submit.prevent="submit">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <Label for="name">Nama</Label>
                                    <Input
                                        id="name"
                                        v-model="form.name"
                                        type="text"
                                        class="mt-1 block w-full"
                                        required
                                    />
                                    <InputError :message="form.errors.name" class="mt-2" />
                                </div>

                                <div>
                                    <Label for="category">Kategori</Label>
                                    <Select v-model="selectedCategory">
                                        <SelectTrigger class="w-full">
                                            <SelectValue placeholder="Pilih kategori" class="truncate text-start" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem
                                                :value="null"
                                                class="text-amber-600 font-medium"
                                            >
                                                -- Tanpa Kategori --
                                            </SelectItem>
                                            <SelectItem
                                                v-for="category in categories"
                                                :key="category.id"
                                                :value="category.id"
                                            >
                                                {{ category.name }}
                                            </SelectItem>
                                        </SelectContent>
                                    </Select>
                                    <InputError :message="form.errors.category_id" class="mt-2" />
                                </div>

                                <div>
                                    <Label for="price">Harga</Label>
                                    <Input
                                        id="price"
                                        v-model="form.price"
                                        type="number"
                                        min="0"
                                        step="0.01"
                                        class="mt-1 block w-full"
                                        required
                                    />
                                    <p class="text-xs text-muted-foreground mt-1">
                                        Masukkan harga tanpa tanda baca, contoh: 100000
                                    </p>
                                    <InputError :message="form.errors.price" class="mt-2" />
                                </div>

                                <div>
                                    <Label for="custom_url">URL Kustom</Label>
                                    <Input
                                        id="custom_url"
                                        v-model="form.custom_url"
                                        type="text"
                                        class="mt-1 block w-full"
                                    />
                                    <p class="text-xs text-muted-foreground mt-1">
                                        Opsional: URL kustom untuk produk (tanpa spasi dan karakter khusus)
                                    </p>
                                    <InputError :message="form.errors.custom_url" class="mt-2" />
                                </div>

                                <div>
                                    <Label for="featured_image">Gambar Utama</Label>
                                    
                                    <div v-if="featuredImagePreview" class="mt-2 mb-2">
                                        <img
                                            :src="featuredImagePreview"
                                            alt="Preview"
                                            class="w-32 h-32 object-cover rounded-md"
                                        />
                                    </div>
                                    
                                    <Input
                                        id="featured_image"
                                        type="file"
                                        @input="handleFeaturedImageChange"
                                        accept="image/*"
                                        class="mt-1 block w-full"
                                        required
                                    />
                                    <p class="text-xs text-muted-foreground mt-1">
                                        Format yang didukung: JPEG, PNG, JPG, GIF. Maksimal 2MB.
                                    </p>
                                    <InputError :message="form.errors.featured_image" class="mt-2" />
                                </div>

                                <div>
                                    <Label for="gallery">Galeri Gambar</Label>
                                    <Input
                                        id="gallery"
                                        type="file"
                                        @input="handleGalleryImagesChange"
                                        accept="image/*"
                                        multiple
                                        class="mt-1 block w-full"
                                    />
                                    <p class="text-xs text-muted-foreground mt-1">
                                        Opsional: Pilih beberapa gambar untuk galeri produk. Format yang didukung: JPEG, PNG, JPG, GIF. Maksimal 2MB per gambar.
                                    </p>
                                    <InputError :message="form.errors.gallery" class="mt-2" />
                                </div>
                            </div>

                            <div>
                                <Label for="description">Deskripsi</Label>
                                <Textarea
                                    id="description"
                                    v-model="form.description"
                                    class="mt-1 block w-full h-[400px]"
                                    required
                                />
                                <InputError :message="form.errors.description" class="mt-2" />
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <Button 
                                type="submit" 
                                :disabled="form.processing || loading"
                                class="cursor-pointer"
                            >
                                <Loader2 v-if="form.processing || loading" class="mr-2 h-4 w-4 animate-spin" />
                                Simpan Produk
                            </Button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { ArrowLeft, Loader2 } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Textarea } from '@/components/ui/textarea';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import InputError from '@/components/InputError.vue';
import { ref, watch } from 'vue';
import { toast } from 'vue-sonner';
import { router } from '@inertiajs/vue3';

// Breadcrumbs untuk navigasi
const breadcrumbs = [
    {
        title: 'Admin',
        href: route('admin.dashboard'),
    },
    {
        title: 'Produk',
        href: route('admin.products.index'),
    },
    {
        title: 'Tambah Produk',
        href: '#',
    }
];

const props = defineProps({
    categories: Array
});

// Loading state untuk form submission
const loading = ref(false);

// Preview gambar utama
const featuredImagePreview = ref(null);

// Ref terpisah untuk kategori
const selectedCategory = ref(null);

const form = useForm({
    name: '',
    category_id: '',
    price: '',
    description: '',
    featured_image: null,
    gallery: [],
    custom_url: ''
});

// Watch perubahan pada selectedCategory
watch(selectedCategory, (newValue) => {
    form.category_id = newValue;
});

// Fungsi untuk preview gambar utama
const handleFeaturedImageChange = (event) => {
    const file = event.target.files[0];
    form.featured_image = file;
    
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            featuredImagePreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    } else {
        featuredImagePreview.value = null;
    }
};

// Fungsi untuk menangani galeri gambar
const handleGalleryImagesChange = (event) => {
    if (event.target.files && event.target.files.length > 0) {
        form.gallery = Array.from(event.target.files);
    }
};

const submit = () => {
    loading.value = true;
    
    form.post(route('admin.products.store'), {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => {
            loading.value = false;
            form.reset();
            featuredImagePreview.value = null;
            selectedCategory.value = null;
            
            toast.success('Berhasil', {
                description: 'Produk baru berhasil ditambahkan',
            });
            
            // Redirect ke halaman index produk
            router.visit(route('admin.products.index'));
        },
        onError: (errors) => {
            loading.value = false;
            toast.error('Gagal', {
                description: `Terjadi kesalahan saat menyimpan produk: ${errors.message || 'Unknown error'}`,
            });
            console.error('Error:', errors);
        }
    });
};
</script> 