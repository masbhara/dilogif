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
                                    <Input
                                        id="featured_image"
                                        type="file"
                                        @input="form.featured_image = $event.target.files[0]"
                                        accept="image/*"
                                        class="mt-1 block w-full"
                                        required
                                    />
                                    <InputError :message="form.errors.featured_image" class="mt-2" />
                                </div>

                                <div>
                                    <Label for="gallery">Galeri Gambar</Label>
                                    <Input
                                        id="gallery"
                                        type="file"
                                        @input="form.gallery = Array.from($event.target.files)"
                                        accept="image/*"
                                        multiple
                                        class="mt-1 block w-full"
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
                            <Button type="submit" :disabled="form.processing">
                                <Loader2 v-if="form.processing" class="mr-2 h-4 w-4 animate-spin" />
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

const form = useForm({
    name: '',
    category_id: '',
    price: '',
    description: '',
    featured_image: null,
    gallery: [],
    custom_url: ''
});

const submit = () => {
    form.post(route('admin.products.store'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        }
    });
};
</script> 