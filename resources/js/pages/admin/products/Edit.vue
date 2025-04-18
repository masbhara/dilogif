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
                                        autocomplete="name"
                                    />
                                    <InputError :message="form.errors.name" class="mt-2" />
                                </div>

                                <div>
                                    <Label for="product_code">Kode Produk</Label>
                                    <Input
                                        id="product_code"
                                        v-model="form.product_code"
                                        type="text"
                                        class="mt-1 block w-full opacity-60 cursor-not-allowed"
                                        disabled
                                    />
                                    <p class="text-xs text-muted-foreground mt-1">
                                        Kode produk digenerate otomatis oleh sistem dan tidak dapat diubah
                                    </p>
                                    <InputError :message="form.errors.product_code" class="mt-2" />
                                </div>

                                <div>
                                    <Label for="category">Kategori</Label>
                                    <Combobox
                                        v-model="form.category_id"
                                        :options="categoryOptions"
                                        placeholder="Pilih kategori..."
                                    />
                                    <InputError :message="form.errors.category_id" />
                                </div>

                                <div>
                                    <Label for="price">Harga</Label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm"> Rp </span>
                                        </div>
                                        <Input
                                            id="price"
                                            v-model="form.price"
                                            type="number"
                                            class="pl-12 mt-1 block w-full"
                                            placeholder="0"
                                            required
                                        />
                                    </div>
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
                                    <Label for="demo_url">URL Demo</Label>
                                    <Input
                                        id="demo_url"
                                        v-model="form.demo_url"
                                        type="url"
                                        class="mt-1 block w-full"
                                        placeholder="https://example.com"
                                    />
                                    <p class="text-xs text-muted-foreground mt-1">
                                        Opsional: Masukkan URL untuk demo produk
                                    </p>
                                    <InputError :message="form.errors.demo_url" class="mt-2" />
                                </div>

                                <div>
                                    <Label for="featured_image">Gambar Utama</Label>
                                    <div class="mt-2">
                                        <!-- Tampilkan gambar utama saat ini atau preview -->
                                        <img
                                            v-if="featuredImagePreview"
                                            :src="featuredImagePreview"
                                            :alt="product.name"
                                            class="w-32 h-32 object-cover rounded-md mb-2"
                                        />
                                        <img
                                            v-else
                                            :src="featuredImageUrl"
                                            :alt="product.name"
                                            class="w-32 h-32 object-cover rounded-md mb-2"
                                        />
                                    </div>
                                    <Input
                                        id="featured_image"
                                        type="file"
                                        @input="handleFeaturedImageChange"
                                        accept="image/*"
                                        class="mt-1 block w-full"
                                    />
                                    <p class="text-xs text-muted-foreground mt-1">
                                        Opsional: Ganti gambar utama produk. Format yang didukung: JPEG, PNG, JPG, GIF. Maksimal 2MB.
                                    </p>
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
                                        @input="handleGalleryImagesChange"
                                        accept="image/*"
                                        multiple
                                        class="mt-4 block w-full"
                                    />
                                    <p class="text-xs text-muted-foreground mt-1">
                                        Opsional: Tambahkan gambar baru ke galeri produk. Format yang didukung: JPEG, PNG, JPG, GIF. Maksimal 2MB per gambar.
                                    </p>
                                    <InputError :message="form.errors.gallery" class="mt-2" />
                                </div>
                            </div>

                            <div>
                                <div class="mb-6">
                                    <Label>Fitur Produk</Label>
                                    <div class="space-y-2 mt-2">
                                        <div v-for="(feature, index) in productFeatures" :key="`feature-${index}`" class="flex items-start gap-2">
                                            <div class="flex-1">
                                                <Input
                                                    v-model="feature.text"
                                                    type="text"
                                                    placeholder="Masukkan fitur produk"
                                                    class="w-full"
                                                />
                                            </div>
                                            <Button
                                                type="button"
                                                variant="destructive"
                                                size="icon"
                                                @click="removeFeature(index)"
                                                class="h-10 w-10"
                                            >
                                                <X class="w-4 h-4" />
                                            </Button>
                                        </div>
                                        <Button
                                            type="button"
                                            variant="outline"
                                            @click="addFeature"
                                            class="mt-2 w-full"
                                        >
                                            Tambah Fitur
                                        </Button>
                                    </div>
                                    <p class="text-xs text-muted-foreground mt-1">
                                        Opsional: Tambahkan fitur-fitur produk
                                    </p>
                                </div>

                                <div class="mb-6">
                                    <Label>Nilai/Keunggulan Produk</Label>
                                    <div class="space-y-2 mt-2">
                                        <div v-for="(value, index) in productValues" :key="`value-${index}`" class="flex items-start gap-2">
                                            <div class="flex-1">
                                                <Input
                                                    v-model="value.text"
                                                    type="text"
                                                    placeholder="Masukkan nilai/keunggulan produk"
                                                    class="w-full"
                                                />
                                            </div>
                                            <Button
                                                type="button"
                                                variant="destructive"
                                                size="icon"
                                                @click="removeValue(index)"
                                                class="h-10 w-10"
                                            >
                                                <X class="w-4 h-4" />
                                            </Button>
                                        </div>
                                        <Button
                                            type="button"
                                            variant="outline"
                                            @click="addValue"
                                            class="mt-2 w-full"
                                        >
                                            Tambah Nilai/Keunggulan
                                        </Button>
                                    </div>
                                    <p class="text-xs text-muted-foreground mt-1">
                                        Opsional: Tambahkan nilai/keunggulan produk
                                    </p>
                                </div>

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
            :open="showDeleteConfirm"
            @update:open="showDeleteConfirm = $event"
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
import { Textarea } from '@/components/ui/textarea';
import Combobox from '@/components/ui/combobox/Combobox.vue';
import InputError from '@/components/InputError.vue';
import { router } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';
import ConfirmationDialog from '@/components/ui/ConfirmationDialog.vue';
import { toast } from 'vue-sonner';

