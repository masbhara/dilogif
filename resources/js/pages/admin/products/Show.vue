<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { ArrowLeft, PencilLine, ShoppingBag, Tag, CircleDollarSign, Calendar, CircleCheck, CircleX, ImageIcon, X, CheckCircle, StarIcon, ExternalLink } from 'lucide-vue-next';
import { ref, computed } from 'vue';

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
        title: 'Detail Produk',
        href: '#',
    },
];

// Props dari controller
const props = defineProps({
    product: Object
});

// Cek apakah produk memiliki fitur dan nilai
const hasProductFeatures = computed(() => {
  return props.product.product_features && 
         Array.isArray(props.product.product_features) && 
         props.product.product_features.length > 0;
});

const hasProductValues = computed(() => {
  return props.product.product_values && 
         Array.isArray(props.product.product_values) && 
         props.product.product_values.length > 0;
});

// Format tanggal
const formatDate = (dateString) => {
    if (!dateString) return 'Tidak tersedia';
    return new Date(dateString).toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};

// Format harga
const formatPrice = (price) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(price);
};

// State untuk lightbox
const showLightbox = ref(false);
const currentImage = ref('');

// Fungsi untuk menampilkan lightbox
const openLightbox = (imagePath) => {
    currentImage.value = '/storage/' + imagePath;
    showLightbox.value = true;
};

// Fungsi untuk menutup lightbox
const closeLightbox = () => {
    showLightbox.value = false;
    currentImage.value = '';
};
</script>

