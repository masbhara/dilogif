<template>
    <Head title="Produk" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4 md:p-6">
            <!-- Header dengan judul dan tombol tambah -->
            <div class="mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="font-semibold text-lg text-gray-800 leading-tight">Produk</h1>
                        <Breadcrumb :items="breadcrumbItems" />
                    </div>
                    <Link :href="route('admin.products.create')" class="flex items-center gap-2 bg-primary hover:bg-primary/90 transition-all text-white px-4 py-2 rounded-md">
                        <PlusCircleIcon class="w-5 h-5" />
                        <span>Tambah Produk</span>
                    </Link>
                </div>
            </div>
                
            <div class="bg-card text-card-foreground rounded-xl shadow border border-sidebar-border/70 dark:border-sidebar-border overflow-hidden">
                <div class="p-6 border-b">
                    <div>
                        <h2 class="text-lg font-medium">Daftar Produk</h2>
                        <p class="text-muted-foreground mt-1">Kelola semua produk yang tersedia di situs Anda</p>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <Table>
                        <TableHeader>
                            <TableRow class="hover:bg-transparent border-b border-border">
                                <TableHead class="py-3 px-6 font-medium text-muted-foreground">Gambar</TableHead>
                                <TableHead class="py-3 px-6 font-medium text-muted-foreground">Nama</TableHead>
                                <TableHead class="py-3 px-6 font-medium text-muted-foreground">Kategori</TableHead>
                                <TableHead class="py-3 px-6 font-medium text-muted-foreground">Harga</TableHead>
                                <TableHead class="py-3 px-6 font-medium text-muted-foreground">URL</TableHead>
                                <TableHead class="py-3 px-6 font-medium text-muted-foreground">Status</TableHead>
                                <TableHead class="py-3 px-6 font-medium text-muted-foreground text-right">Tindakan</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="product in products.data" :key="product.id" class="border-b border-border/60 hover:bg-muted/20">
                                <TableCell class="py-3.5 px-6 align-middle">
                                    <img
                                        :src="'/storage/' + product.featured_image"
                                        :alt="product.name"
                                        class="w-16 h-16 object-cover rounded-md"
                                    />
                                </TableCell>
                                <TableCell class="py-3.5 px-6 align-middle font-medium">{{ product.name }}</TableCell>
                                <TableCell class="py-3.5 px-6 align-middle">
                                    <Badge v-if="product.category" variant="outline" class="px-2.5 py-0.5 text-xs font-medium">
                                        {{ product.category.name }}
                                    </Badge>
                                    <Badge v-else variant="outline" class="px-2.5 py-0.5 text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300">
                                        Kategori tidak tersedia
                                    </Badge>
                                </TableCell>
                                <TableCell class="py-3.5 px-6 align-middle">{{ formatPrice(product.price) }}</TableCell>
                                <TableCell class="py-3.5 px-6 align-middle">
                                    <div v-if="product.url" class="flex items-center gap-2">
                                        <a 
                                            :href="product.url" 
                                            target="_blank" 
                                            class="text-blue-600 hover:text-blue-800 underline text-xs truncate max-w-[150px]"
                                            :title="product.url"
                                        >
                                            {{ product.slug }}
                                        </a>
                                        <button 
                                            @click="copyToClipboard(product.url)" 
                                            class="text-gray-500 hover:text-gray-700 focus:outline-none"
                                            title="Salin URL"
                                        >
                                            <ClipboardIcon class="w-4 h-4" />
                                        </button>
                                    </div>
                                    <span v-else>-</span>
                                </TableCell>
                                <TableCell class="py-3.5 px-6 align-middle">
                                    <Badge 
                                        :class="[
                                            'px-2.5 py-0.5 text-xs font-medium',
                                            product.is_active 
                                                ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 border-green-800/20 dark:border-green-300/20' 
                                                : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 border-red-800/20 dark:border-red-300/20'
                                        ]"
                                        variant="outline"
                                    >
                                        {{ product.is_active ? 'Aktif' : 'Nonaktif' }}
                                    </Badge>
                                </TableCell>
                                <TableCell class="py-3.5 px-6 align-middle text-right">
                                    <DropdownMenu>
                                        <DropdownMenuTrigger asChild>
                                            <Button variant="ghost" size="icon" class="h-8 w-8 cursor-pointer">
                                                <MoreHorizontal class="h-4 w-4" />
                                                <span class="sr-only">Menu</span>
                                            </Button>
                                        </DropdownMenuTrigger>
                                        <DropdownMenuContent align="end" class="w-[160px]">
                                            <DropdownMenuItem @click="viewProduct(product)" class="flex items-center gap-2 cursor-pointer py-1.5">
                                                <Eye class="h-4 w-4" />
                                                <span>Lihat</span>
                                            </DropdownMenuItem>
                                            <DropdownMenuItem @click="editProduct(product)" class="flex items-center gap-2 cursor-pointer py-1.5">
                                                <Pencil class="h-4 w-4" />
                                                <span>Edit</span>
                                            </DropdownMenuItem>
                                            <DropdownMenuSeparator />
                                            <DropdownMenuItem @click="showHapusDialog(product)" class="flex items-center gap-2 cursor-pointer py-1.5 text-red-600">
                                                <Trash class="h-4 w-4" />
                                                <span>Hapus</span>
                                            </DropdownMenuItem>
                                        </DropdownMenuContent>
                                    </DropdownMenu>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>
                
                <div v-if="products.links && products.links.length > 0" class="py-4 px-6 flex items-center justify-between border-t">
                    <Pagination :links="products.links" />
                </div>
            </div>
        </div>

        <!-- Dialog Konfirmasi Hapus -->
        <ConfirmationDialog
            :open="showDeleteDialog"
            @update:open="showDeleteDialog = $event"
            title="Konfirmasi Penghapusan"
            dangerMode
            :icon="Trash2"
            :loading="loading"
            confirmLabel="Hapus"
            @confirm="hapusProduct()"
        >
            <p class="mb-2">Apakah Anda yakin ingin menghapus {{ selectedProduct?.name }}?</p>
        </ConfirmationDialog>
    </AppLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted, computed } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { PlusIcon, MoreHorizontal, Eye, Pencil, Trash, Trash2, ClipboardIcon } from 'lucide-vue-next';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuSeparator, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import Pagination from '@/components/ui/pagination/Pagination.vue';
