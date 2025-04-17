<template>
    <Head :title="`Edit Produk - ${product.name}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4 md:p-6">
            <!-- Header dengan judul dan tombol kembali -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h1 class="text-2xl font-bold">Edit Produk: {{ product.name }}</h1>
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
                        <h2 class="text-lg font-medium">Form Edit Produk</h2>
                        <p class="text-muted-foreground mt-1">Perbarui informasi produk Anda</p>
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
                                    <Select v-model="form.category_id">
                                        <SelectTrigger>
                                            <SelectValue placeholder="Pilih kategori" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem
                                                value=""
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
                                    <div v-if="!form.category_id" class="mt-1 text-xs text-amber-600">
                                        Produk ini tidak memiliki kategori. Pilih kategori atau biarkan kosong.
                                    </div>
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
                                    <InputError :message="form.errors.custom_url" class="mt-2" />
                                </div>

                                <div>
                                    <Label for="featured_image">Gambar Utama</Label>
                                    <div class="mt-2">
                                        <img
                                            :src="'/storage/' + product.featured_image"
                                            :alt="product.name"
                                            class="w-32 h-32 object-cover rounded-md mb-2"
                                        />
                                    </div>
                                    <Input
                                        id="featured_image"
                                        type="file"
                                        @input="form.featured_image = $event.target.files[0]"
                                        accept="image/*"
                                        class="mt-1 block w-full"
                                    />
                                    <InputError :message="form.errors.featured_image" class="mt-2" />
                                </div>

                                <div>
                                    <Label>Galeri Gambar</Label>
                                    <div class="grid grid-cols-4 gap-4 mt-2">
                                        <div
                                            v-for="image in product.gallery"
                                            :key="image.id"
                                            class="relative group"
                                        >
                                            <img
                                                :src="'/storage/' + image.image"
                                                :alt="product.name"
                                                class="w-full aspect-square object-cover rounded-md"
                                            />
                                            <button
                                                type="button"
                                                @click="showDeleteGalleryDialog(image)"
                                                class="absolute top-2 right-2 p-1 bg-red-600 text-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity"
                                            >
                                                <X class="w-4 h-4" />
                                            </button>
                                        </div>
                                    </div>
                                    <Input
                                        id="gallery"
                                        type="file"
                                        @input="form.gallery = Array.from($event.target.files)"
                                        accept="image/*"
                                        multiple
                                        class="mt-4 block w-full"
                                    />
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
                                Perbarui Produk
                            </Button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Dialog Konfirmasi Hapus Gambar -->
        <ConfirmationDialog
            :open="showDeleteDialog"
            @update:open="showDeleteDialog = $event"
            title="Konfirmasi Penghapusan Gambar"
            dangerMode
            :icon="Trash2"
            :loading="loading"
            confirmLabel="Hapus"
            @confirm="deleteGalleryImage()"
        >
            <p class="mb-2">Apakah Anda yakin ingin menghapus gambar ini dari galeri produk?</p>
        </ConfirmationDialog>
    </AppLayout>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { ArrowLeft, Loader2, X, Trash2 } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Textarea } from '@/components/ui/textarea';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import InputError from '@/components/InputError.vue';
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';
import ConfirmationDialog from '@/components/ui/ConfirmationDialog.vue';
import { toast } from 'vue-sonner';

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
        title: 'Edit Produk',
        href: '#',
    }
];

const props = defineProps({
    product: Object,
    categories: Array
});

// State untuk dialog hapus gambar
const selectedImage = ref(null);
const showDeleteDialog = ref(false);
const loading = ref(false);

const form = useForm({
    name: props.product.name,
    category_id: props.product.category_id,
    price: props.product.price,
    description: props.product.description,
    featured_image: null,
    gallery: [],
    custom_url: props.product.custom_url,
    _method: 'PUT'
});

const submit = () => {
    loading.value = true;
    form.post(route('admin.products.update', props.product.id), {
        onSuccess: () => {
            loading.value = false;
            toast.success('Berhasil', {
                description: `Produk ${props.product.name} berhasil diperbarui`,
            });
            // Redirect ke halaman index produk
            router.visit(route('admin.products.index'));
        },
        onError: (errors) => {
            loading.value = false;
            toast.error('Gagal', {
                description: `Terjadi kesalahan saat memperbarui produk`,
            });
            console.error('Error:', errors);
        },
        forceFormData: true
    });
};

const showDeleteGalleryDialog = (image) => {
    selectedImage.value = image;
    showDeleteDialog.value = true;
};

const deleteGalleryImage = () => {
    if (!selectedImage.value) return;
    
    loading.value = true;
    
    router.delete(route('admin.products.gallery.delete', selectedImage.value.id), {
        preserveScroll: true,
        only: ['product'],
        onSuccess: () => {
            toast.success('Berhasil', {
                description: 'Gambar berhasil dihapus dari galeri',
            });
            showDeleteDialog.value = false;
        },
        onError: (errors) => {
            toast.error('Gagal', {
                description: `Terjadi kesalahan saat menghapus gambar: ${errors.message || 'Unknown error'}`,
            });
        },
        onFinish: () => {
            loading.value = false;
        }
    });
};
</script> 