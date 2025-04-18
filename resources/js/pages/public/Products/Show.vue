<template>
  <Head :title="product.name" />

  <MainLayout>
    <div class="bg-gray-50 py-12">
      <div class="container mx-auto px-4">
        <!-- Breadcrumb -->
        <div class="bg-white p-4 rounded-lg shadow-sm mb-8">
          <Breadcrumb :items="breadcrumbItems" />
        </div>

        <!-- Product Detail -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
          <!-- Product Images -->
          <div class="bg-white rounded-xl shadow-sm p-6">
            <!-- Main Image -->
            <div class="mb-6 overflow-hidden rounded-lg">
              <img :src="selectedImage" :alt="product.name" class="w-full h-auto max-h-[500px] object-contain rounded-md" />
            </div>
            
            <!-- Thumbnail Gallery -->
            <div 
              v-if="product.gallery && product.gallery.length > 0" 
              class="grid grid-cols-5 xl:grid-cols-6 gap-3"
            >
              <!-- Featured Image Thumbnail -->
              <div 
                class="aspect-square overflow-hidden cursor-pointer rounded-md border-2 transition-all duration-200"
                :class="selectedImageIndex === -1 ? 'border-primary-600 scale-[1.05] shadow-md' : 'border-transparent hover:border-primary-300'"
                @click="selectImage(-1)"
              >
                <img 
                  :src="`/storage/${product.featured_image}`" 
                  :alt="product.name" 
                  class="w-full h-full object-cover"
                />
              </div>
              
              <!-- Gallery Images -->
              <div 
                v-for="(image, index) in product.gallery" 
                :key="image.id" 
                class="aspect-square overflow-hidden cursor-pointer rounded-md border-2 transition-all duration-200"
                :class="selectedImageIndex === index ? 'border-primary-600 scale-[1.05] shadow-md' : 'border-transparent hover:border-primary-300'"
                @click="selectImage(index)"
              >
                <img 
                  :src="`/storage/${image.image}`" 
                  :alt="`${product.name} - Gambar ${index + 1}`" 
                  class="w-full h-full object-cover"
                />
              </div>
            </div>
          </div>
          
          <!-- Product Info -->
          <div class="bg-white rounded-xl shadow-sm p-6">
            <h1 class="text-3xl font-bold mb-2">{{ product.name }}</h1>
            
            <div class="flex flex-wrap items-center gap-2 mb-6">
              <Badge v-if="product.category" variant="outline">{{ product.category.name }}</Badge>
              <Badge variant="secondary">{{ product.is_active ? 'Tersedia' : 'Tidak Tersedia' }}</Badge>
            </div>
            
            <div class="text-3xl font-bold text-primary-600 mb-6">
              {{ formatPrice(product.price) }}
            </div>
            
            <div class="prose prose-sm mb-8 max-w-none">
              <div v-html="product.description"></div>
            </div>
            
            <div class="border-t border-gray-200 pt-6 mb-8">
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <Button class="w-full">
                    <ShoppingCartIcon class="w-4 h-4 mr-2" />
                    Tambah ke Keranjang
                  </Button>
                </div>
                <div>
                  <Button variant="outline" class="w-full">
                    <HeartIcon class="w-4 h-4 mr-2" />
                    Tambah ke Wishlist
                  </Button>
                </div>
              </div>
            </div>
            
            <div class="border-t border-gray-200 pt-6">
              <div class="text-sm text-gray-600 grid gap-2">
                <div class="flex">
                  <span class="w-32 font-medium">Kode Produk:</span>
                  <span>{{ product.id }}</span>
                </div>
                <div class="flex" v-if="product.custom_url">
                  <span class="w-32 font-medium">URL Khusus:</span>
                  <span>{{ product.custom_url }}</span>
                </div>
              </div>
              
              <!-- Share Links -->
              <div class="mt-6">
                <p class="text-sm font-medium mb-3">Bagikan produk ini:</p>
                <div class="flex items-center gap-2">
                  <Button variant="outline" size="icon" @click="shareOnFacebook" class="rounded-full h-10 w-10">
                    <FacebookIcon class="w-5 h-5" />
                  </Button>
                  <Button variant="outline" size="icon" @click="shareOnTwitter" class="rounded-full h-10 w-10">
                    <TwitterIcon class="w-5 h-5" />
                  </Button>
                  <Button variant="outline" size="icon" @click="shareOnWhatsApp" class="rounded-full h-10 w-10">
                    <MessageCircleIcon class="w-5 h-5" />
                  </Button>
                  <Button variant="outline" size="icon" @click="copyLink" class="rounded-full h-10 w-10">
                    <LinkIcon class="w-5 h-5" />
                  </Button>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Related Products -->
        <div v-if="relatedProducts.length > 0" class="mb-8">
          <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold">Produk Terkait</h2>
            <Link :href="route('products.index')" class="text-primary-600 hover:text-primary-800 text-sm font-medium">Lihat Semua Produk</Link>
          </div>
          
          <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <div 
              v-for="product in relatedProducts" 
              :key="product.id" 
              class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 group"
            >
              <Link :href="product.url" class="block h-full flex flex-col">
                <div class="aspect-square overflow-hidden bg-gray-100 relative">
                  <img 
                    :src="`/storage/${product.featured_image}`" 
                    :alt="product.name" 
                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                  />
                  <div v-if="product.category" class="absolute top-2 left-2">
                    <Badge variant="primary" class="bg-primary/80 backdrop-blur-sm">{{ product.category.name }}</Badge>
                  </div>
                </div>
                <div class="p-5 flex flex-col flex-grow">
                  <h3 class="text-lg font-bold mb-2 line-clamp-2 group-hover:text-primary-600 transition-colors">{{ product.name }}</h3>
                  <p class="text-sm text-gray-500 mb-3 line-clamp-2">
                    {{ truncateDescription(product.description) }}
                  </p>
                  <div class="mt-auto flex justify-between items-center">
                    <div class="text-xl font-bold text-primary-600">
                      {{ formatPrice(product.price) }}
                    </div>
                    <Button variant="outline" size="sm" class="group-hover:bg-primary group-hover:text-white transition-colors">
                      Lihat Detail
                    </Button>
                  </div>
                </div>
              </Link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </MainLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import Breadcrumb from '@/components/ui/breadcrumb.vue';
