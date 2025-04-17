<template>
    <Head title="Kategori" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4 md:p-6">
            <!-- Header dengan judul dan tombol tambah -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h1 class="text-2xl font-bold">Kategori</h1>
                <Link :href="route('admin.categories.create')" class="cursor-pointer">
                    <Button class="flex items-center gap-1.5 w-full sm:w-auto cursor-pointer">
                        <PlusIcon class="h-4 w-4" />
                        Tambah Kategori
                    </Button>
                </Link>
            </div>
            
            <div class="bg-card text-card-foreground rounded-xl shadow border border-sidebar-border/70 dark:border-sidebar-border overflow-hidden">
                <div class="p-6 border-b">
                    <div>
                        <h2 class="text-lg font-medium">Daftar Kategori</h2>
                        <p class="text-muted-foreground mt-1">Kelola kategori produk di situs Anda</p>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <Table>
                        <TableHeader>
                            <TableRow class="hover:bg-transparent border-b border-border">
                                <TableHead class="py-3 px-6 font-medium text-muted-foreground">Ikon</TableHead>
                                <TableHead class="py-3 px-6 font-medium text-muted-foreground">Nama</TableHead>
                                <TableHead class="py-3 px-6 font-medium text-muted-foreground">Produk</TableHead>
                                <TableHead class="py-3 px-6 font-medium text-muted-foreground">Status</TableHead>
                                <TableHead class="py-3 px-6 font-medium text-muted-foreground text-right">Tindakan</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="category in categories.data" :key="category.id" class="border-b border-border/60 hover:bg-muted/20">
                                <TableCell class="py-3.5 px-6 align-middle">
                                    <img
                                        v-if="category.icon"
                                        :src="'/storage/' + category.icon"
                                        :alt="category.name"
                                        class="w-10 h-10 object-cover rounded-md"
                                    />
                                    <div v-else class="w-10 h-10 bg-gray-200 rounded-md flex items-center justify-center">
                                        <FolderIcon class="w-6 h-6 text-gray-400" />
                                    </div>
                                </TableCell>
                                <TableCell class="py-3.5 px-6 align-middle font-medium">{{ category.name }}</TableCell>
                                <TableCell class="py-3.5 px-6 align-middle">{{ category.products_count }}</TableCell>
                                <TableCell class="py-3.5 px-6 align-middle">
                                    <Badge 
                                        :class="[
                                            'px-2.5 py-0.5 text-xs font-medium',
                                            category.is_active 
                                                ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 border-green-800/20 dark:border-green-300/20' 
                                                : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 border-red-800/20 dark:border-red-300/20'
                                        ]"
                                        variant="outline"
                                    >
                                        {{ category.is_active ? 'Aktif' : 'Nonaktif' }}
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
                                            <DropdownMenuItem @click="editCategory(category)" class="flex items-center gap-2 cursor-pointer py-1.5">
                                                <Pencil class="h-4 w-4" />
                                                <span>Edit</span>
                                            </DropdownMenuItem>
                                            <DropdownMenuSeparator />
                                            <DropdownMenuItem @click="showHapusDialog(category)" class="flex items-center gap-2 cursor-pointer py-1.5 text-red-600">
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
                
                <div v-if="categories.links && categories.links.length > 0" class="py-4 px-6 flex items-center justify-between border-t">
                    <Pagination :links="categories.links" />
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
            @confirm="hapusCategory()"
        >
            <p class="mb-2">Apakah Anda yakin ingin menghapus {{ selectedCategory?.name }}?</p>
        </ConfirmationDialog>
    </AppLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { PlusIcon, MoreHorizontal, Pencil, Trash, FolderIcon, Trash2 } from 'lucide-vue-next';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuSeparator, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import Pagination from '@/components/ui/pagination/Pagination.vue';
import ConfirmationDialog from '@/components/ui/ConfirmationDialog.vue';
import { router } from '@inertiajs/vue3';
import { toast } from 'vue-sonner';

// Breadcrumbs untuk navigasi
const breadcrumbs = [
    {
        title: 'Admin',
        href: route('admin.dashboard'),
    },
    {
        title: 'Kategori',
        href: route('admin.categories.index'),
    },
];

// State untuk dialog hapus
const selectedCategory = ref(null);
const showDeleteDialog = ref(false);
const loading = ref(false);

const props = defineProps({
    categories: Object
});

const editCategory = (category) => {
    router.visit(route('admin.categories.edit', category.id));
};

const showHapusDialog = (category) => {
    selectedCategory.value = category;
    showDeleteDialog.value = true;
};

const hapusCategory = () => {
    if (!selectedCategory.value) return;
    
    loading.value = true;
    
    router.delete(route('admin.categories.destroy', selectedCategory.value.id), {
        onSuccess: () => {
            toast.success('Berhasil', {
                description: `Kategori ${selectedCategory.value.name} berhasil dihapus`,
            });
            showDeleteDialog.value = false;
        },
        onError: (errors) => {
            toast.error('Gagal', {
                description: `Terjadi kesalahan saat menghapus kategori: ${errors.message || 'Unknown error'}`,
            });
        },
        onFinish: () => {
            loading.value = false;
        }
    });
};
</script> 