// Breadcrumbs untuk navigasi yang digunakan di AppLayout
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

// Computed property untuk breadcrumb items
const breadcrumbItems = computed(() => {
    return [
        {
            title: 'Dashboard',
            href: route('admin.dashboard'),
        },
        {
            title: 'Produk',
            href: route('admin.products.index'),
        },
        {
            title: 'Edit Produk',
            href: '#',
            current: true
        }
    ];
});

const props = defineProps({
    product: Object,
    categories: {
        type: Array,
        default: () => []
    }
});

// Tambahkan log untuk melihat data produk
console.log("Product data received:", props.product);
console.log("Product features:", props.product.product_features);
console.log("Product values:", props.product.product_values);

// State untuk dialog hapus gambar
const imageToDelete = ref(null);
const showDeleteConfirm = ref(false);
const loading = ref(false);

// Preview gambar utama baru
const featuredImagePreview = ref(null);

// Tambahkan array untuk menangani gambar galeri baru
const newGalleryImages = ref([]);

// Inisialisasi fitur dan nilai produk
const initProductFeatures = () => {
    console.log("Product features from DB:", props.product.product_features);
    let features = [];
    
    // Kasus 1: Array biasa
    if (props.product.product_features && Array.isArray(props.product.product_features)) {
        features = props.product.product_features.map(feature => ({ text: feature }));
    } 
    // Kasus 2: String JSON
    else if (typeof props.product.product_features === 'string' && props.product.product_features) {
        try {
            const parsed = JSON.parse(props.product.product_features);
            if (Array.isArray(parsed)) {
                features = parsed.map(feature => ({ text: feature }));
            }
        } catch (e) {
            console.error("Error parsing features:", e);
        }
    }
    
    // Jika tidak ada data, tambahkan field kosong
    if (features.length === 0) {
        features.push({ text: '' });
    }
    
    return features;
};

const initProductValues = () => {
    console.log("Product values from DB:", props.product.product_values);
    let values = [];
    
    // Kasus 1: Array biasa
    if (props.product.product_values && Array.isArray(props.product.product_values)) {
        values = props.product.product_values.map(value => ({ text: value }));
    } 
    // Kasus 2: String JSON
    else if (typeof props.product.product_values === 'string' && props.product.product_values) {
        try {
            const parsed = JSON.parse(props.product.product_values);
            if (Array.isArray(parsed)) {
                values = parsed.map(value => ({ text: value }));
            }
        } catch (e) {
            console.error("Error parsing values:", e);
        }
    }
    
    // Jika tidak ada data, tambahkan field kosong
    if (values.length === 0) {
        values.push({ text: '' });
    }
    
    return values;
};