<template>
    <Head title="Detail Produk" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4 md:p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <Link :href="route('admin.products.index')">
                        <Button 
                            variant="outline" 
                            size="icon" 
                            class="h-8 w-8 cursor-pointer"
                        >
                            <ArrowLeft class="h-4 w-4" />
                        </Button>
                    </Link>
                    <h1 class="text-2xl font-bold">Detail Produk</h1>
                </div>
                <Link :href="route('admin.products.edit', props.product.id)">
                    <Button 
                        variant="outline"
                        class="cursor-pointer flex items-center gap-1.5 w-full sm:w-auto"
                    >
                        <PencilLine class="h-4 w-4" />
                        Edit Produk
                    </Button>
                </Link>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <!-- Informasi Dasar -->
                <Card>
                    <CardHeader>
                        <CardTitle>Informasi Dasar</CardTitle>
                        <CardDescription>Detail data produk</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-4">
                            <div class="flex items-start gap-3">
                                <ShoppingBag class="h-5 w-5 mt-0.5 text-gray-500" />
                                <div class="flex-1">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Nama Produk</p>
                                    <p class="font-medium">{{ props.product.name }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3" v-if="props.product.product_code">
                                <ShoppingBag class="h-5 w-5 mt-0.5 text-gray-500" />
                                <div class="flex-1">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Kode Produk</p>
                                    <p class="font-medium">{{ props.product.product_code }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <Tag class="h-5 w-5 mt-0.5 text-gray-500" />
                                <div class="flex-1">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Kategori</p>
                                    <Badge v-if="props.product.category" variant="outline" class="px-2.5 py-0.5 text-xs font-medium mt-1">
                                        {{ props.product.category.name }}
                                    </Badge>
                                    <Badge v-else variant="outline" class="px-2.5 py-0.5 text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300 mt-1">
                                        Kategori tidak tersedia
                                    </Badge>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <CircleDollarSign class="h-5 w-5 mt-0.5 text-gray-500" />
                                <div class="flex-1">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Harga</p>
                                    <p class="font-medium">{{ formatPrice(props.product.price) }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3" v-if="props.product.demo_url">
                                <ExternalLink class="h-5 w-5 mt-0.5 text-gray-500" />
                                <div class="flex-1">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">URL Demo</p>
                                    <a 
                                        :href="props.product.demo_url" 
                                        target="_blank"
                                        class="text-primary-600 hover:underline"
                                    >
                                        {{ props.product.demo_url }}
                                    </a>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <div v-if="props.product.is_active">
                                    <CircleCheck class="h-5 w-5 mt-0.5 text-green-500" />
                                </div>
                                <div v-else>
                                    <CircleX class="h-5 w-5 mt-0.5 text-red-500" />
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Status</p>
                                    <p class="font-medium">
                                        {{ props.product.is_active ? 'Produk Aktif' : 'Produk Tidak Aktif' }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <Calendar class="h-5 w-5 mt-0.5 text-gray-500" />
                                <div class="flex-1">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Informasi Waktu</p>
                                    <div class="grid grid-cols-2 gap-x-4 gap-y-2 mt-1.5">
                                        <div>
                                            <p class="text-xs text-gray-500">Dibuat</p>
                                            <p class="text-sm">{{ formatDate(props.product.created_at) }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500">Diperbarui</p>
                                            <p class="text-sm">{{ formatDate(props.product.updated_at) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Gambar Produk -->
                <Card>
                    <CardHeader>
                        <CardTitle>Gambar Produk</CardTitle>
                        <CardDescription>Foto utama produk</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="mb-6">
                            <p class="text-sm text-gray-500 mb-2">Gambar Utama</p>
                            <div 
                                class="border rounded-md overflow-hidden cursor-pointer hover:opacity-90 transition-opacity"
                                @click="openLightbox(props.product.featured_image)"
                            >
                                <img 
                                    :src="'/storage/' + props.product.featured_image" 
                                    :alt="props.product.name"
                                    class="w-full h-64 object-contain"
                                />
                            </div>
                            <p class="text-xs text-center mt-1 text-muted-foreground">Klik pada gambar untuk melihat ukuran penuh</p>
                        </div>
                        
                        <div v-if="props.product.gallery && props.product.gallery.length > 0">
                            <p class="text-sm text-gray-500 mb-2">Galeri Produk</p>
                            <div class="grid grid-cols-3 gap-2">
                                <div 
                                    v-for="image in props.product.gallery" 
                                    :key="image.id"
                                    class="border rounded-md overflow-hidden cursor-pointer hover:opacity-90 transition-opacity"
                                    @click="openLightbox(image.image)"
                                >
                                    <img 
                                        :src="'/storage/' + image.image" 
                                        :alt="props.product.name"
                                        class="w-full h-24 object-cover"
                                    />
                                </div>
                            </div>
                        </div>
                        
                        <div v-else class="flex items-center justify-center p-6 border border-dashed rounded-md">
                            <div class="text-center">
                                <ImageIcon class="h-8 w-8 mx-auto text-gray-400" />
                                <p class="mt-2 text-sm text-gray-500">Belum ada gambar galeri</p>
                            </div>
                        </div>
                    </CardContent>
                </Card>
                
                <!-- Deskripsi Produk -->
                <Card class="md:col-span-2">
                    <CardHeader>
                        <CardTitle>Deskripsi Produk</CardTitle>
                        <CardDescription>Informasi detail tentang produk</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="prose dark:prose-invert max-w-none">
                            <p>{{ props.product.description }}</p>
                        </div>
                    </CardContent>
                </Card>

                <!-- Fitur Produk (jika ada) -->
                <Card v-if="hasProductFeatures">
                    <CardHeader>
                        <CardTitle class="flex items-center gap-2">
                            <CheckCircle class="h-5 w-5 text-primary-600" />
                            Fitur Produk
                        </CardTitle>
                        <CardDescription>Fitur-fitur unggulan produk</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <ul class="space-y-2">
                            <li v-for="(feature, index) in props.product.product_features" :key="index" class="flex items-start gap-2">
                                <CircleCheck class="h-4 w-4 mt-0.5 text-primary-600 flex-shrink-0" />
                                <span>{{ feature }}</span>
                            </li>
                        </ul>
                    </CardContent>
                </Card>

                <!-- Nilai Produk (jika ada) -->
                <Card v-if="hasProductValues">
                    <CardHeader>
                        <CardTitle class="flex items-center gap-2">
                            <StarIcon class="h-5 w-5 text-amber-500" />
                            Keunggulan Produk
                        </CardTitle>
                        <CardDescription>Nilai dan keunggulan produk</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <ul class="space-y-2">
                            <li v-for="(value, index) in props.product.product_values" :key="index" class="flex items-start gap-2">
                                <StarIcon class="h-4 w-4 mt-0.5 text-amber-500 flex-shrink-0" />
                                <span>{{ value }}</span>
                            </li>
                        </ul>
                    </CardContent>
                </Card>
            </div>
        </div>

        <!-- Lightbox Modal -->
        <div v-if="showLightbox" class="fixed inset-0 bg-black bg-opacity-80 z-50 flex items-center justify-center p-4">
            <div class="relative max-w-4xl w-full">
                <button 
                    @click="closeLightbox"
                    class="absolute -top-10 right-0 text-white hover:text-gray-300 cursor-pointer"
                >
                    <X class="h-6 w-6" />
                </button>
                <img 
                    :src="currentImage" 
                    :alt="props.product.name"
                    class="w-full max-h-[80vh] object-contain bg-black/30 rounded-lg"
                />
            </div>
        </div>
    </AppLayout>
</template> 