import MainLayout from '@/components/layout/MainLayout.vue';
import { ref, computed } from 'vue';
import { 
  ShoppingCartIcon, 
  HeartIcon, 
  FacebookIcon,
  TwitterIcon,
  MessageCircleIcon,
  LinkIcon
} from 'lucide-vue-next';
import { toast } from 'vue-sonner';

const props = defineProps({
  product: Object,
  relatedProducts: Array
});

// Gambar yang dipilih untuk ditampilkan
const selectedImageIndex = ref(-1); // -1 berarti gambar utama

// URL gambar yang sedang ditampilkan
const selectedImage = computed(() => {
  if (selectedImageIndex.value === -1) {
    return `/storage/${props.product.featured_image}`;
  } else {
    return `/storage/${props.product.gallery[selectedImageIndex.value].image}`;
  }
});

// Memilih gambar untuk ditampilkan
const selectImage = (index) => {
  selectedImageIndex.value = index;
};

// Format harga
const formatPrice = (price) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0
  }).format(price);
};

// Menghilangkan HTML dan memotong teks deskripsi
const truncateDescription = (html) => {
  if (!html) return '';
  // Hapus tag HTML
  const text = html.replace(/<[^>]*>/g, '');
  return text.length > 60 ? text.substring(0, 60) + '...' : text;
};

// Share functions
const shareOnFacebook = () => {
  const url = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(window.location.href)}`;
  window.open(url, '_blank');
};

const shareOnTwitter = () => {
  const text = `Lihat produk ${props.product.name}`;
  const url = `https://twitter.com/intent/tweet?text=${encodeURIComponent(text)}&url=${encodeURIComponent(window.location.href)}`;
  window.open(url, '_blank');
};

const shareOnWhatsApp = () => {
  const text = `Lihat produk ${props.product.name}: ${window.location.href}`;
  const url = `https://wa.me/?text=${encodeURIComponent(text)}`;
  window.open(url, '_blank');
};

const copyLink = () => {
  navigator.clipboard.writeText(window.location.href).then(() => {
    toast.success('Link berhasil disalin!');
  });
};

// Breadcrumb data
const breadcrumbItems = computed(() => {
  const items = [
    { label: 'Beranda', href: route('home') },
    { label: 'Produk', href: route('products.index') },
  ];

  if (props.product.category) {
    items.push({
      label: props.product.category.name,
      href: route('products.index', { category: props.product.category.slug })
    });
  }

  items.push({ label: props.product.name });
  
  return items;
});
</script> 