const productFeatures = ref(initProductFeatures());
const productValues = ref(initProductValues());

const form = useForm({
    name: props.product.name,
    category_id: props.product.category_id,
    price: props.product.price,
    description: props.product.description,
    featured_image: null,
    gallery: props.product.gallery || [],
    custom_url: props.product.custom_url,
    demo_url: props.product.demo_url,
    product_code: props.product.product_code,
    _method: 'PUT' // Metode HTTP yang benar untuk update
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

// Fungsi untuk menangani penambahan gambar galeri baru
const handleGalleryImagesChange = (event) => {
    if (event.target.files && event.target.files.length > 0) {
        newGalleryImages.value = Array.from(event.target.files);
    }
};

// URLs untuk gambar yang ada
const featuredImageUrl = computed(() => {
    return `/storage/${props.product.featured_image}`;
});

// Transform categories for Combobox component
const categories = computed(() => {
    return props.categories.map(category => ({
        label: category.name,
        value: category.id
    }));
});

// Pass categories directly to the Combobox
const categoryOptions = computed(() => {
    return categories.value;
});

// Fungsi untuk mengelola fitur produk
const addFeature = () => {
    productFeatures.value.push({ text: '' });
};

const removeFeature = (index) => {
    productFeatures.value.splice(index, 1);
    // Pastikan selalu ada minimal satu field
    if (productFeatures.value.length === 0) {
        productFeatures.value.push({ text: '' });
    }
};

// Fungsi untuk mengelola nilai produk
const addValue = () => {
    productValues.value.push({ text: '' });
};

const removeValue = (index) => {
    productValues.value.splice(index, 1);
    // Pastikan selalu ada minimal satu field
    if (productValues.value.length === 0) {
        productValues.value.push({ text: '' });
    }
};

const submit = () => {
    loading.value = true;
    
    const formData = new FormData();
    formData.append("_method", "PUT");
    formData.append("name", form.name);
    formData.append("description", form.description);
    formData.append("price", form.price);
    formData.append("custom_url", form.custom_url);
    formData.append("demo_url", form.demo_url);
    
    if (form.category_id) {
        formData.append("category_id", form.category_id);
    }
    
    if (form.featured_image && typeof form.featured_image !== 'string') {
        formData.append("featured_image", form.featured_image);
    }
    
    if (newGalleryImages.value.length > 0) {
        newGalleryImages.value.forEach((image) => {
            formData.append("gallery[]", image);
        });
    }
    
    // Tambahkan gallery_ids yang akan dipertahankan
    form.gallery.forEach((item) => {
        formData.append("gallery_ids[]", item.id);
    });
    
    // Menambahkan product_features dan product_values sebagai JSON
    if (productFeatures.value.length > 0) {
        const features = productFeatures.value.filter(f => f.text.trim() !== '').map(f => f.text);
        formData.append("product_features", JSON.stringify(features));
    }
    
    if (productValues.value.length > 0) {
        const values = productValues.value.filter(v => v.text.trim() !== '').map(v => v.text);
        formData.append("product_values", JSON.stringify(values));
    }
    
    router.post(route("admin.products.update", props.product.id), formData, {
        onSuccess: () => {
            loading.value = false;
            toast.success("Produk berhasil diperbarui");
            newGalleryImages.value = [];
            showDeleteConfirm.value = false;
            imageToDelete.value = null;
        },
        onError: (errors) => {
            loading.value = false;
            toast.error("Gagal memperbarui produk");
            console.error(errors);
        },
        forceFormData: true,
    });
};

const showDeleteGalleryDialog = (image) => {
    imageToDelete.value = image;
    showDeleteConfirm.value = true;
};

const deleteGalleryImage = () => {
    if (!imageToDelete.value) return;
    
    loading.value = true;
    
    router.delete(route('admin.products.gallery.delete', imageToDelete.value.id), {
        preserveScroll: true,
        only: ['product'], // Hanya refresh data product
        onSuccess: () => {
            toast.success('Berhasil', {
                description: 'Gambar berhasil dihapus dari galeri',
            });
            showDeleteConfirm.value = false;
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