import ConfirmationDialog from '@/components/ui/ConfirmationDialog.vue';
import { router } from '@inertiajs/vue3';
import { toast } from 'vue-sonner';
import { PlusCircleIcon, TrashIcon, PencilSquareIcon, EyeIcon } from '@heroicons/vue/24/outline';
import Breadcrumb from '@/components/ui/breadcrumb.vue';

// Breadcrumbs untuk AppLayout
const breadcrumbs = [
    {
        title: 'Admin',
        href: route('admin.dashboard'),
    },
    {
        title: 'Produk',
        href: route('admin.products.index'),
    },
];

// Breadcrumb items untuk komponen Breadcrumb dalam halaman
const breadcrumbItems = computed(() => [
    { label: 'Dashboard', href: route('admin.dashboard') },
    { label: 'Produk' }
]);

// State untuk dialog hapus
const selectedProduct = ref(null);
const showDeleteDialog = ref(false);
const loading = ref(false);

const props = defineProps({
    products: Object
});

const formatPrice = (price) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR'
    }).format(price);
};

const viewProduct = (product) => {
    router.visit(route('admin.products.show', product.id));
};

const editProduct = (product) => {
    router.visit(route('admin.products.edit', product.id));
};

const showHapusDialog = (product) => {
    selectedProduct.value = product;
    showDeleteDialog.value = true;
};

const hapusProduct = () => {
    if (!selectedProduct.value) return;
    
    loading.value = true;
    
    router.delete(route('admin.products.destroy', selectedProduct.value.id), {
        onSuccess: () => {
            toast.success('Berhasil', {
                description: `Produk ${selectedProduct.value.name} berhasil dihapus`,
            });
            showDeleteDialog.value = false;
        },
        onError: (errors) => {
            toast.error('Gagal', {
                description: `Terjadi kesalahan saat menghapus produk: ${errors.message || 'Unknown error'}`,
            });
        },
        onFinish: () => {
            loading.value = false;
        }
    });
};

// Fungsi untuk menyalin URL ke clipboard
const copyToClipboard = (url) => {
    navigator.clipboard.writeText(url).then(() => {
        toast.success('URL berhasil disalin!');
    });
};
</script> 